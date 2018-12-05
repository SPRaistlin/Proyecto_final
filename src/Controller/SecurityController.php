<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $utils)
    {
       
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        $this->get("request")->getSession()->invalidate();
        $this->get("security.context")->setToken(null);
        $response = new Response();
        $response->headers->clearCookie('REMEMBERME');
        $response->send();
    }
}
