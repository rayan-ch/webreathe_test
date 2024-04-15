<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Modules;


class APIController extends AbstractController
{
    #[Route('/api/modules/{id}', name: 'api', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $module = $entityManager->getRepository(Modules::class)->find($id);   
        $data = ['historique' => $module->getDonnees(), 'description' => $module->getDescription()];

        return $this->json($data);
    }

    #[Route('/api/modules/panne', name: 'api', methods: ['GET'])]
    public function panne(EntityManagerInterface $entityManager): JsonResponse
    {
        $modules = $entityManager->getRepository(Modules::class)->findAll();
        $nb_panne = 0;
        foreach ($modules as $key => $module) {
            if ($module->getEtat() != "Fonctionnel") {
                $nb_panne++;
            }
        }
        $data = ['nb_panne' => $nb_panne];

        return $this->json($data);
    }
}
