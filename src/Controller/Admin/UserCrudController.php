<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\SearchFormType;
use App\Form\UserUpdateFormType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
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
use mysql_xdevapi\Exception;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $userPasswordHasher;


    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }


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
        yield DateField::new('created_at')
            ->hideWhenUpdating()
            ->hideWhenCreating();
        $roles = ['ROLE_USER', 'ROLE_ADMIN'];
        yield ChoiceField::new('roles')
            ->setChoices(array_combine($roles, $roles))
            ->allowMultipleChoices()
            ->renderExpanded()
            ->setPermission('POST_EDIT');
        yield TextField::new('password', 'New password')
            ->onlyWhenUpdating()
        ->setRequired(false)
        ->setFormType(RepeatedType::class)
        ->setFormTypeOptions([
            'type'=> PasswordType::class,
            'first_options'=>['label' => 'New password'],
            'second_options'=>['label' => 'Repeat password'],
            'error_bubbling'=> true,
            'invalid_message'=>'The passwordfields do not match',
        ]);


    }


    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPaginatorPageSize(5);
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->setPermission(Action::INDEX, 'ROLE_USER');
    }

//    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
//    {
//            if ($entityInstance instanceof User){
//                throw new Exception('kein User');
//            }
//            dd($entityInstance);
//
//            $entityInstance->setPassword(
//                $this->userPasswordHasher->hashPassword(
//                    $entityInstance,
//                    $entityInstance->getPlainpassword()
//                )
//            );
//
//            $entityManager->persist($entityInstance);
//            $entityManager->flush();
//
//    }

//    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
//    {

////
////

//
//        $entityManager->persist($entityInstance);
//        $entityManager->flush();
//    }
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!($entityInstance instanceof User)) {
            dd('ok');
        }

        $plainpw = $entityInstance->getPassword();
//        dd($plainpw);

        $entityInstance->setPassword(
            $this->userPasswordHasher->hashPassword(
                $entityInstance,
                $plainpw
            )
        );

        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }


}
