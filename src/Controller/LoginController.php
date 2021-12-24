<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    /**
    * @Route("/login", name="login")
    */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
    /**
     * je crée une page racine qui porte le nom "redirect"
     * @Route("/redirect", name="redirect")
     */

    public function redirectRole()
    {

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_home');
        } elseif ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('user_home');
        } else {
            return $this->redirectToRoute('login');
        }

    }

}
