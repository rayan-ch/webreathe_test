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
    // api utilisé pour mettre a jour le graphique en temps réel
    #[Route('/api/module/{id}', name: 'api_get_module', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $module = $entityManager->getRepository(Modules::class)->find($id);   
        $data = ['historique' => $module->getDonnees(), 'description' => $module->getDescription()];

        return $this->json($data);
    }

    // api utilisé pour mettre a jour le nombre de pannes en temps réel
    #[Route('/api/modules/panne', name: 'api_get_pannes', methods: ['GET'])]
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

    // api utilisé pour mettre a jour l'etat des modules
    #[Route('/api/updatePannes', name: 'api_update_pannes', methods: ['POST'])]
    public function update_pannes(EntityManagerInterface $entityManager): JsonResponse
    {
        $modules = $entityManager->getRepository(Modules::class)->findAll();
        $etat_possible = ["Fonctionnel", "En maintenance", "En panne"];
        $data = [];

        foreach ($modules as $key => $module) {
            $new_etat = $etat_possible[rand(0, count($etat_possible)-1)];
            $module->setEtat($new_etat);
            if ($new_etat == "Fonctionnel") {
                $module->setStartAt(new \DateTime);
            }
            $entityManager->persist($module);
            $entityManager->flush();
            array_push($data, [$module->getNom()." (".$module->getNumeroSerie().")" => $new_etat]);
        }
        return $this->json($data);
    }

    // api utilisé pour generer les donnees
    #[Route('/api/genererDonnees', name: 'api_generer_donnees', methods: ['POST'])]
    public function generer_donnees(EntityManagerInterface $entityManager): JsonResponse
    {
        $modules = $entityManager->getRepository(Modules::class)->findAll();
        $return = ["message" => "les données ont été envoyer avec succée"];
        foreach ($modules as $key => $module) {
            if ($module->getEtat() == "Fonctionnel") {
                $date_actuelle = new \DateTime;
                $new_data[$date_actuelle->format('Y-m-d H:i:s')] = rand(5, 50);
                $data = $module->getDonnees();
                array_push($data, $new_data);
                $module->setDonnees($data);
                $entityManager->persist($module);
                $entityManager->flush();
            }
        }
        return $this->json($return);
    }
}
