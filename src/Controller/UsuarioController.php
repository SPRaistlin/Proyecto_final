<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsuarioController extends AbstractController
{
    /**
     * @Route("/admin/usuario", name="usuario_index", methods="GET")
     */
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        return $this->render('usuario/index.html.twig', ['usuarios' => $usuarioRepository->findAll()]);
    }

    /**
     * @Route("/admin/usuario/new", name="usuario_new", methods="GET|POST")
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $password = $passwordEncoder->encodePassword($usuario, $usuario->getPlainPassword());
            $usuario->setPass($password);
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
     * @Route("/admin/usuario/{id}", name="usuario_show", methods="GET")
     */
    public function show(Usuario $usuario): Response
    {
        return $this->render('usuario/show.html.twig', ['usuario' => $usuario]);
    }

    /**
     * @Route("admin/usuario/{id}/edit", name="usuario_edit", methods="GET|POST")
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
     * @Route("admin/usuario/activar-desactivar/{id}", name="usuario_actdesact", methods="GET")
     */
    public function activarDesactivar(UsuarioRepository $usuarioRepository,$id): Response
    {

        $usuario = $usuarioRepository->findOneBy([
            'id' => $id,
        ]);
        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->getRepository("App:Usuario");
        
        if ($usuario->getIsActive()) 
        {
            $us = $usuarios->find($id);
            $us->setIsActive('0');
            $em->persist($us);
            $flush = $em->flush();
            $this->addFlash("error","El Usuario ".$us->getApodo()." ha sido desactivado");

        } else {

            $us = $usuarios->find($id);
            $us->setIsActive('1');
            $em->persist($us);
            $flush = $em->flush();
            $this->addFlash("success","Usuario ".$us->getApodo()." ha sido activado");
        }
        
        return $this->render('usuario/index.html.twig', ['usuarios' => $usuarioRepository->findAll()]);
    }

    /**
     * @Route("admin/usuario/{id}", name="usuario_delete", methods="DELETE")
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
