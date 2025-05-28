<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\DetailHeures;
use App\Entity\Employe;
use App\Repository\CentreDeChargeRepository;
use App\Repository\DetailHeuresRepository;
use App\Repository\EmployeRepository;
use App\Repository\FavoriTypeHeureRepository;
use App\Repository\StatutRepository;
use App\Repository\TacheRepository;
use App\Repository\TacheSpecifiqueRepository;
use App\Repository\TypeHeuresRepository;
use App\Repository\ActiviteRepository;
use App\Service\DetailHeureService;
use Psr\Log\LoggerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

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
class TempsController extends AbstractController
{
    public function __construct(public LoggerInterface $logger, public CentreDeChargeRepository $CDGRepository, public DetailHeuresRepository $detailHeuresRepository, public DetailHeureService $detailHeureService, public EmployeRepository $employeRepository, public Security $security, public StatutRepository $statutRepository, public TacheRepository $tacheRepository, public TacheSpecifiqueRepository $tacheSpecifiqueRepository, public TypeHeuresRepository $typeHeuresRepo) {}

    // Affiche la page de saisie des temps
    #[Route('/temps', name: 'temps')]
    public function temps(FavoriTypeHeureRepository $favoriTypeHeureRepository): Response
    {
        /** @var Employe $user */
        $user = $this->getUser();

        $nbHeures = $this->detailHeuresRepository->getNbHeures($user->getUserIdentifier());
        if ($nbHeures >= 12) {
            $message = "Votre avez atteint votre limite d'heures journalières";
            $this->addFlash('warning', $message);
        }
        $this->detailHeureService->cleanLastWeek();

        $favoriTypeHeure = $favoriTypeHeureRepository->findOneBy(['employe' => $user]);

        // Rendre la vue 'temps/temps.html.twig' en passant les variables
        return $this->render(
            'temps/temps.html.twig',
            [
                'details' => $this->detailHeuresRepository->findAllTodayUser(),
                'types' => $this->typeHeuresRepo->findAll(),
                'taches' => $this->tacheRepository->findAll(),
                'tachesSpe' => $this->tacheSpecifiqueRepository->findAllSite(),
                'CDG' => $this->CDGRepository->findAllUser(),
                'nbHeures' => $nbHeures,
                'favoriTypeHeure' => $favoriTypeHeure,
                'site' => substr((string) $user->getUserIdentifier(), 0, 2),
                'selectedTypeId' => $favoriTypeHeure?->getTypeHeure()?->getId() ?? null,
            ]
        );
    }

