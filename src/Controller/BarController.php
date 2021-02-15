<?php

namespace App\Controller;

use App\Repository\BeerRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Contracts\HttpClient\HttpClientInterface;

use App\Entity\Beer;
use App\Entity\Category;

class BarController extends AbstractController
{

    private $client;

    /** @var CategoryRepository $categoryRepository */
    private $categoryRepository;

    /** @var BeerRepository $beerRepository */
    private $beerRepository;

    public function __construct(HttpClientInterface $client, CategoryRepository $categoryRepository, BeerRepository $beerRepository)
    {
        $this->client = $client;
        $this->categoryRepository = $categoryRepository;
        $this->beerRepository = $beerRepository;
    }

    /**
     * @Route("/mentions", name="mentions")
     */
    public function mentions()
    {

        return $this->render('mentions/index.html.twig', [
            'title' => 'Mentions légales',
        ]);
    }

    /**
     * @Route("/beers", name="beers")
     */
    public function beers()
    {
        $beerRepo = $this->getDoctrine()->getRepository(Beer::class);
      
        return $this->render('beers/index.html.twig', [
            'title' => 'Page beers',
            'beers' => $beerRepo->findAll()
        ]);
    }

    /**
     * @Route("/beer/{id}", name="beer_detail")
     */
    public function beer_detail($id)
    {
        $beerRepo = $this->getDoctrine()->getRepository(Beer::class);
        $oneBeer = $beerRepo->find($id);

        $categoryRepo = $this->getDoctrine()->getRepository(Category::class);
        $categoryNormal = $categoryRepo->findCat('normal', $id);
        $categorySpecial = $categoryRepo->findCat('special', $id);

        return $this->render('detail/index.html.twig', [
            'title' => 'Page beers',
            'oneBeer' => $oneBeer,
            'categoryNormal' => $categoryNormal,
            'categorySpecial' => $categorySpecial,
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        $beerRepo = $this->getDoctrine()->getRepository(Beer::class);
        $listBeer = $beerRepo->getLastBeers();

        return $this->render('home/index.html.twig', [
            'title' => "Page d'accueil",
            'listBeer' => $listBeer,
        ]);
    }

    /**
     * @Route("/category/{id}", name="category")
     */
    public function beersByCategory(int $id): Response
    {
        $category = $this->categoryRepository->find($id);

        $beers = $this->beerRepository->getBeersCategory($id);


        return $this->render('beers/category.html.twig', [
            'title' => 'Bières '.$category->getName(),
            'category' => $category->getName(),
            'beers' => $beers,
        ]);
    }

    public function mainMenu(string $categoryId, string $routeName): Response
    {
        $repo = $this->getDoctrine()->getRepository(Category::class);

        $categories = $repo->findByTerm('normal');

        return $this->render('partials/main_menu.html.twig', [
            'categories' => $categories,
            'categoryId' => $categoryId,
            'route_name' => $routeName,
        ]);
    }
}
