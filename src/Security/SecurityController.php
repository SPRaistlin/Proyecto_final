<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Security;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
#use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


/**
 * Description of SecurityController
 *
 * @author mariano
 */
class SecurityController extends AbstractController {
    /**
    * @Route("/login", name="login")
    */
   public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
   {
       // get the login error if there is one
       $error = $authenticationUtils->getLastAuthenticationError();
       
       // Recupera el último nombre de usuario introducido
       $lastUsername = $authenticationUtils->getLastUsername();

       // Renderiza la plantilla, enviándole, si existen, el último error y nombre de usuario
       return $this->render('security/login.html.twig', array(
           'last_username' => $lastUsername,
           'error'         => $error,
       ));
   }
    /**
    * @Route("/logout", name="logout")
    */
    public function logoutAction(Request $request)
    {
        // UNREACHABLE CODE
    }
}