<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\DetailHeures;
use App\Entity\Employe;
use App\Entity\FavoriTypeHeure;
use App\Form\FiltreDateType;
use App\Form\FiltreResponsableType;
use App\Repository\CentreDeChargeRepository;
use App\Repository\DetailHeuresRepository;
use App\Repository\EmployeRepository;
use App\Repository\FavoriTypeHeureRepository;
use App\Repository\StatutRepository;
use App\Repository\TacheRepository;
use App\Repository\TacheSpecifiqueRepository;
use App\Repository\TypeHeuresRepository;
use App\Service\DetailHeureService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface as FormFormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @property LoggerInterface           $logger
 * @property CentreDeChargeRepository  $CDGRepository
 * @property DetailHeuresRepository    $detailHeuresRepository
 * @property DetailHeureService        $detailHeureService
 * @property EmployeRepository         $employeRepository
 * @property Security                  $security
 * @property StatutRepository          $statutRepository
 * @property TacheRepository           $tacheRepository
 * @property TacheSpecifiqueRepository $tacheSpecifiqueRepository
 * @property TypeHeuresRepository      $typeHeuresRepo
 */
class IndexController extends AbstractController
{
    public function __construct(public LoggerInterface $logger, public CentreDeChargeRepository $CDGRepository, public DetailHeuresRepository $detailHeuresRepository, public DetailHeureService $detailHeureService, public EmployeRepository $employeRepository, public Security $security, public StatutRepository $statutRepository, public TacheRepository $tacheRepository, public TacheSpecifiqueRepository $tacheSpecifiqueRepository, public TypeHeuresRepository $typeHeuresRepo) {}

    // Affiche la page d'identification
    #[Route('/', name: 'home')]
    public function index(CacheInterface $cache): Response
    {
        return $cache->get(
            'index_page',
            function (ItemInterface $item) {
                $item->expiresAfter(43200);

                /** @var Employe $user */
                $user = $this->getUser();

                return $this->render(
                    'connexion/identification.html.twig',
                    [
                        'user' => $user,
                    ]
                );
            }
        );
    }

    // Redirige lors de l'accès refusé
    #[Route('/access_denied', name: 'access_denied')]
    public function accessDenied(): Response
    {
        $route = 'home';
        if ($this->isGranted('ROLE_EMPLOYE')) {
            $route = 'temps';
        } elseif ($this->isGranted('ROLE_RESPONSABLE') || $this->isGranted('ROLE_ADMIN')) {
            $route = 'console';
        }

        return $this->redirectToRoute($route);
    }

    #[Route('/favori/type-heure', name: 'favori_type_heure', methods: ['POST'])]
    public function setFavoriTypeHeure(Request $request, Security $security, EntityManagerInterface $em, TypeHeuresRepository $typeHeuresRepo, FavoriTypeHeureRepository $favoriRepo): Response
    {
        $employe = $security->getUser();

        $typeId = $request->request->get('type_heure_id');

        $typeHeure = $typeHeuresRepo->find($typeId);

        $ancienFavori = $favoriRepo->findOneBy(['employe' => $employe]);

        if ($ancienFavori) {
            $ancienFavori->setTypeHeure($typeHeure);
        } else {
            $favori = new FavoriTypeHeure();
            $favori->setEmploye($employe);
            $favori->setTypeHeure($typeHeure);
            $em->persist($favori);
        }
        $em->flush();


        $this->addFlash('success', 'Type d\'heure favori enregistré !');
        if (str_contains($request->headers->get('Accept'), 'text/vnd.turbo-stream.html')) {
            $alertsHtml = $this->renderView('alert.html.twig');
            return new Response($alertsHtml, 200, ['Content-Type' => 'text/vnd.turbo-stream.html']);
        }

        return new Response('', 204);
    }

    // Affiche la page d'historique
    #[Route('/historique', name: 'historique')]
    public function historique(): Response
    {
        /** @var Employe $user */
        $user = $this->getUser();
        $nbHeures = $this->detailHeuresRepository->getNbHeures($user->getUserIdentifier());
        if ($nbHeures >= 12) {
            $message = "Votre avez atteint votre limite d'heures journalières";

            $this->addFlash('warning', $message);
        }
        $this->detailHeureService->cleanLastWeek();

        return $this->render(
            'historique.html.twig',
            [
                'details' => $this->detailHeuresRepository->findAllTodayUser(),
                'nbHeures' => $nbHeures,
            ]
        );
    }

