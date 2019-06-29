<?php

namespace App\Controller;

use App\Entity\Vinyl;
use App\Form\VinylType;
use App\Repository\VinylRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vinyl")
 */
class VinylController extends AbstractController
{
    /**
     * @Route("/", name="vinyl_index", methods={"GET"})
     */
    public function index(VinylRepository $vinylRepository): Response
    {
        return $this->render('vinyl/index.html.twig', [
            'vinyls' => $vinylRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="vinyl_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $vinyl = new Vinyl();
        $form = $this->createForm(VinylType::class, $vinyl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vinyl);
            $entityManager->flush();

            return $this->redirectToRoute('vinyl_index');
        }

        return $this->render('vinyl/new.html.twig', [
            'vinyl' => $vinyl,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vinyl_show", methods={"GET"})
     */
    public function show(Vinyl $vinyl): Response
    {
        return $this->render('vinyl/show.html.twig', [
            'vinyl' => $vinyl,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="vinyl_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Vinyl $vinyl): Response
    {
        $form = $this->createForm(VinylType::class, $vinyl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('vinyl_index', [
                'id' => $vinyl->getId(),
            ]);
        }

        return $this->render('vinyl/edit.html.twig', [
            'vinyl' => $vinyl,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vinyl_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Vinyl $vinyl): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vinyl->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vinyl);
            $entityManager->flush();
        }

        return $this->redirectToRoute('vinyl_index');
    }
}
