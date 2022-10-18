<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function homepage(AuthenticationUtils $authenticationUtils) :Response
    {
        if ($this->getUser()){
            return $this->redirectToRoute('app_users');
        } else {
            return $this->redirectToRoute('app_login');
        }
    }


    #[Route('/users', name: 'app_users')]
    public function index(UserRepository $userRepository): Response
    {
//        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $users = $userRepository->findAll();

        return $this->render('user/users.html.twig', ['users' => $users]);
    }


    #[Route('users/search', name: 'app_search')]
    public function search(): Response
    {
        dd('search');
    }

    #[Route('users/edit/{id}', name: 'app_edit_user')]
    public function edit(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('POST_EDIT', $user);

//        $form = $this->createForm(UserFormType::class, $user);
//        $form->handleRequest($request);
//        if($form->isSubmitted() && $form->isValid()){
//            $user = $form->getData();
//            $entityManager->persist($user);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_users');
            dd('yeah');

    }

    #[Route('/users/{id}', name: 'app_user')]
    public function show(User $user): Response
    {
        return $this->render('user/user.html.twig', [
            'user' => $user,
        ]);

    }


}


