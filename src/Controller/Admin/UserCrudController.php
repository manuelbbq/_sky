<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\SearchFormType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
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

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return parent::configureFilters($filters)
            ->add('name');
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


    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPaginatorPageSize(5);
    }
}
