<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Form\CategoriaType;
use App\Repository\CategoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class CategoriaController extends AbstractController
{
    /**
     * @Route("/admin/categoria", name="categoria_index", methods="GET")
     */
    public function index(CategoriaRepository $categoriaRepository): Response
    {
        return $this->render('categoria/index.html.twig', ['categorias' => $categoriaRepository->findAll()]);
    }

    /**
     * @Route("/admin/categoria/new", name="categoria_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $categorium = new Categoria();
        $form = $this->createForm(CategoriaType::class, $categorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorium);
            $em->flush();

            return $this->redirectToRoute('categoria_index');
        }

        return $this->render('categoria/new.html.twig', [
            'categorium' => $categorium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/categoria/{id}", name="categoria_show", methods="GET")
     */
    public function show(Categoria $categorium): Response
    {
        return $this->render('categoria/show.html.twig', ['categorium' => $categorium]);
    }

    /**
     * @Route("/admin/categoria/{id}/edit", name="categoria_edit", methods="GET|POST")
     */
    public function edit(Request $request, Categoria $categorium): Response
    {
        $form = $this->createForm(CategoriaType::class, $categorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categoria_index', ['id' => $categorium->getId()]);
        }

        return $this->render('categoria/edit.html.twig', [
            'categorium' => $categorium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/categoria/{id}", name="categoria_delete", methods="DELETE")
     */
    public function delete(Request $request, Categoria $categorium): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorium->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorium);
            $em->flush();
        }

        return $this->redirectToRoute('categoria_index');
    }
    
    /**
     * Listado recetas por categoría
     * @Route("/admin/recetas/{nombre}", name="menu", methods="GET")
    */
    public function menuCategorias(CategoriaRepository $recetaRepository, $nombre): Response
    {

        return $this->render('receta/listadofiltrado.html.twig', ['recetas' =>$recetaRepository->findBy(array('categoria' => $catid),array('categoria' => 'DESC')),
            'categoria' => $nombre
            ]);
    }
}