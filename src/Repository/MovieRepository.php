<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function findByKeyword(?string $keyword)
    {
        if (null === $keyword || '' === $keyword)
        {
            return [];
        }

        $qb = $this->createQueryBuilder('m');
        $qb ->select('m, d, g')
            ->leftJoin('m.director', 'd')
            ->leftJoin('m.genre', 'g')
            ->setMaxResults(100);

        if ($keyword)
        {
            $words = explode(' ', $keyword);

            foreach ($words as $kw => $word)
            {
                $orX = $qb->expr()->orX();
                $orX->add($qb->expr()->like('m.title', ':keyword' . $kw));
                $orX->add($qb->expr()->like('m.title', ':keywordWhitespace' . $kw));

                $qb->andWhere($orX);
                $qb->setParameter('keyword' . $kw, $word.'%');
                $qb->setParameter('keywordWhitespace' . $kw, '% '.$word.'%');
            }
        }

        return $qb->getQuery()->getResult();
    }

    public function findGenresKeyword(?string $keyword)
    {
        if (null === $keyword || '' === $keyword)
        {
            return [];
        }

        $qb = $this->createQueryBuilder('m');
        $qb ->select('g.id, COUNT(m.id) as total')
            ->leftJoin('m.director', 'd')
            ->leftJoin('m.genre', 'g')
            ->groupBy('g.id');

        if ($keyword)
        {
            $words = explode(' ', $keyword);

            foreach ($words as $kw => $word)
            {
                $orX = $qb->expr()->orX();
                $orX->add($qb->expr()->like('m.title', ':keyword' . $kw));
                $orX->add($qb->expr()->like('m.title', ':keywordWhitespace' . $kw));

                $qb->andWhere($orX);
                $qb->setParameter('keyword' . $kw, $word.'%');
                $qb->setParameter('keywordWhitespace' . $kw, '% '.$word.'%');
            }
        }

        $items = $qb->getQuery()->getArrayResult();
        $genresCount = [];

        foreach ($items as $item)
        {
            $genresCount[$item['id']] = $item['total'];
        }

        return $genresCount;
    }

    public function findAllCustom()
    {
        $qb = $this->createQueryBuilder('m');
        $qb ->select('m, d, g')
            ->leftJoin('m.director', 'd')
            ->leftJoin('m.genre', 'g');

        return $qb->getQuery()->getResult();
    }
}