<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Director;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LikeController extends AbstractController
{
    public function index(
        Request $request,
        MovieRepository $movieRepository,
        EntityManagerInterface $entityManager
    )
    {
        if ($request->isXmlHttpRequest())
        {
            return $this->render('Index/like-result.html.twig', [
                'items' => $movieRepository->findByKeyword(
                    $request->get('keyword')
                ),
                'genresCount' => $movieRepository->findGenresKeyword(
                    $request->get('keyword')
                ),
                'keyword' => $request->get('keyword'),
                'genres' => $entityManager->getRepository(Genre::class)->findBy([], ['name' => 'ASC']),
            ]);
        }

        return $this->render('Index/like.html.twig');
    }
}