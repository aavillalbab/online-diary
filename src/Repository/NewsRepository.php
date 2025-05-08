<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\News;

use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<News>
 *
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    /**
     * @return News[] Returns an array of News objects
     */
    public function searchNews(
        ?string            $title,
        ?DateTimeInterface $fromDate,
        ?DateTimeInterface $toDate,
        ?bool              $isActive,
        ?Category          $category,
        int                $limit,
        int                $offset
    ): array
    {
        $qb = $this->createQueryBuilder('n')
            ->select('n, c')
            ->leftJoin('n.category', 'c');

        if ($title) {
            $qb->andWhere('n.title LIKE :title')
                ->setParameter('title', '%' . $title . '%');
        }

        if ($fromDate) {
            $qb->andWhere('n.publishedAt >= :fromDate')
                ->setParameter('fromDate', $fromDate->format('Y-m-d 00:00:00'));
        }

        if ($toDate) {
            $qb->andWhere('n.publishedAt <= :toDate')
                ->setParameter('toDate', $toDate->format('Y-m-d 23:59:59'));
        }

        if ($isActive !== null) {
            $qb->andWhere('n.isActive = :isActive')
                ->setParameter('isActive', $isActive);
        }

        if ($category) {
            $qb->andWhere('n.category = :categoryId')
                ->setParameter('categoryId', $category->getId());
        }

        return $qb->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }
}
