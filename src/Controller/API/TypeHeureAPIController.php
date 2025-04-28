<?php

namespace App\Controller\API;

use App\Entity\Employe;
use App\Entity\FavoriTypeHeure;
use App\Repository\FavoriTypeHeureRepository;
use App\Repository\TypeHeuresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TypeHeureAPIController extends AbstractController
{
    #[Route('/api/post/type_heure', name: 'api_type_heure_post', methods: ['POST'])]
    public function TypeHeurePost(Request $request, FavoriTypeHeureRepository $favoriTypeHeureRepository, TypeHeuresRepository $typeHeuresRepository, EntityManagerInterface $entityManager): Response
    {
        /** @var Employe $user */
        $user = $this->getUser();

        // Récupérer les données JSON envoyées dans la requête POST
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (!isset($data['type'])) {
            return new Response('type manquant', Response::HTTP_BAD_REQUEST);
        }

        $typeHeure = $typeHeuresRepository->find($data['type']);

        $favoriTypeHeure = $favoriTypeHeureRepository->findOneBy(['employe' => $user]);

        if ($favoriTypeHeure) {
            $favoriTypeHeure->setTypeHeure($typeHeure);
        } else {
            $favoriTypeHeure = new FavoriTypeHeure();
            $favoriTypeHeure->setTypeHeure($typeHeure);
            $favoriTypeHeure->setEmploye($user);
        }
        $entityManager->persist($favoriTypeHeure);
        $entityManager->flush();

        // Retourner une réponse 200
        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }
}
