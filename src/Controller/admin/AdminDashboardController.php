<?php

namespace App\Controller\admin;

use App\Entity\Admin;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AdminDashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/create', name: 'admin_create')]
    public function createUser(UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new Admin();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();

            $hashedPassword = $passwordHasher->hashPassword($user, $password);

            $user->setPassword($hashedPassword);

            $role = $form->get('roles')->getData();

            $user->setRoles($role);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a bien été créé !");

            return $this->redirectToRoute('admin_dashboard');
        }

        $form_view = $form->createView();

        return $this->render('admin/dashboard.html.twig', [
            'form_view' => $form_view,
        ]);
    }

    #[Route('/admin/delete/{id}', name: 'admin_delete', requirements: ['id' => '\d+'])]
    public function deleteUser(int $id, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {

        $user = $userRepository->find($id);

        if ($user->getId() === $this->getUser()->getId()) {
            $this->addFlash('success', 'Impossible de supprimer un utilisateur déjà connecté.');
            return $this->redirectToRoute('admin_dashboard');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', "L'utilisateur a été supprimé.");

        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/admin/update/{id}', name: 'admin_update', requirements: ['id' => '\d+'])]
    public function updateUser(int $id, Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();

            if (!$password) {
                $user->setPassword($user->getPassword());
            } else {
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
            }

            $role = $form->get('roles')->getData();
            $user->setRoles($role);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a été modifié !");

            return $this->redirectToRoute('admin_dashboard');
        }

        $form_view = $form->createView();

        return $this->render('admin/dashboard.html.twig', [
            'form_view' => $form_view,
            'user' => $user,
        ]);
    }


}
