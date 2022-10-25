<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\SearchFormType;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use http\Env\Request;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserSearchCrudController extends UserCrudController
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


    #[Route('/admin/search', name: 'app_search')]
    public function search(Request $request)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $this->denyAccessUnlessGranted('POST_EDIT', $user);
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            return dd($form);
        }

        return $this->renderForm('admin/search.html.twig', [
            'form' => $form,
        ]);
    }


    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere('entity.email = :user')
            ->setParameter('user', 'user@test.de');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, 'Search');
    }

//    public function createNewForm(
//        EntityDto $entityDto,
//        KeyValueStore $formOptions,
//        AdminContext $context
//    ): FormInterface
//    {
//        return $this->createForm(SearchFormType::class);
//
//    }


}