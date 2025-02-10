<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Form\DepotType;
use App\Repository\DepotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

 final class DepotController extends AbstractController
{
    #[Route('/depot',name: 'app_depot_index')]
    public function index(DepotRepository $depotRepository): Response
    {
        return $this->render('depot/index.html.twig', [
            'depots' => $depotRepository->findAll(),
        ]);
    }

    #[Route('/depot/new', name: 'app_depot_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $depot = new Depot();
        $form = $this->createForm(DepotType::class, $depot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($depot);
            $entityManager->flush();
            return $this->redirectToRoute('app_jeux_show');
        }

        return $this->render('depot/new.html.twig', [
            'depot' => $depot,
            'form' => $form,
        ]);
    }

    #[Route('/depot/{id}', name: 'app_depot_show')]
    public function show(Depot $depot): Response
    {
        return $this->render('depot/show.html.twig', [
            'depot' => $depot,
        ]);
    }

    #[Route('/depot/{id}/edit', name: 'app_depot_edit')]
    public function edit(Request $request, Depot $depot, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DepotType::class, $depot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_depot_index');
        }

        return $this->render('depot/edit.html.twig', [
            'depot' => $depot,
            'form' => $form,
        ]);
    }

    #[Route('/depot/delete/{id}', name: 'app_depot_delete')]
    public function delete( Depot $depot, EntityManagerInterface $entityManager): Response
    {
             $entityManager->remove($depot);
            $entityManager->flush();
 
        return $this->redirectToRoute('app_depot_index');
    }
}
