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

use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserSearchCrudController extends UserCrudController
{
    private TokenStorageInterface $tokenStorage;
    private AdminUrlGenerator $adminUrlGenerator;

    private RequestStack $requestStack;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AdminUrlGenerator $adminUrlGenerator,
        RequestStack $requestStack
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->requestStack = $requestStack;
    }


    #[Route('/admin/search', name: 'app_search')]
    public function search(Request $request)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $this->denyAccessUnlessGranted('POST_EDIT', $user);
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->addFlash('success','hallo');
            $this->addFlash('error','hallo');

            $url = $this->adminUrlGenerator
//                ->setDashboard(DashboardController::class)
                ->setController(UserSearchCrudController::class)
                ->setAction('index')
                ->setAll([
                    'email' => $form->get('email')->getData(),
                    'name' => $form->get('name')->getData(),
                    'ort' => $form->get('ort')->getData(),
                    'telefon' => $form->get('telefon')->getData(),
                    'plz' => $form->get('plz')->getData(),
                ])
                ->generateUrl();

//            dd($form->get('name'))->getData();
            return $this->redirect($url);
        }

        return $this->renderForm('admin/search.html.twig', [
            'form' => $form,
        ]);
    }


    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters,

    ): QueryBuilder {

        $request = $this->requestStack->getCurrentRequest();
        $email = "%".$request->query->get('email')."%";
        $plz = "%".$request->query->get('plz')."%";
        $ort = "%".$request->query->get('ort')."%";
        $tele = "%".$request->query->get('telefon')."%";
        $name = "%".$request->query->get('name')."%";
//        dd($email);
//        dd($request->query->all());
//        dd($_GET['name']);

        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere('entity.email like :email')
            ->andWhere('entity.name like :name')
            ->andWhere('entity.ort like :ort')
            ->andWhere('entity.plz like :plz')
            ->andWhere('entity.telefon like :telefon')
            ->setParameter('email', $email)
            ->setParameter('name', $name)
            ->setParameter('ort', $ort)
            ->setParameter('telefon', $tele)
            ->setParameter('plz', $plz);

    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, 'result');
    }

//    public function configureFields(string $pageName): iterable
//    {
//        parent::configureFields($pageName);
//        yield DateField::new('created_at')
//            ->hideWhenUpdating()
//        ;
//    }


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