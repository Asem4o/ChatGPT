<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {
        $user = $token->getUser();
        // Check user roles or other properties to determine the redirection path
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $url = $this->router->generate('admin');
        } elseif ($user->getProfileComplete()) {
            $url = $this->router->generate('profile');
        } else {
            $url = $this->router->generate('search');
        }

        return new RedirectResponse($url);
    }
}