<?php

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Menu>
 *
 * @method Menu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Menu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Menu[]    findAll()
 * @method Menu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    /**
     * @return Menu[]
     */
    public function findAllForTwig(): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.isVisible = true')
            ->orderBy('m.menuOrder')
            ->getQuery()
            ->getResult();
    }

    public function getIndexQueryBuilder(string $field): QueryBuilder
    {
        return $this->createQueryBuilder('m')
            ->where("m.$field IS NOT NULL OR (m.page IS NULL AND m.article IS NULL AND m.link IS NULL AND m.category IS NULL)");
    }

    // public function getIndexQuery(string $field): Query
    // {
    //     $entityManager = $this->getEntityManager();
    //     $connection = $entityManager->getConnection();

    //     $query = "
    //         SELECT * 
    //         FROM menu m
    //         WHERE m.$field IS NOT NULL 
    //             OR (m.page IS NULL AND m.article IS NULL AND m.link IS NULL AND m.category IS NULL)
    //     ";

    //     $statement = $connection->prepare($query);
    //     $statement->execute();

    //     return $statement->fetchAll();
    // }


//    /**
//     * @return Menu[] Returns an array of Menu objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Menu
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
