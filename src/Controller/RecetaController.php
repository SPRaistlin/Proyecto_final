<?php

namespace App\Controller;

use App\Entity\Receta;
use App\Form\RecetaType;
use App\Repository\RecetaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Foto;
use App\Form\FotoType;
use App\Repository\FotoRepository;


class RecetaController extends AbstractController
{
    /**
     * @Route("/admin/receta", name="receta_index", methods="GET")
     */
    public function index(RecetaRepository $recetaRepository): Response
    {
        return $this->render('receta/index.html.twig', ['recetas' => $recetaRepository->findAll()]);
    }

    /**
     * @Route("/admin/receta/new", name="receta_new", methods="GET|POST")
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
     * @Route("/admin/receta/{id}", name="receta_show", methods="GET")
     */
    public function show(Receta $recetum): Response
    {
        return $this->render('receta/show.html.twig', ['recetum' => $recetum]);
    }

    /**
     * @Route("/admin/receta/{id}/edit", name="receta_edit", methods="GET|POST")
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
     * @Route("/admin/receta/{id}", name="receta_delete", methods="DELETE")
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
  
    /**
     * Últimas recetas
     */
    public function ultimasRecetas(RecetaRepository $recetaRepository, $max = 5): Response
    {
        return $this->render('receta/ultimas.html.twig', ['recetas' =>$recetaRepository->findBy(array(),array('id' => 'DESC'),$max)]);
    }
    
    /**
     * Listado recetas por categoría
     * @Route("/{nombre}", name="mostrar_categoria", methods="GET")
    */
    public function listadoRecetas(RecetaRepository $recetaRepository, $nombre): Response
    {
        $em = $this->getDoctrine()->getManager();
        $categoria = $em->getRepository('App:Categoria')->findOneBy(array('nombre' => $nombre));
        $catid = $categoria->getId();

        return $this->render('receta/listadofiltrado.html.twig', ['recetas' =>$recetaRepository->findBy(array('categoria' => $catid),array('categoria' => 'DESC')),
            'categoria' => $nombre
            ]);
    }

    /**
     * Listado recetas por categoría
     * @Route("/{slug}", name="mostrar_receta", methods="GET")
    */
    public function mostrarRecetas(RecetaRepository $recetaRepository, $slug): Response
    {
        $em = $this->getDoctrine()->getManager();
        $receta = $em->getRepository('App:Receta')->findOneBy(array('slug' => $slug));
        $recetaid = $receta->getId();
        $comentarios = $em->getRepository('App:Comentario')->findBy(array('receta' => $recetaid));
        $fotos = $em->getRepository('App:Foto')->findBy(array('receta_id' => $recetaid));
        return $this->render('receta/view.html.twig', ['recetas' =>$recetaRepository->findBy(array('slug' => $slug)),
            'comentarios' => $comentarios,
            'fotos' => $fotos
        ]);
    }
    /** 
     * @Route("/crear-receta", name="crearReceta", methods="GET|POST")
     */
    public function crearReceta(Request $request): Response
    {
        
        $recetum = new Receta();
        $form = $this->createForm(RecetaType::class, $recetum);
        $form->handleRequest($request);
        $ok;

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $ano = date("Y");
            $mes = date("m");
            $contador = 1;
            $slug = str_replace(" ","-", $form["nombre"]->getData());
            $recetum->setSlug($slug);

            if ($form["ruta"]->getData())
            {
                $file = $form["ruta"]->getData();
                $title = $form["nombre"]->getData();
                $ext = $file->guessExtension();
                $file_name = time()."-".str_replace(" ", "-", $title).".".$ext;
                $ano = date("Y");
                $mes = date("m");
                $path = "uploads/".$ano."/".$mes;
                $file->move($path, $file_name);
                //save data file
                $recetum->setPath($path);
                $recetum ->setRuta($file_name);
            }

            $em->persist($recetum);
            $em->flush();


            //almaceno en la tabla de imágenes los datos de las imágenes con el ID del post
            foreach ($form->get('imgs')->getData() as $img) 
            {

               
                $file = $img;
                $title = $form["nombre"]->getData();
                $ext = $img->guessExtension();
                $file_name = time().$contador."-".str_replace(" ", "-", $title).".".$ext;
                $file->getFilename();
                $path = "uploads/".$ano."/".$mes;
                $file->move($path, $file_name);
                
                //insertamos en BBDD
                $foto = new Foto();
                $em = $this->getDoctrine()->getManager();
                //$foto = $em->getRepository('App:Foto');
                $foto->setRecetaId($recetum->getId());
                $foto->setNombre($form["nombre"]->getData());
                $foto->setUrl($file_name);
                $foto->setPath($path);
                $em->persist($foto);
                $em->flush();
                $contador ++;

            }   
            $this->addFlash("success","La receta se ha creado con éxito");
            $ok = 1;
            return $this->redirectToRoute('crearReceta', array(
                'ok' => $ok
            ));
        }

        return $this->render('receta/crearReceta.html.twig', [
            'recetum' => $recetum,
            'form' => $form->createView(),
        ]);
    }
      
    /**
    * Buscador
    * @Route("/{_locale}/buscador/q={busqueda}", name="buscador", methods="GET|POST")
    * @Route({
     *  "es": "/buscador/q={busqueda}",
     *  "en": "/{_locale}/buscador/q={busqueda}"
     }, name="buscador")
    */  
    public function buscador(Request $request, $busqueda): Response
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('App:Receta');
        $query = $repository->createQueryBuilder('q')
            ->where('q.nombre LIKE :busqueda')
            ->setParameter('busqueda', '%'.$busqueda.'%')
            ->orderBy('q.created', 'DESC')
            ->getQuery();            
        $resultado = $query->getResult();

        return $this->render('receta/buscador.html.twig', [
            'recetas' => $resultado,
            'resultado' => $busqueda,
        ]); 
    }
}
