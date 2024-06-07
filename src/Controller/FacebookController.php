<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\FacebookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class FacebookController extends AbstractController
{
    #[Route('/facebook', name: 'facebook')]
    public function loginFacebook(FacebookService $fbService): RedirectResponse
    {
        $loginUrl = $fbService->getFacebookLoginUrl($this->generateUrl('facebook_check', [], 0));
        return $this->redirect($loginUrl);
    }

    // src/Controller/FacebookController.php

    #[Route('/login/facebook/check', name: 'facebook_check')]
    public function facebookCheck(Request $request, FacebookService $fbService): Response
    {
        $fbUser = $fbService->getFacebookUser($request);
        var_dump($fbUser);
        if (!$fbUser) {
            $this->addFlash('error', 'Facebook login failed.');
            return $this->redirectToRoute('login');
        }

        // Here, implement your logic to register or log in the user in your system
        // For example, check if the user exists in your database, if not, register them
        // Then log them into your application

        $this->addFlash('success', 'Logged in successfully!');
        return $this->redirectToRoute('home'); // Redirect to a route after successful login
    }


}
