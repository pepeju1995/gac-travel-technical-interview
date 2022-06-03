<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // Obtencion de errores de login
        $error = $authenticationUtils->getLastAuthenticationError();

        // Ultimo usuario
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class);
        return $this->renderForm('login/index.html.twig', [
            'loginForm' => $form,
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/success', name: 'app_success')]
    public function success() : Response
    {
        return $this->render('success.html.twig');
    }
}
