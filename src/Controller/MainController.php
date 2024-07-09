<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PictureRepository;
use App\Repository\ProjectRepository;
use App\Repository\PullRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Helper\FileSize;

class MainController extends AbstractController
{
    public function __construct(
        private readonly PullRequestRepository $pullRequestRepository,
        private readonly ProjectRepository $projectRepository,
        private readonly PictureRepository $pictureRepository,
    ) {
    }

    #[Route('/', name: 'app_main_no_locale')]
    public function indexNoLocale(Request $request): Response
    {
        $locale = $request->headers->get('accept-language');
        $locale = \strtolower(\str_split($locale, 2)[0]);

        return $this->redirectToRoute('app_main', ['_locale' => $locale]);
    }

    #[Route('/{_locale<%app.supported_locales%>}', name: 'app_main')]
    public function index(Request $request): Response
    {
        $locale = $request->getLocale();

        return $this->render('main/index.html.twig', [
            'locale'       => $locale,
            'localeLinks'  => [
                'ru' => $this->generateURL('app_main', ['_locale' => 'ru']),
                'en' => $this->generateURL('app_main', ['_locale' => 'en']),
            ],
            'total'   => [
                'time'   => \round((\microtime(true) - BENCHMARK_START_TIME), 5),
                'memory' => FileSize::humanize(\memory_get_usage() - BENCHMARK_START_MEMORY),
            ],
            'pullRequests' => $this->pullRequestRepository->findLast(5),
            'projects'     => $this->projectRepository->findAllFiltered(),
            'pictures'     => $this->pictureRepository->findLast(8),
        ]);
    }
}
