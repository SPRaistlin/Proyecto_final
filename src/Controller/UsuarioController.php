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
use Doctrine\ORM\Query;

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
    public function perfilUsuario(UsuarioRepository $usuarioRepository, Request $request): Response {

        return $this->render('usuario/perfil.html.twig', [
            'usuario' => $request->get('usuario')
        ]);
    }

    public function recetasUsuario(Usuario $usuario, $userid, $max)
    {
        $em = $this->getDoctrine()->getManager();
        $recetas = $em->getRepository('App:Receta');


        return $this->render('usuario/listadoRecetas.html.twig', [
            'recetas' => $recetas->findBy( array(
                'usuario' =>$userid
            ),array(
                'created' => 'DESC',
            ),
            $max)
        ]);

    }

    /**
     * @Route("/perfil/{usuario}/mis-recetas", name="perfil", methods="GET|POST")
     */
    public function misRecetas(UsuarioRepository $usuarioRepository, Request $request): Response
    {
        //$validator = md5("muchomasquerecetas");
        //if ($validator == $request->get('validate')) 
        //{         
            $em = $this->getDoctrine()->getManager();
            $recetas = $em->getRepository('App:Receta');
            $usuario = $usuarioRepository->findOneBy([
                'apodo' => $request->get('usuario'),
            ]);

            return $this->render('usuario/todasRecetasUsuario.html.twig', [
                'recetas' => $recetas->findBy( array(
                    'usuario' =>$usuario->getId()
                ),array(
                    'created' => 'DESC',
                ))
            ]);   
        /*}
        else {
            return  $this->render('usuario/todasRecetasUsuario.html.twig', [
                'error' => 1
            ]);  
        }*/

    }

    /**
     * @Route("/perfil/{usuario}/mis-recetas", name="perfil", methods="GET|POST")
     */
    public function misComentarios(UsuarioRepository $usuarioRepository, Request $request): Response
    {
        //$validator = md5("muchomasquerecetas");
        //if ($validator == $request->get('validate')) 
        //{
        $usuario = $usuarioRepository->findOneBy(array('apodo' => $request->get('usuario')));
        $usuarioid = $usuario->getId();
        $em = $this->getDoctrine()->getManager();
        $query = $em
            ->createQuery("
                SELECT DISTINCT r.nombre, r.slug, ct.nombre AS categoria, c.comentario, c.created 
                FROM App:Receta r 
                INNER JOIN App:Comentario c 
                WITH c.receta = r.id 
                INNER JOIN App:Categoria ct
                WITH ct.id = r.categoria
                WHERE c.usuario = :parameter
            ")
            ->setParameter('parameter', $usuarioid);
        //$usuario = $usuarioRepository->findOneBy(array('apodo' => $request->get('usuario')));
        //$usuarioid = $usuario->getId();
        //$comentarios = $em->getRepository('App:Comentario')->findBy(array('usuario' => $usuarioid));
        //$recetas = $em->getRepository('App:Receta')->findBy(array('usuario' => $usuarioid));
        //$recetaid = $recetas->getId();
        return $this->render('usuario/todosComentariosUsuario.html.twig', array('comentarios' => $query->getResult())); 
        /*}
        else {
            return  $this->render('usuario/todasRecetasUsuario.html.twig', [
                'error' => 1
            ]);  
        }*/

    }

    /**
     * @Route("/perfil/{usuario}/editar-perfil", name="editarPerfil", methods="GET|POST")
     */
    public function editarPerfil(Request $request, UsuarioRepository $usuarioRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        
        $usuario = $usuarioRepository->findOneBy(array('apodo' => $request->get('usuario')));
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->remove('apodo');
        $form->remove('isactive');
        $form->handleRequest($request);   

        if ($form->isSubmitted() && $form->isValid()) {  

            if ($form['cambiarContra']->getData()) {

                $password = $passwordEncoder->encodePassword($usuario, $form['plainPassword']->getData());
                $usuario->setPass($password);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('editarPerfil', ['usuario' => $usuario->getApodo()]);

            } else {

                $actual = $usuario->getPass();
                $nueva = $form['plainPassword']->getData();

                if (password_verify($nueva, $actual))
                {
                    $usuario->setPass($actual);
                    $this->getDoctrine()->getManager()->flush();
                    return $this->redirectToRoute('editarPerfil', ['usuario' => $usuario->getApodo()]);

                } else {

                    print_r('La contraseña no es correcta');

                }
            }             
           
        }
        return $this->render('usuario/informacionCuenta.html.twig', [
            'usuario' => $usuario->getApodo(),
            'form' => $form->createView(),
        ]);
    }

}
