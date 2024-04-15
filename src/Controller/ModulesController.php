<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Modules;
use App\Entity\TypeModule;

class ModulesController extends AbstractController
{
    #[Route('/', name: 'Accueil')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->render('Modules/index.html.twig', [
            'nb_alerts' => $this->getNbAlerts($entityManager)
        ]);
    }

    #[Route("/modules/", name: 'Modules')]
    public function modules(EntityManagerInterface $entityManager): Response
    {
        $modules = $entityManager->getRepository(Modules::class)->findAll();
        return $this->render('Modules/liste.html.twig', [
            'modules' => $modules,
            'nb_alerts' => $this->getNbAlerts($entityManager)
        ]);
    }

    #[Route("/modules/detail/{id}", name: 'Detail')]
    public function show_detail(EntityManagerInterface $entityManager, $id): Response
    {
        $module = $entityManager->getRepository(Modules::class)->find($id);
        if ($module) {
            return $this->render('Modules/detail.html.twig', [
                'module' => $module,
                'nb_alerts' => $this->getNbAlerts($entityManager)
            ]);
        } else {
            return $this->redirectToRoute('Modules');
        }
    }


    #[Route('/ajouterModule', name: 'AjouterModule')]
    public function ajouter_module(EntityManagerInterface $entityManager): Response
    {
        $type_modules = $entityManager->getRepository(TypeModule::class)->findAll();
        return $this->render('Modules/add_module.html.twig', [
            'type_modules' => $type_modules,
            'nb_alerts' => $this->getNbAlerts($entityManager)
        ]);
    }

    #[Route('/enregistrerModule', name: 'EnregistrerModule')]
    public function enregistrer_module(Request $request, EntityManagerInterface $entityManager): Response
    {
        dump($request->request);
        $request = $request->request;
        if ($request->count() > 0) {
            $typeModule = $entityManager->getRepository(TypeModule::class)->find($request->get('type_module'));
            $module = new Modules();
            if ($typeModule) {
                $module->setNom($request->get('nom'))
                    ->setFabricant($request->get('fabricant'))
                    ->setNumeroSerie($request->get('NumSerie'))
                    ->setEtat($request->get('etat_initial'))
                    ->setTypeModule($typeModule)
                    ->setDescription($request->get('description'))
                    ->setDonnees([])
                    ->setStartAt(new \DateTime());
                    
                $entityManager->persist($module);
                $entityManager->flush();
            }
        }
        return $this->redirectToRoute('Modules');
    }

    #[Route('/ajouterTypeModule', name: 'AjouterTypeModule')]
    public function ajouter_type_module(EntityManagerInterface $entityManager): Response
    {
        return $this->render('Modules/add_type_module.html.twig', [
            'nb_alerts' => $this->getNbAlerts($entityManager)
        ]);
    }

    #[Route('/enregistrerTypeModule', name: 'EnregistrerTypeModule')]
    public function enregistrer_type_module(Request $request, EntityManagerInterface $entityManager): Response
    {
        $request = $request->request;
        if ($request->count() > 0) {
            $typeModule = $entityManager->getRepository(TypeModule::class)->findByNom($request->get('nom'));
            dump($typeModule);

            $type = new TypeModule();
            if (!$typeModule) {
                $type->setNom($request->get('nom'))
                    ->setGrandeurPhysique($request->get('GPM'))
                    ->setUnite($request->get('unite'));
                    
                
                $entityManager->persist($type);
                $entityManager->flush();
            } else {
                return $this->render('Modules/add_type_module.html.twig', [
                    'error' => "Ce nom de type de module existe déja",
                    'nb_alerts' => $this->getNbAlerts($entityManager)
                ]);
            }
        }
        return $this->redirectToRoute('AjouterModule');
    }

    #[Route('/notifications', name: 'Notifications')]
    public function notifications(EntityManagerInterface $entityManager): Response
    {
        $modules = $entityManager->getRepository(Modules::class)->findAll();

        return $this->render('Modules/notifications.html.twig', [
            'modules' => $modules,
            'nb_alerts' => $this->getNbAlerts($entityManager)
        ]);
    }

    private function getNbAlerts($entityManager)
    {
        $modules = $entityManager->getRepository(Modules::class)->findAll();
        $nb_alerts = 0;
        foreach ($modules as $key => $module) {
            if ($module->getEtat() != "Fonctionnel") {
                $nb_alerts++;
            }
        }
        return $nb_alerts;
    }
}
