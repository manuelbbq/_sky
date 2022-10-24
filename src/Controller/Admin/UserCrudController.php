<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\SearchFormType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserCrudController extends AbstractCrudController
{
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator= $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    #[Route('/admin/search', name: 'app_search')]
    public function search(Request $request)
    {
        $form =$this->createForm(SearchFormType::class);
        if ($form->isSubmitted()){
            $form->handleRequest($request);

            $url = $this->adminUrlGenerator
                ->setController(self::class)
                ->setAction(Crud::PAGE_INDEX)
                ->set('filters[email][value]','user@test.de')
                ->set('filters[email][comparison', '=')
                ->generateUrl();

            return $this->redirect($url);
        }



        return $this->renderForm('admin/search.html.twig',[
            'form'=> $form,
        ]);
    }
    #[Route('admin/result', name: 'app_result')]
    public function result(Request $request)
    {


    }





    public function configureFields(string $pageName): iterable
    {

        yield IdField::new('id')
        ->onlyOnIndex();
        yield TextField::new('name')
        ->setTemplatePath('admin/field/name.html.twig');
        yield EmailField::new('email');
        yield NumberField::new('plz');
        yield TextField::new('ort');
        yield TextField::new('telefon');
        yield BooleanField::new('is_verified');
        yield DateField::new('created_at');
        $roles = ['ROLE_USER','ROLE_ADMIN'];
        yield ChoiceField::new('roles')
            ->setChoices(array_combine($roles,$roles))
            ->allowMultipleChoices()
            ->renderExpanded();


    }

}
