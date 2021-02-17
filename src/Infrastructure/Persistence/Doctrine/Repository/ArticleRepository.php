<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Model\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
    implements \App\Domain\Model\Article\ArticleRepositoryInterface
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Article::class);
        $this->manager = $manager;
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Delete article
     * @param Article $article
     */
    public function deleteArticle(Article $article): void
    {
        $this->manager->remove($article);
        $this->manager->flush();
    }

    /**
     * Add article
     * @param Article $article
     * @return int
     */
    public function addArticle(Article $article): int
    {
        $this->manager->persist($article);
        $this->manager->flush();
        return $article->getId();
    }

    public function updateArticle(Article $article): void
    {
        $this->manager->persist($article);
        $this->manager->flush();
    }
}
