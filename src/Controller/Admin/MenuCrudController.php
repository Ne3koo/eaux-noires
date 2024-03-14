<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuCrudController extends AbstractCrudController
{
    const MENU_PAGES = 0;
    const MENU_ARTICLES = 1;
    const MENU_LINKS = 2;
    const MENU_CATEGORIES = 3;

    public function __construct(
        private MenuRepository $menuRepository,
        private RequestStack   $requestStack
    ) {
        
    }

    public static function getEntityFqcn(): string
    {
        return Menu::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        
        $subMenuIndex = $this->getSubMenuIndex();
        //dd($subMenuIndex);
        $entityLabelInSingular = 'un menu';

        $entityLabelInPlural = match ($subMenuIndex) {
            self::MENU_ARTICLES => 'Articles',
            self::MENU_CATEGORIES => 'Catégories',
            self::MENU_LINKS => 'Liens personnalisés',
            self::MENU_PAGES => 'Pages',
        };

        return $crud
            ->setEntityLabelInSingular($entityLabelInSingular)
            ->setEntityLabelInPlural($entityLabelInPlural);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $subMenuIndex = $this->getSubMenuIndex();

        return $this->menuRepository->getIndexQueryBuilder($this->getFieldNameFromSubMenuIndex($subMenuIndex));
    }

    public function configureFields(string $pageName): iterable
    {
        $subMenuIndex = $this->getSubMenuIndex();

        yield TextField::new('name', 'Titre de la navigation');

        yield NumberField::new('menuOrder', 'Ordre');

        yield $this->getFieldFromSubMenuIndex($subMenuIndex)
            ->setRequired(true);

        yield BooleanField::new('isVisible', 'Visible');

        yield AssociationField::new('subMenus', 'Sous-éléments');
    }

    private function getFieldNameFromSubMenuIndex(int $subMenuIndex): string
    {
        //dd($subMenuIndex);
        return match ($subMenuIndex) {
            self::MENU_ARTICLES => 'article',
            self::MENU_CATEGORIES => 'category',
            self::MENU_LINKS => 'link',
            self::MENU_PAGES => 'page',
            default => 'article'
        };
    }

    private function getFieldFromSubMenuIndex(int $subMenuIndex): AssociationField|TextField
    {
        $fieldName = $this->getFieldNameFromSubMenuIndex($subMenuIndex);

        return ($fieldName === 'link') ? TextField::new($fieldName) : AssociationField::new($fieldName);
    }

    private function getSubMenuIndex(): int
    {
        $query = $this->requestStack->getMainRequest()->query;
        //dd($query->get('subMenuIndex'));
        if ($referer = $query->get('referrer')) {
            parse_str(parse_url($referer, PHP_URL_QUERY), $query);

            return $query['subMenuIndex'] ?? 0;
        }

        return $query->getInt('subMenuIndex');
    }
}