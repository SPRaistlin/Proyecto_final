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
use Symfony\Component\HttpFoundation\RequestStack;

class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('', array('route' => 'index'))->setExtra('icon', 'fa-home');
        $menu->addChild('Categorias', array('#'));
        $menu['Categorias']->addChild('Desayunos', ['route' => 'desayunos']);
        $menu['Categorias']->addChild('Aperitivos', ['route' => 'aperitivos']);
        $menu['Categorias']->addChild('Almuerzos', ['route' => 'almuerzos']);
        $menu['Categorias']->addChild('Comidas', ['route' => 'comidas']);
        $menu['Categorias']->addChild('Meriendas', ['route' => 'meriendas']);
        $menu['Categorias']->addChild('Cenas', ['route' => 'cenas']);
        $menu['Categorias']->addChild('Postres', ['route' => 'postres']);
        $menu['Categorias']->addChild('Bebidas', ['route' => 'bebidas']);
        $menu->addChild('Usuarios', ['route' => 'usuario']);
        // ... add more children

        return $menu;
    }
     public function createSidebarMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem('sidebar');

        $menu->addChild('Home', ['route' => 'index']);
        // ... add more children

        return $menu;
    }
    public function createLoginMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->creteItem('login');
        
        $menu->addChild('Iniciar Sesión', ['route' => '']);
        
        return $menu;
    }
    public function createRegisterMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->creteItem('register');
        
        $menu->addChild('Nuevo Usuario', ['route' => 'new']);
        
        return $menu;
    }
}