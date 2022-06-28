<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function add(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Getting articles with categories and pagination
     *
     * @param int $page
     * @param string $category
     *
     * @return array
     */
    public function getArticlesByCategories(int $page = 1, string $category = ''): array
    {
        $limit = 10;

        $qb = $this->createQueryBuilder('a');
        $qb->select('a.title, a.author, a.category, a.publishedAt')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->addOrderBy('a.publishedAt', 'DESC');

        if (!empty($category)) {
            $qb->where('a.category = :category')->setParameter('category', $category);
        }

        return $qb->getQuery()->getResult() ?? [];
    }
}
