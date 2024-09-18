<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

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

    /**
     * @return array
     */
    public function getCompaniesAsArray(): array
    {
        return $this->createQueryBuilder('c')
            ->select()
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Company $company
     * @return void
     */
    public function deleteCompany(Company $company): void
    {
        $this->getEntityManager()->remove($company);
        $this->getEntityManager()->flush();
    }

    /**
     * @param int $id
     * @return Company
     */
    public function getCompanyById(int $id): Company
    {
        return $this->getEntityManager()->getRepository(Company::class)->find($id);
    }
}
