<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Modules;
use App\Entity\TypeModule;

class ModulesController extends AbstractController
{
    #[Route("/modules", name: 'Modules')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $modules = $entityManager->getRepository(Modules::class)->findAll();
        return $this->render('Modules/liste.html.twig', [
            'modules' => $modules,
        ]);
    }

    #[Route('/ajouterModule', name: 'AjouterModule')]
    public function ajouter_module(): Response
    {
        return $this->render('Modules/add_module.html.twig', [
            'controller_name' => 'Modules Controller',
        ]);
    }

    #[Route('/ajouterTypeModule', name: 'AjouterTypeModule')]
    public function ajouter_type_module(): Response
    {
        return $this->render('Modules/add_type_module.html.twig', [
            'controller_name' => 'Modules Controller',
        ]);
    }

    #[Route('/module/{id}', name: 'Module')]
    public function module(EntityManagerInterface $entityManager, $id): Response
    {
        $module = $entityManager->getRepository(Modules::class)->find($id);
        if(!$module) {
            return $this->redirectToRoute('Modules');
        }
        return $this->render('Modules/graph.html.twig', [
            'id' => $module->getId(),
            'description' => $module->getDescription(),
            'type' => $module->getTypeModule()
        ]);
    }

    #[Route('/notifications', name: 'Notifications')]
    public function notifications(EntityManagerInterface $entityManager): Response
    {
        return $this->render('Modules/notifications.html.twig', [
            'controller_name' => 'Modules Controller',
        ]);
    }
}
