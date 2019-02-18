<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SecurityController extends Controller
{
    /**
     * @Route("/{_locale}/login", name="login", defaults={"_locale"="es"}))
     * @Route({
     *  "es": "/{_locale}/login",
     *  "en": "/{_locale}/login"
     }, name="login")
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
     * @Route("/logout", name="logout", defaults={"_locale"="es"})
     * @Route({
     *  "es": "/{_locale}/logout",
     *  "en": "/{_locale}/logout"
     }, name="logout")
     */
    public function logout(Request $request, TokenStorageInterface $tokenStorage)
    {
        $session = $request->getSession();
        $session->invalidate();
        $tokenStorage->setToken(null);
        $response = new Response();
        $response->headers->clearCookie('REMEMBERME');
        $response->send();
        return $this->render('security/login.html.twig', [
            'error' => null,
            'last_username' => null
        ]);
    }
}