    #[Route('/chargement-formulaire/{typeId}', name: 'chargement_formulaire')]
    public function loadFormulaireParType(int $typeId, TypeHeuresRepository $typeRepo, FavoriTypeHeureRepository $favoriRepo, TacheRepository $tacheRepo, CentreDeChargeRepository $cdgRepo, Request $request, EntityManagerInterface $entityManager): Response 
    {
        $type = $typeRepo->find($typeId);
        /** @var Employe $user */
        $user = $this->getUser();

        if (!$type || !$user) {
            $formulaireHtml = $this->renderView('temps/_default.html.twig', []);
            $favoriHtml = $this->renderView('temps/_btnFavoris.html.twig', [
                'selectedTypeId' => $typeId,
                'favoriTypeHeure' => $favoriRepo->findOneBy(['employe' => $user])
            ]);

            $turboStreams = <<<HTML
                <turbo-stream action="replace" target="formulaire_saisie">
                    <template>$formulaireHtml</template>
                </turbo-stream>
                <turbo-stream action="replace" target="frame-favori-btn">
                    <template>$favoriHtml</template>
                </turbo-stream>
            HTML;

            return new Response($turboStreams, 200, ['Content-Type' => 'text/vnd.turbo-stream.html']);
        }

        $detailHeures = new DetailHeures();
        $centre = $user->getCentreDeCharge();

        if ($centre) {
            $detailHeures->setCentreDeCharge($centre);
        }

        $detailHeures->setTypeHeures($type);

        $formTypeClass = $this->getFormTypeByNom($type->getNomType());
        $form = $this->createForm($formTypeClass, $detailHeures, [
            'site' => substr($centre->getId(), 0, 3),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($detailHeures);
            $entityManager->flush();

            return $this->redirectToRoute('chargement_formulaire', ['typeId' => $typeId]);
        }

        $formulaireHtml = $this->renderView(sprintf('temps/_%s.html.twig', strtolower((new AsciiSlugger())->slug($type->getNomType()))), [
            'nbHeures' => $this->detailHeuresRepository->getNbHeures($user->getUserIdentifier()),
            'formHeures' => $form->createView(),
            'type' => $type,
            'taches' => $tacheRepo->findBy(['typeHeures' => $type]),
            'tachesSpe' => $this->tacheSpecifiqueRepository->findAllSite(),
            'CDG' => $centre ? $cdgRepo->findBySitePrefix(substr($centre->getId(), 0, 3)) : [],
            'site' => substr($user->getUserIdentifier(), 0, 2),
        ]);

        $favoriHtml = $this->renderView('temps/_btnFavoris.html.twig', [
            'selectedTypeId' => $typeId,
            'favoriTypeHeure' => $favoriRepo->findOneBy(['employe' => $user])
        ]);

        $turboStreams = <<<HTML
            <turbo-stream action="replace" target="formulaire_saisie">
                <template>$formulaireHtml</template>
            </turbo-stream>
            <turbo-stream action="replace" target="frame-favori-btn">
                <template>$favoriHtml</template>
            </turbo-stream>
        HTML;

        return new Response($turboStreams, 200, ['Content-Type' => 'text/vnd.turbo-stream.html']);
    }



    #[Route('/temps/soumission-formulaire/{typeId}', name: 'soumission_formulaire')]
    public function soumettreFormulaireParType(int $typeId, Request $request, TypeHeuresRepository $typeRepo, EntityManagerInterface $entityManager, TacheRepository $tacheRepo, CentreDeChargeRepository $cdgRepo): Response 
    {
        $type = $typeRepo->find($typeId);

        if (!$type) {
            $this->addFlash('error', 'Type d\'heure invalide.');
            $alertsHtml = $this->renderView('alert.html.twig');

            if ($request->headers->get('Turbo-Frame')) {
                return new Response(<<<HTML
                    <turbo-stream action="update" target="flash-messages">
                        <template>$alertsHtml</template>
                    </turbo-stream>
                HTML, 200, ['Content-Type' => 'text/vnd.turbo-stream.html']);
            }

            return $this->redirectToRoute('temps');
        }

        /** @var Employe $user */
        $user = $this->getUser();
        $centreDeChargeEmploye = $user instanceof Employe ? $user->getCentreDeCharge() : null;

        $heure = new DetailHeures();
        $heure->setTypeHeures($type);
        $heure->setEmploye($user);
        $heure->setCentreDeCharge($centreDeChargeEmploye);
        $heure->setDate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));

        $formTypeClass = $this->getFormTypeByNom($type->getNomType());

        $form = $this->createForm($formTypeClass, $heure, [
            'site' => $centreDeChargeEmploye ? substr($centreDeChargeEmploye->getId(), 0, 3) : null,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nbHeures = $this->detailHeuresRepository->getNbHeures($user->getUserIdentifier());
            $heuresSaisies = $heure->getTempsMainOeuvre();

            if (($nbHeures + $heuresSaisies) > 12) {
                $this->addFlash('warning', 'Impossible d’ajouter cette saisie : vous dépasseriez les 12 heures autorisées par jour.');
                $alertsHtml = $this->renderView('alert.html.twig');

                if ($request->headers->get('Turbo-Frame')) {
                    return new Response(<<<HTML
                        <turbo-stream action="update" target="flash-messages">
                            <template>$alertsHtml</template>
                        </turbo-stream>
                    HTML, 200, ['Content-Type' => 'text/vnd.turbo-stream.html']);
                }

                return $this->redirectToRoute('chargement_formulaire', ['typeId' => $typeId]);
            }

            if ($form->has('ordre')) {
                $suffixe = $form->get('ordre')->getData();
                $prefixe = substr($centreDeChargeEmploye->getId(), 0, 2);
                $heure->setOrdre($prefixe . $suffixe);
            }

            $entityManager->persist($heure);
            $entityManager->flush();

            $this->addFlash('success', 'Heure enregistrée avec succès.');

            $action = $request->request->get('action');
            if ($action === 'quitter') {
                return $this->redirectToRoute('deconnexion');
            }

            if ($request->headers->get('Turbo-Frame')) {
                $alertsHtml = $this->renderView('alert.html.twig');

                return new Response(<<<HTML
                    <turbo-stream action="update" target="flash-messages">
                        <template>$alertsHtml</template>
                    </turbo-stream>
                    <turbo-stream action="reload" target="formulaire_saisie"></turbo-stream>
                    <turbo-stream action="reload" target="frameNbHeures"></turbo-stream>
                HTML, 200, ['Content-Type' => 'text/vnd.turbo-stream.html']);
            }

            return $this->redirectToRoute('chargement_formulaire', ['typeId' => $typeId]);
        }

        $template = sprintf('temps/_%s.html.twig', strtolower((new AsciiSlugger())->slug($type->getNomType())));
        $context = [
            'formHeures' => $form->createView(),
            'type' => $type,
            'nbHeures' => $this->detailHeuresRepository->getNbHeures($user->getUserIdentifier()),
            'taches' => $tacheRepo->findBy(['typeHeures' => $type]),
            'tachesSpe' => $this->tacheSpecifiqueRepository->findAllSite(),
            'CDG' => $cdgRepo->findAll(),
            'site' => substr($user->getUserIdentifier(), 0, 2),
        ];

        if ($request->headers->get('Turbo-Frame')) {
            $formHtml = $this->renderView($template, $context);

            return new Response(<<<HTML
                <turbo-stream action="replace" target="formulaire_saisie">
                    <template>$formHtml</template>
                </turbo-stream>
            HTML, 200, ['Content-Type' => 'text/vnd.turbo-stream.html']);
        }

        return $this->render($template, $context);
    }


