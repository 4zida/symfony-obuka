<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Repository;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param User $user
     * @return void
     */
    public function deleteUser(User $user): void
    {
        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();
    }

    /**
     * @return array
     */
    public function getUsersAsArray(): array
    {
        return $this->createQueryBuilder('u')
            ->select()
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Company $company
     * @return array
     */
    public function getUsersByCompany(Company $company): array
    {
        $em = $this->getEntityManager();
        return $em->getRepository(User::class)->findBy(['company' => $company]);
    }

    /**
     * @param string $role
     * @return array
     */
    public function getUsersByRole(string $role): array
    {
        $em = $this->getEntityManager();
        return $em->getRepository(User::class)->findBy(['role' => $role]);
    }

    /**
     * @param User $user
     * @return void
     */
    public function updateUser(User $user): void
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }
}
