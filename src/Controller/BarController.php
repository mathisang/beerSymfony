<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Contracts\HttpClient\HttpClientInterface;

use App\Entity\Beer;
use App\Entity\Category;

class BarController extends AbstractController
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
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

        return $this->render('detail/index.html.twig', [
            'title' => 'Page beers',
            'oneBeer' => $oneBeer
        ]);
    }

    // A MODIFIER
    /**
     * @Route("/categorie/{id}", name="categorie")
     */
    public function categorie($id)
    {
        $beerRepo = $this->getDoctrine()->getRepository(Beer::class);
        $oneBeer = $beerRepo->find($id);

        return $this->render('detail/index.html.twig', [
            'title' => 'Page beers',
            'oneBeer' => $oneBeer
        ]);
    }

    /**
     * @Route("/menu", name="menu")
     */
    public function mainMenu(string $category_id, string $routeName): Response
    {
        // BOUCLER SUR LES CATEGORIES
        $cat = ['coucou', 'test', 'mathis'];

        $return = '';

        // RECUPERER LES CATEGORIES
        foreach ($cat as $c) {
            $return = $return . '<a href="#">'.$c.'</a>';
        }
        return new Response($return);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $beerRepo = $this->getDoctrine()->getRepository(Beer::class);
        $listBeer = $beerRepo->getLastBeers();

        return $this->render('home/index.html.twig', [
            'title' => "Page d'accueil",
            'listBeer' => $listBeer,
        ]);
    }
}
