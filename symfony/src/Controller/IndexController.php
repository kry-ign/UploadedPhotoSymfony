<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\UploadedPhotoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(UploadedPhotoType::class);
        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($this->getUser()) {
                    $entityPhotos = new Photo();
                    $entityPhotos->setFilename($form->get('filename')->getData());
                    $entityPhotos->setIsPublic($form->get('is_public')->getData());
                    $entityPhotos->setUploadedAt(new \DateTime());
                    $entityPhotos->setUser($this->getUser());

                    $em->persist($entityPhotos);
                    $em->flush();
                }
            }

        return $this->render('index/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