    #[Route('/type-select', name: 'type_select', methods: ['GET'])]
    public function typeSelectRedirect(Request $request, FavoriTypeHeureRepository $favoriRepo, Security $security, TypeHeuresRepository $typeRepo, TacheRepository $tacheRepo, CentreDeChargeRepository $cdgRepo): Response 
    {
        $typeId = (int) $request->query->get('type', 0);
        $user = $security->getUser();
        $type = $typeRepo->find($typeId);

        // Sécurité minimale
        if (!$type) {
            $template = $this->renderView('temps/_default.html.twig');
            return new Response($template, 200, ['Content-Type' => 'text/html']);
        }

        $heure = new DetailHeures();
        $heure->setTypeHeures($type);

        $formTypeClass = $this->getFormTypeByNom($type->getNomType());
        $centreDeChargeEmploye = $user instanceof Employe ? $user->getCentreDeCharge() : null;
        
        $formHeures = $this->createForm($formTypeClass, $heure, [
            'site' => $centreDeChargeEmploye ? substr($centreDeChargeEmploye->getId(), 0, 3) : null,
        ]);

        $formulaireHtml = $this->renderView('temps/_' . strtolower((new AsciiSlugger())->slug($type->getNomType())) . '.html.twig', [
            'type' => $type,
            'nbHeures' => $this->detailHeuresRepository->getNbHeures($user->getUserIdentifier()),
            'formHeures' => $formHeures->createView(),
            'taches' => $tacheRepo->findBy(['typeHeures' => $type]),
            'tachesSpe' => $this->tacheSpecifiqueRepository->findAllSite(),
            'CDG' => $cdgRepo->findAll(),
            'site' => substr((string) $user->getUserIdentifier(), 0, 2),
        ]);

        $favoriHtml = $this->renderView('temps/_btnFavoris.html.twig', [
            'selectedTypeId' => $typeId,
            'favoriTypeHeure' => $favoriRepo->findOneBy(['employe' => $user])
        ]);

        $turboStreams = <<<HTML
            <turbo-stream action="replace" target="frames-formulaire-favori">
                <template>$formulaireHtml</template>
            </turbo-stream>
            <turbo-stream action="replace" target="frame-favori-btn">
                <template>$favoriHtml</template>
            </turbo-stream>
        HTML;

        return new Response($turboStreams, 200, ['Content-Type' => 'text/vnd.turbo-stream.html']);
    }

    #[Route('/chargement-formulaire', name: 'chargement_formulaire_redirect', methods: ['GET'])]
    public function redirectChargementFormulaire(Request $request): Response
    {
        $typeId = $request->query->getInt('typeId', 0);
        return $this->redirectToRoute('chargement_formulaire', ['typeId' => $typeId]);
    }

    private function getFormTypeByNom(string $nomType): string
    {
        return match (strtolower($nomType)) {
            'générale', 'generale' => \App\Form\AjoutGeneraleType::class,
            'fabrication' => \App\Form\AjoutFabricationType::class,
            'service' => \App\Form\AjoutServiceType::class,
            'projet' => \App\Form\AjoutProjetType::class,
        };
    }

    #[Route('/verifier-activite/{id}', name: 'verifier_activite', methods: ['GET'])]
    public function verifierActivite(int $id, ActiviteRepository $activiteRepository): JsonResponse 
    {
        $activite = $activiteRepository->find($id);

        if ($activite) {
            return new JsonResponse([
                'trouve' => true,
                'nom' => $activite->getName(),
            ]);
        }

        return new JsonResponse([
            'trouve' => false,
            'message' => 'Aucune activité trouvée avec cet ID.'
        ]);
    }

}