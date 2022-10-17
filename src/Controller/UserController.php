<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
//    #[Route('/user', name: 'app_user')]
//    public function index(): Response
//    {
//        return $this->render('user/index.html.twig', [
//            'controller_name' => 'UserController',
//        ]);
//    }

    #[Route('/users', name: 'app_users')]
    public function index(UserRepository $userRepository) :Response
    {
        $users = $userRepository->findAll();
        return $this->render('user/users.html.twig', ['users'=> $users]);
    }
    #[Route('/users/{id}', name: 'app_user')]
    public function show(User $user) :Response
    {
        return $this->render('user/user.html.twig',[
            'user'=> $user]);

    }

    #[Route('users/edit/{id}', name: 'app_edit_user')]
    public function edit(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
//        $form = $this->createForm(UserFormType::class, $user);
//        $form->handleRequest($request);
//        if($form->isSubmitted() && $form->isValid()){
//            $user = $form->getData();
//            $entityManager->persist($user);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_users');
        dd('edit');
        }

}


