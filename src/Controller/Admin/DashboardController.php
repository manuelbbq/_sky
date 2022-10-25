<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\SearchFormType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;



class DashboardController extends AbstractDashboardController
{




    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

//        return $this->render('admin/index.html.twig');

    }

//    #[Route('/admin/profile', name: 'app_profile')]
//    public function profile()
//    {
//        return $this->render('user/profile.html.twig');
//    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('User');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('My Data', 'fas fa-tags', User::class)
            ->setController(UserCrudController::class)
            ->setAction('detail')
            ->setEntityId($this->getUser()->getId())
        ;

        yield MenuItem::linkToCrud('List Person', 'fa fa-question-circle', User::class)
            ->setController(UserCrudController::class)
        ;


        yield MenuItem::linkToRoute('Search', 'fa fa-question-circle','app_search' )


        ;




        yield MenuItem::linkToLogout('logout', 'fas fa-home', 'app_search');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::DELETE);
    }


}
