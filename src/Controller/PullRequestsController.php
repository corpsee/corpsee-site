<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PullRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PullRequestsController extends AbstractController
{
    public function __construct(
        private readonly PullRequestRepository $pullRequestRepository,
    ) {
    }

    #[Route('/{_locale<%app.supported_locales%>}/pull-requests/{year}', name: 'app_pull_requests')]
    public function index(Request $request, ?int $year = null): Response
    {
        $locale = $request->getLocale();

        $pullRequestYears = $this->pullRequestRepository->findPullRequestYears();

        if (null === $year) {
            $year = $pullRequestYears[0]['year'];
        }

        return $this->render('pull_requests/index.html.twig', [
            'locale'       => $locale,
            'localeLinks'  => [
                'ru' => $this->generateURL('app_main', ['_locale' => 'ru']),
                'en' => $this->generateURL('app_main', ['_locale' => 'en']),
            ],
            'year'         => $year,
            'years'        => $pullRequestYears,
            'pullRequests' => $this->pullRequestRepository->findByYear($year),
        ]);
    }
}
