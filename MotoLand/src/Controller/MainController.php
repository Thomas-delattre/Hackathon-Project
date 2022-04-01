<?php

namespace App\Controller;

use App\Entity\Moto;
use App\Form\MotoType;
use App\Repository\MotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(MotoRepository $motoRepository): Response
    {
        //recove the data in the data base with method findBy
        $motos = $motoRepository->findBy([], ['createdAt' => 'DESC']);
        // return to user view
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'motos' => $motos
        ]);
    }

    #[Route ('/main/create', name: 'app_main_addMoto', methods: ['GET', 'POST'])]
    public function addMoto(MotoRepository $motoRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $moto = new Moto();
        $dateNow = new \DateTime('now');
        //creation form
        $form = $this->createForm(MotoType::class, $moto);
        $form->handleRequest($request);
        //form check
        if ($form->isSubmitted() && $form->isValid()) {
            $moto->setCreatedAt($dateNow);
            $motoRepository->add($moto);
            $entityManager->persist($moto);
            $entityManager->flush();
            $this->addFlash('succes', 'L\'artcile a bien été ajouté !');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('main/create.html.twig', [
            'motoForm' => $form->createView()
        ]);
    }

    #[Route('/main/{id}', name: 'app_main_show', methods: ['GET'])]
    public function show(Moto $moto): Response
    {
        return $this->render('main/moto.html.twig', [
            'moto' => $moto
        ]);
    }
    #[Route ('/main/edit/{id}', name: 'app_moto_edit', methods: ['GET', 'POST'])]
    public function edit( Request $request,  Moto $moto, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(MotoType::class, $moto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $moto = $form->getData();
            $entityManager->persist($moto);
            $entityManager->flush();
             return $this->redirectToRoute('app_home');
        }
            return $this->renderForm('main/edit.html.twig', [
                'moto' => $moto,
                'motoForm' => $form
            ]);
    }
    #[Route('/main/delete/{id}', name: 'app_moto_delete', methods: ['POST'])]
    public function deleteMoto(Request $request, Moto $moto, MotoRepository $motoRepository): Response
    {
        if ($this->isCsrfTokenValid('moto_delete'.$moto->getId(), $request->request->get('_token'))){
            $motoRepository->remove($moto);

            $this->addFlash('succes', 'L\'artcile a bien été supprimé !');
        }
        return $this->redirectToRoute('app_home');
    }
}
