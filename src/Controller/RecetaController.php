<?php

namespace App\Controller;

use App\Entity\Receta;
use App\Form\RecetaType;
use App\Repository\RecetaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/receta")
 */
class RecetaController extends AbstractController
{
    /**
     * @Route("/", name="receta_index", methods="GET")
     */
    public function index(RecetaRepository $recetaRepository): Response
    {
        return $this->render('receta/index.html.twig', ['recetas' => $recetaRepository->findAll()]);
    }

    /**
     * @Route("/new", name="receta_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $recetum = new Receta();
        $form = $this->createForm(RecetaType::class, $recetum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recetum);
            $em->flush();

            return $this->redirectToRoute('receta_index');
        }

        return $this->render('receta/new.html.twig', [
            'recetum' => $recetum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="receta_show", methods="GET")
     */
    public function show(Receta $recetum): Response
    {
        return $this->render('receta/show.html.twig', ['recetum' => $recetum]);
    }

    /**
     * @Route("/{id}/edit", name="receta_edit", methods="GET|POST")
     */
    public function edit(Request $request, Receta $recetum): Response
    {
        $form = $this->createForm(RecetaType::class, $recetum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('receta_index', ['id' => $recetum->getId()]);
        }

        return $this->render('receta/edit.html.twig', [
            'recetum' => $recetum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="receta_delete", methods="DELETE")
     */
    public function delete(Request $request, Receta $recetum): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recetum->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recetum);
            $em->flush();
        }

        return $this->redirectToRoute('receta_index');
    }
}
