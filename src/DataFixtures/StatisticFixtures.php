<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\Client;
use App\Entity\Statistic;
use App\Entity\Beer;

class StatisticFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $count = 0;

        $repoBeer = $manager->getRepository(Beer::class);
        while ($count < 20) {
            $client = new Client();

            $client->setEmail('client' . $count . '@mail.com');
            $client->setWeight(random_int(40, 120));
            $client->setName('client kiboi' . $count);

            $numberBeer = random_int(1, 6);

            for ($i = 1; $i <= $numberBeer; $i++) {
                $beerId = $repoBeer->findAll();
                /** @var Beer $beerId */
                $beerId = $beerId[random_int(0, count($beerId) - 1)];
                $categoryId = $beerId->getCategories()[0]->getId();

                $statistic = new Statistic();
                $statistic->setBeer($beerId);
                $statistic->setClient($client);
                $statistic->setCategoryId($categoryId);
                $statistic->setScore(random_int(0, 10));
                $manager->persist($statistic);
            }

            $client->setNumberBeer($numberBeer);

            $manager->persist($client);
            $count++;
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            AppFixtures::class,
        );
    }
}