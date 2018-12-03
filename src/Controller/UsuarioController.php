<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UsuarioController extends AbstractController
{
    /**
     * @Route("/usuario", name="usuario_index", methods="GET")
     */
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        return $this->render('usuario/index.html.twig', ['usuarios' => $usuarioRepository->findAll()]);
    }

    /**
     * @Route("/new", name="usuario_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();

            return $this->redirectToRoute('usuario_index');
        }

        return $this->render('usuario/new.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="usuario_show", methods="GET")
     */
    public function show(Usuario $usuario): Response
    {
        return $this->render('usuario/show.html.twig', ['usuario' => $usuario]);
    }

    /**
     * @Route("/{id}/edit", name="usuario_edit", methods="GET|POST")
     */
    public function edit(Request $request, Usuario $usuario): Response
    {
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('usuario_index', ['id' => $usuario->getId()]);
        }

        return $this->render('usuario/edit.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="usuario_delete", methods="DELETE")
     */
    public function delete(Request $request, Usuario $usuario): Response
    {
        if ($this->isCsrfTokenValid('delete'.$usuario->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($usuario);
            $em->flush();
        }

        return $this->redirectToRoute('usuario_index');
    }
    
    /**
     * @Route("/perfil/{usuario}", name="perfil", methods="GET|POST")
     */
    public function perfilUsuario(Request $request,Usuario $usuario): Response {
        
        
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('perfil', ['apodo' => $usuario->getApodo(), 'change' => 'ok']);
        } 
        else if ($form->isEmpty() && $form->isDisabled())
        {
            return $this->redirectToRoute('perfil', ['apodo' => $usuario->getApodo(), 'change' => 'ko']);
        }        

        return $this->render('usuario/perfil.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
    }

}
