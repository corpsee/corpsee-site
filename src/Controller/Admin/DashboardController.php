<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Picture;
use App\Entity\Project;
use App\Entity\PullRequest;
use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(ProjectCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Corpsee Site')
            //->disableDarkMode()
            ;
    }

    public function configureMenuItems(): iterable
    {
        //yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'app_main');
        yield MenuItem::linkToCrud('Projects', 'fas fa-star', Project::class);
        yield MenuItem::linkToCrud('Pictures', 'fas fa-image', Picture::class);
        yield MenuItem::linkToCrud('PullRequests', 'fas fa-code-pull-request', PullRequest::class);
        yield MenuItem::linkToCrud('Tags', 'fas fa-tag', Tag::class);
    }
}
