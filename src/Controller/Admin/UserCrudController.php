<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/** 
 * @method User getUser()
 */

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHash,
        private EntityRepository $entityRepository
        )
    {
       
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $userId = $this->getUser()->getId();

        $response = $this->entityRepository->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere('entity.id != :userId')->setParameter('userId', $userId );

        return $response;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('username');
        yield TextField::new('password')
        ->setFormType(PasswordType::class)
        ->onlyOnForms();

        yield ChoiceField::new('roles')
        ->allowMultipleChoices()
        ->renderAsBadges([
            'ROLE_ADMIN' => 'success',
            'ROLE_AUTHOR' => 'warning'
        ])
        ->setChoices([
            'Administrateur' => 'ROLE_ADMIN',
            'Autheur' => 'ROLE_AUTHOR'
        ]);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var User $user */
        $user = $entityInstance;

        $plainPassword = $user->getPassword();
        $hashPassword = $this->passwordHash->hashPassword($user, $plainPassword);

        $user->setPassword($hashPassword);

        parent::persistEntity($entityManager, $entityInstance);
    }
}
