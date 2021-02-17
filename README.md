# beerSymfony

## Launch the project
```
composer i  
npm i  
npm run build  
bin/console doctrine:migrations:migrate  
bin/console doctrine:fixtures:load  
symfony server:run  
```

## Partie 4  

public function findCatSpecial(int $id)
{
    return $this->createQueryBuilder('c')
        ->join('c.beers', 'b') // raisonner en terme de relation
        ->where('b.id = :id')
        ->setParameter('id', $id)
        ->andWhere('c.term = :term')
        ->setParameter('term', 'special')
        ->getQuery()
        ->getResult();
}
```

Cette méthode va nous permettre de récupérer la catégorie spéciale d'une bière grace
à  l'argument `id` que nous lui passons.