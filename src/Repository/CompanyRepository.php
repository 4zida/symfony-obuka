<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function getCompaniesAsArray($number = 100) : array
    {
        return $this->createQueryBuilder('c')
            ->select()
            ->setMaxResults($number)
            ->getQuery()
            ->getResult();
    }

    public function deleteCompany(Company $company): void
    {
        $this->getEntityManager()->remove($company);

        $this->getEntityManager()->flush();
    }
}
