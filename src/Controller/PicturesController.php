<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Tag;
use App\Helper\FileSize;
use App\Repository\PictureRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PicturesController extends AbstractController
{
    public function __construct(
        private readonly PictureRepository $pictureRepository,
        private readonly TagRepository $tagRepository,
    ) {
    }

    #[Route('{_locale<%app.supported_locales%>}/pictures', name: 'app_pictures')]
    public function index(Request $request): Response
    {
        $locale = $request->getLocale();
        $tags   = $this->tagRepository->findAllFiltered();

        \shuffle($tags);

        $years = $this->pictureRepository->findPicturesYears();
        $picturesByYears = [];
        foreach ($years as $year) {
            $yearValue = (int)$year['year'];
            $picturesByYears[$yearValue] = $this->pictureRepository->findByYear($yearValue);
        }

        return $this->render('pictures/index.html.twig', [
            'locale'       => $locale,
            'localeLinks'  => [
                'ru' => $this->generateURL('app_main', ['_locale' => 'ru']),
                'en' => $this->generateURL('app_main', ['_locale' => 'en']),
            ],
            'total'   => [
                'time'   => \round((\microtime(true) - BENCHMARK_START_TIME), 5),
                'memory' => FileSize::humanize(\memory_get_usage() - BENCHMARK_START_MEMORY),
            ],
            'pictures'     => $picturesByYears,
            'tags'         => $tags,
            'tagClasses'   => [
                0  => 'tag0',
                1  => 'tag1',
                2  => 'tag1',
                3  => 'tag2',
                4  => 'tag2',
                5  => 'tag3',
                6  => 'tag3',
                7  => 'tag4',
                8  => 'tag4',
                9  => 'tag5',
                10 => 'tag5',
            ],
        ]);
    }

    #[Route('{_locale<%app.supported_locales%>}/pictures/tags', name: 'app_pictures_tags')]
    public function tags(Request $request): Response
    {
        $locale     = $request->getLocale();
        $tags       = $this->tagRepository->findAllOrdered();
        $tagOrdered = $tags;

        \shuffle($tags);

        return $this->render('pictures/tags.html.twig', [
            'locale'       => $locale,
            'localeLinks'  => [
                'ru' => $this->generateURL('app_main', ['_locale' => 'ru']),
                'en' => $this->generateURL('app_main', ['_locale' => 'en']),
            ],
            'total'   => [
                'time'   => \round((\microtime(true) - BENCHMARK_START_TIME), 5),
                'memory' => FileSize::humanize(\memory_get_usage() - BENCHMARK_START_MEMORY),
            ],
            'tags'         => $tags,
            'tagsOrdered'  => $tagOrdered,
            'tagClasses'   => [
                0  => 'tag0',
                1  => 'tag1',
                2  => 'tag1',
                3  => 'tag2',
                4  => 'tag2',
                5  => 'tag3',
                6  => 'tag3',
                7  => 'tag4',
                8  => 'tag4',
                9  => 'tag5',
                10 => 'tag5',
            ],
        ]);
    }

    #[Route('{_locale<%app.supported_locales%>}/pictures/tag/{tag}', name: 'app_pictures_tag')]
    public function tag(Request $request, string $tag): Response
    {
        $locale = $request->getLocale();
        /** @var Tag $tagEntity */
        $tagEntity = $this->tagRepository->findOneBy(['name' => $tag]);
        if (!$tagEntity) {
            throw new NotFoundHttpException(\sprintf('Tag "%s" doesn\'t exist!', $tag));
        }

        return $this->render('pictures/tag.html.twig', [
            'locale'       => $locale,
            'localeLinks'  => [
                'ru' => $this->generateURL('app_main', ['_locale' => 'ru']),
                'en' => $this->generateURL('app_main', ['_locale' => 'en']),
            ],
            'total'   => [
                'time'   => \round((\microtime(true) - BENCHMARK_START_TIME), 5),
                'memory' => FileSize::humanize(\memory_get_usage() - BENCHMARK_START_MEMORY),
            ],
            'tag'          => $tag,
            'pictures'     => $tagEntity->getPictures(),
        ]);
    }
}
