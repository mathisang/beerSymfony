<?php

namespace App\Controller;

use App\Entity\Client;
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
     * @Route("/statistic", name="statistic")
     */
    public function statistic()
    {
        $clientRepo = $this->getDoctrine()->getRepository(Client::class);
        $clients = $clientRepo->findBy(
            [],
            ['number_beer' => 'DESC']
        );

        // Moyenne
        $avgBeers = $clientRepo->avgBeerClient();
        $avgBeers = $avgBeers[0]['numberBeer'];

        // Ecart type
        $ecartSomme = 0;
        foreach ($clients as $c) {
            $ecartSomme = $ecartSomme + (($avgBeers - $c->getNumberBeer())**2);
        }
        $ecartType = sqrt($ecartSomme/count($clients));

        return $this->render('statistic/index.html.twig', [
            'title' => 'Statistic',
            'clients' => $clients,
            'avgBeers' => $avgBeers,
            'ecartType' => $ecartType,
        ]);
    }

    /**
     * @Route("/consommation", name="consommation")
     */
    public function consommation()
    {
        $clientRepo = $this->getDoctrine()->getRepository(Client::class);

        $clients18 = $clientRepo->getAgeConso(18, 25);
        $clients26 = $clientRepo->getAgeConso(26, 35);
        $clients36 = $clientRepo->getAgeConso(36, 45);
        $clients46 = $clientRepo->getAgeConso(46, 55);
        $clients56 = $clientRepo->getAgeConso(56, 80);

        function avgClients($c) {
            $somme = 0;
            for($i = 0; $i < count($c); $i++) {
                $somme = $somme + $c[$i]->getNumberBeer();
            }
            return $somme/count($c);
        }

        return $this->render('consommation/index.html.twig', [
            'title' => 'Consommation',
            'clients18Avg' => avgClients($clients18),
            'clients26Avg' => avgClients($clients26),
            'clients36Avg' => avgClients($clients36),
            'clients46Avg' => avgClients($clients46),
            'clients56Avg' => avgClients($clients56),
            'clients18Nb' => count($clients18),
            'clients26Nb' => count($clients26),
            'clients36Nb' => count($clients36),
            'clients46Nb' => count($clients46),
            'clients56Nb' => count($clients56),
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
