<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\PullRequest;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PullRequestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PullRequest::class;
    }

    public function createEntity(string $entityFqcn)
    {
        return (new $entityFqcn())
            ->setPlatform(PullRequest::PLATFORM_GITHUB);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Pull Request')
            ->setEntityLabelInPlural('Pull Requests')
            ->setSearchFields(['platform','repository', 'title'])
            ->setDefaultSort(['originalCreatedAt' => 'DESC']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters;
    }

    public function configureFields(string $pageName): iterable
    {
        yield ChoiceField::new('platform')
            ->setChoices([PullRequest::PLATFORM_GITHUB => PullRequest::PLATFORM_GITHUB]);
        yield TextField::new('repository');
        yield TextField::new('platformId');
        yield TextField::new('title');
        yield TextareaField::new('body')
            ->hideOnIndex();
        yield TextField::new('status');
        yield IntegerField::new('commits');
        yield IntegerField::new('additions');
        yield IntegerField::new('deletions');
        yield IntegerField::new('files');
        yield DateTimeField::new('originalCreatedAt');
    }
}
