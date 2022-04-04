<?php

namespace App\Controller;


use App\Entity\Media;
use App\Entity\Moto;
use App\Form\MotoType;
use App\Repository\MediaRepository;
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
        $motos = $motoRepository->findBy([], ['id' => 'DESC']);
        // return to user view
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'motos' => $motos
        ]);
    }

    #[Route ('/main/create', name: 'app_main_addMoto', methods: ['GET', 'POST'])]
    public function addMoto(MotoRepository $motoRepository, Request $request): Response
    {
        $moto = new Moto();
        $dateNow = new \DateTime('now');
        $media = new Media();
        //creation du form
        $form = $this->createForm(MotoType::class, $moto);
        $form->handleRequest($request);
        //On verifie si le formulaire est remplit et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('media')->getData();
            //md5 (uniqid) sert à crypté le nom de la photo et à le rendre unique
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            // Envoie l'image dans le dossier Upload_directory grâce à la commande pathUploadDirectory qui est dans le fichier services.yaml
            $file->move($this->getParameter('pathUploadDirectory'), $fileName);
            $media
                ->setName($fileName)
                ->setAlt('Photo moto');
            $moto->setMedia($media);
            $moto->setCreatedAt($dateNow);
            $motoRepository->add($moto);
            $this->addFlash('success', 'L\'artcile a bien été ajouté !');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('main/create.html.twig', [
            'motoForm' => $form->createView(),
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

            $this->addFlash('success', 'L\'artcile a bien été supprimé !');
        }
        return $this->redirectToRoute('app_home');
    }
}
