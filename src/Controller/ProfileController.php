<?php

declare(strict_types=1);

namespace App\Controller;


use App\Entity\Note;
use App\Entity\User;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function profile(NotificationRepository $notificationRepository,UserInterface $user, Security $security, EntityManagerInterface $entityManager,AuthenticationUtils $authenticationUtils): Response
    {
        $userNotes = $security->getUser();
        $noteRepository = $entityManager->getRepository(Note::class);
        $userNotification = $this->getUser();
        $notifications = $notificationRepository->findBy(['user' => $userNotification, 'isRead' => false]);
        $notes = $noteRepository->findBy(['user' => $userNotes->getId()]);
        $created = null;


        if (!empty($notes)) {
            $created = $notes[0]->getCreated(); // Assuming 'getCreated()' method exists in Note entity
            $created = $created->format('Y-m-d H:i:s'); // Format the DateTime object into a string
        }

        if ($user && in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return $this->redirectToRoute('admin_profile');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'notes' =>$notes,
            'created' => $created, // Pass the 'created' date to the template
            'notifications' => $notifications,
        ]);
    }



}
