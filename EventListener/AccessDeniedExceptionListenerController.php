<?php

declare(strict_types=1);

namespace EventListener;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Routing\Attribute\Route;

class AccessDeniedExceptionListenerController extends AbstractController
{
    #[Route('/access-denied-exception-listener')]
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof AccessDeniedException) {
            // Handle the access denied exception, e.g., throw a custom exception
            throw new \Exception('Access denied to this route');
        }
    }
}
