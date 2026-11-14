<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\UserAddSuccessEvent;
use App\Event\UserDeleteSuccessEvent;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        EventDispatcherInterface $dispatcher
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 1. Gestion du rôle
            $selectedRole = $form->get('roles')->getData();
            if ($selectedRole) {
                $user->setRoles([$selectedRole]);
            }
            // 2. Hashage du mot de passe
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }
            // 3. Sauvegarde en base de données
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur ajouté avec succès');
            // 4. Dispatch de l'événement de création (avec l'e-mail en string)
            $userEmail = (string) $user->getEmail();
            $userEvent = new UserAddSuccessEvent($userEmail);
            $dispatcher->dispatch($userEvent, 'userAdd.success');
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(?User $user): Response
    {
        if (!$user) {
            throw $this->createNotFoundException("Cet utilisateur n'existe pas.");
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        ?User $user,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $encoder
    ): Response {
        if (!$user) {
            throw $this->createNotFoundException("Cet utilisateur n'existe pas.");
        }
        $form = $this->createForm(UserType::class, $user);
        if (!$request->isMethod('POST')) {
            $currentRoles = $user->getRoles();
            $form->get('roles')->setData($currentRoles[0] ?? 'ROLE_USER');
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $selectedRole = $form->get('roles')->getData();
            if ($selectedRole) {
                $user->setRoles([$selectedRole]);
            }
            $password2 = $form->get('password2')->getData();
            if (!empty($password2)) {
                $encoded = $encoder->hashPassword($user, $password2);
                $user->setPassword($encoded);
            }
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur modifié avec succès !');
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(
        int $id,
        EventDispatcherInterface $dispatcher,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): Response {
        // Recherche manuelle pour éviter les erreurs d'EntityValueResolver si l'ID est introuvable
        $user = $userRepository->find($id);
        if (!$user) {
            $this->addFlash('error', "Cet utilisateur n'existe plus ou a déjà été supprimé.");
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
        // Vérification du token CSRF et suppression
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->getPayload()->getString('_token'))) {
            // Sauvegarde de l'e-mail avant la suppression effective de l'entité
            $userEmail = (string) $user->getEmail();
            $entityManager->remove($user);
            $entityManager->flush();
            // AJOUTEZ CETTE LIGNE TEMPORAIREMENT :
            // dd('Suppression OK, email à envoyer : ' . $userEmail);
            $this->addFlash('success', 'Utilisateur supprimé avec succès !');
            // Dispatch de l'événement de suppression (capturé par le Listener)
            $userEvent = new UserDeleteSuccessEvent($userEmail);
            $dispatcher->dispatch($userEvent, 'userDelete.success');
        }
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
