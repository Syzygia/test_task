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

	public function filter_req($field_name, $value) {
		$qb = $this->createQueryBuilder('b');
		$res = $qb
			->where('b.'.$field_name.' LIKE :value')
			->setParameter('value', '%'.$value.'%')
			->getQuery()
			->getResult();
		return $res;

	}

    public function doctrine_req()
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

	public function sql_req()
	{
		$con = $this->getEntityManager()->getConnection();
		$sql =
			'SELECT  b.name, COUNT(a) as authors_count
			 FROM book b 
			LEFT JOIN author_book a on (b.id = a.book_id)
			 GROUP BY b.id			 
			  HAVING COUNT(a) > 1';
		$stmt = $con->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAllAssociative();

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
