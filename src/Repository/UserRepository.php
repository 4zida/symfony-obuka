<?php

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

    public function deleteUser(User $user) : void
    {
        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();
    }

    public function getUser($id) : User
    {
        $em = $this->getEntityManager();
        return $em->getRepository(User::class)->find($id);
    }

    public function getUsers() : \Generator
    {
        $em = $this->getEntityManager();
        foreach ($em->getRepository(User::class)->findAll() as $user) {
            yield $user;
        }
    }

    public function getUsersAsArray() : array
    {
        return $this->createQueryBuilder('u')
            ->select()
            ->getQuery()
            ->getResult();
    }

    public function getUsersByCompany(Company $company) : array
    {
        $em = $this->getEntityManager();
        return $em->getRepository(User::class)->findBy(['company' => $company]);
    }

    public function getUsersByRole(string $role) : array
    {
        $em = $this->getEntityManager();
        return $em->getRepository(User::class)->findBy(['role' => $role]);
    }
}
