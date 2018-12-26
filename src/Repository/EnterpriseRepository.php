<?php

namespace App\Repository;

use App\Entity\Enterprise;
use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Enterprise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enterprise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enterprise[]    findAll()
 * @method Enterprise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnterpriseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Enterprise::class);
    }

    public function createByEnterpriseDetails($enterprise_details) {
        $enterprise = new Enterprise();
        $enterprise
            ->setName($enterprise_details['name'])
            ->setType($enterprise_details['type'])
            ->setAbn($enterprise_details['abn']);

        // Check if we have any directors
        if (isset($enterprise_details['directors']) && is_array($enterprise_details['directors'])) {
            // If so then add the director (person) to this enterprise entity
            foreach ($enterprise_details['directors'] as $enterprise_director_details) {
                $person_repo = $this->getEntityManager()->getRepository(Person::class);
                $director = $person_repo->createByPersonDetails($enterprise_director_details);

                $enterprise->addDirector($director);
            }
        }

        return $enterprise;
    }

    // /**
    //  * @return Enterprise[] Returns an array of Enterprise objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Enterprise
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
