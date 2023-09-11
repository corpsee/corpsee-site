<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Picture;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PictureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Picture::class;
    }

    public function createEntity(string $entityFqcn)
    {
        return (new $entityFqcn());
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Picture')
            ->setEntityLabelInPlural('Pictures')
            ->setSearchFields(['title', 'drawnAt'])
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title');
        yield TextareaField::new('description')
            ->hideOnIndex();
        yield ImageField::new('image')
            ->setUploadDir('public/images/picture/')
            ->setBasePath('/images/picture/')
            ->setUploadedFileNamePattern('[contenthash].[extension]');
        yield DateField::new('drawnAt');
        yield AssociationField::new('tags');
    }
}
