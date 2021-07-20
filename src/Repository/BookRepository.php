<?php

namespace App\Repository;
use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */

    public function doctrine_req($value)
	{
		$qb = $this->createQueryBuilder('b');
		$builder = $qb
		->select('b.name','COUNT(a) AS authors_count')
		->leftJoin('b.authors', 'a')
		->groupBy('b')
		->having('COUNT(a) > 1')
		->getQuery()
		->getResult();
		return $builder;

	}

	public function sql_req($value)
	{
		$qb = $this->getEntityManager()->createQuery(
			'SELECT b.name, COUNT(a) as authors_count
			 FROM App\Entity\Book b 
			LEFT JOIN b.authors a
			 GROUP BY b
			 
			  HAVING COUNT(a) > 1')->getResult();

		return $qb;

	}
    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
