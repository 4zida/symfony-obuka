<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

trait EntityManagerAwareTrait
{
    /**
     * @template T
     * @param class-string<T> $className
     * @param int $id
     * @return T|null
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected static function findEntity(string $className, int $id)
    {
        return self::getEntityManager()->find($className, $id);
    }

    protected static function flushEntities(): void
    {
        self::getEntityManager()->flush();
    }

    protected static function getEntityManager(): EntityManagerInterface
    {
        return static::getContainer()->get('doctrine')->getManager();
    }

    protected static function persistEntity(object $entity): int
    {
        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        return $entity->getId();
    }

    /**
     * @throws ORMException
     */
    protected static function refreshEntity(object $entity): void
    {
        self::getEntityManager()->refresh($entity);
    }

    protected static function removeEntity(object $entity): void
    {
        self::getEntityManager()->remove($entity);
        self::getEntityManager()->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    protected static function removeEntityById(string $entityClass, int $id): void
    {
        $entity = self::getEntityManager()->find($entityClass, $id);
        self::getEntityManager()->remove($entity);
        self::getEntityManager()->flush();
    }
}