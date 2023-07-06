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

        if (null === $year) {
            $year = (integer)date('Y');
        }

        dump($this->pullRequestRepository->findPullRequestYears());

        return $this->render('pull_requests/index.html.twig', [
            'locale'       => $locale,
            'localeLinks'  => [
                'ru' => $this->generateURL('app_pull_requests', ['_locale' => $locale, 'year' => $year]),
                'en' => $this->generateURL('app_pull_requests', ['_locale' => $locale, 'year' => $year]),
            ],
            'year'         => $year,
            'years'        => $this->pullRequestRepository->findPullRequestYears(),
            'pullRequests' => $this->pullRequestRepository->findByYear($year),
        ]);
    }
}