    // Affiche la page de console d'approbation
    #[Route('/console', name: 'console')]
    public function console(Request $request): Response
    {
        /** @var Employe $user */
        $user = $this->getUser();

        $session = $request->getSession();
        //  Utilisateur responsable par défaut
        if (!$session->has('responsablesId')) {
            $responsablesId[0] = $user->getUserIdentifier();
            $session->set('responsablesId', $responsablesId);
        }
        $heures = [];

        // Formulaire de filtre des responsables
        $formResponsable = $this->createForm(
            FiltreResponsableType::class,
            null,
            [
                'user' => $user,
                'data' => $this->employeRepository->findEmploye($session->get('responsablesId')),
            ]
        );

        $this->setResponsables($formResponsable, $request, $session);

        $dates = $this->detailHeuresRepository->findDatesDetail($session->get('responsablesId'));
        $tabEmployes = $this->employeRepository->findHeuresControle($session->get('responsablesId'));
        $nbAnomalie = 0;

        //  Date par défaut
        if (!$session->has('date')) {
            $session->set('date', -1);
        }

        if (!in_array($session->get('date'), $dates)) {
            $data = [-1];
            $session->set('date', -1);
        } else {
            $data = [$session->get('date')];
        }

        $formDate = $this->createForm(
            FiltreDateType::class,
            null,
            [
                'dates' => $dates,
                'data' => $data,
            ]
        );
        $formDate->handleRequest($request);

        $statutAnomalie = $this->statutRepository->getStatutAnomalie();
        $statutConforme = $this->statutRepository->getStatutConforme();

        if ($formDate->isSubmitted() && $formDate->isValid()) {
            $session->set('date', $formDate->get('date')->getData());
        }

        foreach ($tabEmployes as $unEmploye) {
            foreach ($unEmploye->getDetailHeures() as $value) {
                if ((-1 === $session->get('date') || $value->getDate()->format('d-m-Y') === $session->get('date'))
                    && ($value->getStatut() === $statutConforme || $value->getStatut() === $statutAnomalie)
                ) {
                    array_push($heures, $value);
                    $nbAnomalie += ($value->getStatut() === $statutAnomalie) ? 1 : 0;
                }
            }
        }

        $employes = $this->filtreEmploye($heures);

        return $this->render(
            'console/console.html.twig',
            [
                'formResponsable' => $formResponsable->createView(),
                'formDate' => $formDate->createView(),
                'user' => $user,
                'site' => substr((string) $user->getUserIdentifier(), 0, 2),
                'nbAnomalie' => $nbAnomalie,
                'employes' => $employes,
                'heures' => $heures,
                'taches' => $this->tacheRepository->findAll(),
                'tachesSpe' => $this->tacheSpecifiqueRepository->findAllSite(),
                'CDG' => $this->CDGRepository->findAllUser(),
                'titrePage' => "Console d'approbation des heures",
                'types' => $this->typeHeuresRepo->findAll(),
            ]
        );
    }

    #[Route('/centreon', name: 'centreon', methods: ['GET'])]
    public function centreon(): Response
    {
        return new Response('OK', \Symfony\Component\HttpFoundation\Response::HTTP_OK);
    }

    /**
     * Défini les responsables.
     *
     * @param FormFormInterface $formResponsable
     * @param Request           $request
     * @param SessionInterface  $session
     *
     *  */
    private function setResponsables($formResponsable, $request, $session): void
    {
        $responsables = [];
        $formResponsable->handleRequest($request);
        if ($formResponsable->isSubmitted() && $formResponsable->isValid()) {
            $responsableSelectionnes = $formResponsable->get('responsable')->getData();
            foreach ($responsableSelectionnes as $key => $value) {
                $responsables[$key] = $value->getUserIdentifier();
            }
            $session->set('responsablesId', $responsables);
        }
    }

    /**
     * Filtre les employés possédant des heures.
     *
     * @param DetailHeures[] $heures
     *
     * @return array<mixed>
     */
    private function filtreEmploye($heures): array
    {
        $i = 0;
        $j = 0;
        $tab[$i] = null;
        $tab = [[]];
        foreach ($heures as $uneHeure) {
            if (!in_array($uneHeure->getEmploye()->getUserIdentifier(), $tab[$i])) {
                ++$i;
                $j = 0;
                $tab[$i]['id'] = $uneHeure->getEmploye()->getUserIdentifier();
                $tab[$i]['nom'] = $uneHeure->getEmploye()->getNomEmploye();
                $tab[$i]['centreDeCharge'] = $uneHeure->getEmploye()->getCentreDeCharge()->getId();
                $tab[$i]['libelle'] = $uneHeure->getEmploye()->getCentreDeCharge()->getLibelle();
                $tab[$i]['heures'][$j] = $uneHeure;
                $tab[$i]['total'] = $uneHeure->getTempsMainOeuvre();
            } else {
                $tab[$i]['heures'][$j] = $uneHeure;
                $tab[$i]['total'] += $uneHeure->getTempsMainOeuvre();
            }
            ++$j;
        }
        array_shift($tab);

        return $tab;
    }
}
