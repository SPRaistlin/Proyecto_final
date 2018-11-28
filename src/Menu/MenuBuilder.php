<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Builder
 *
 * @author mariano
 */
namespace App\Menu;


use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Doctrine\ORM\Entitymanager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Routing\Annotation\Route;

class MenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    
    /**
     * @var FactoryInterface
     */
    private $factory;
    
    private $em;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, Entitymanager $em)
    {
        $this->factory = $factory;
        $this->em = $em;
    }

    public function createMainMenu()
    {
        $menu = $this->factory->createItem('root');
        //$em = $this->em->getManager();
        $categorias = $this->em->getRepository('App:Categoria')->findAll();
        $menu->addChild('', array('route' => 'index'))->setExtra('icon', 'fa-home');
        foreach ($categorias as $categoria)
        {
             $menu->addChild($categoria->getNombre(),[
                'label' => $categoria->getNombre(),
                'route' => 'mostrar_categoria',
                'routeParameters' =>  array('nombre' => strtolower($categoria->getNombre()))
            ]); 

        }
        /*$menu['Categorias']->addChild('Desayunos', ['route' => 'desayunos']);
        $menu['Categorias']->addChild('Aperitivos', ['route' => 'aperitivos']);
        $menu['Categorias']->addChild('Almuerzos', ['route' => 'almuerzos']);
        $menu['Categorias']->addChild('Comidas', ['route' => 'comidas']);
        $menu['Categorias']->addChild('Meriendas', ['route' => 'meriendas']);
        $menu['Categorias']->addChild('Cenas', ['route' => 'cenas']);
        $menu['Categorias']->addChild('Postres', ['route' => 'postres']);
        $menu['Categorias']->addChild('Bebidas', ['route' => 'bebidas']);*/
        
       
        return $menu;
    }
     public function createSidebarMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem('sidebar');

        $menu->addChild('Home', ['route' => 'index']);
        // ... add more children

        return $menu;
    }
}
