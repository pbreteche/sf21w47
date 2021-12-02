<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function countByTag(Tag $tag): int
    {
        return $this
            ->getEntityManager()
            ->createQuery(
                'SELECT COUNT(post) FROM '.Post::class.' post '.
                'INNER JOIN post.taggedBy tags '.
                'WHERE tags = :tag'
            )
            ->setParameter('tag', $tag)
            ->getSingleScalarResult()
        ;
    }

    public function countByTagWithBuilder(Tag $tag): int
    {
        return $this
            ->createQueryBuilder('post')
            ->select('COUNT(post)')
            ->innerJoin('post.taggedBy', 'tags')
            ->andWhere('tags = :tag')
            ->getQuery()
            ->setParameter('tag', $tag)
            ->getSingleScalarResult()
        ;
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
