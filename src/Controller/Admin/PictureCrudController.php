<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Picture;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
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
            ->setSearchFields(['title'])
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters;
    }

    public function configureFields(string $pageName): iterable
    {
        $imageField = ImageField::new('image')
            ->setUploadDir('public/files/images/picture/')
            ->setBasePath('/files/images/picture/');
            //->setUploadedFileNamePattern('[contenthash].[extension]');
        $imageMinField = ImageField::new('imageMin')
            ->setUploadDir('public/files/images/picture_min/')
            ->setBasePath('/files/images/picture_min/');
            //->setUploadedFileNamePattern('[contenthash].[extension]');
        $imageGrayField = ImageField::new('imageGray')
            ->setUploadDir('public/files/images/picture_gray/')
            ->setBasePath('/files/images/picture_gray/');
            //->setUploadedFileNamePattern('[contenthash].[extension]');

        if ($pageName !== 'new') {
            $imageField->setRequired(false);
            $imageMinField->setRequired(false);
            $imageGrayField->setRequired(false);
        }

        yield TextField::new('title');
        yield TextareaField::new('description')
            ->hideOnIndex();
        yield $imageField;
        yield $imageMinField;
        yield $imageGrayField;
        yield DateField::new('drawnAt');
        yield DateTimeField::new('deletedAt');
        yield AssociationField::new('tags');
    }
}
