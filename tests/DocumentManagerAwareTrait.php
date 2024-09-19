<?php

namespace App\Tests;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Query\Builder;
use Doctrine\ODM\MongoDB\Aggregation\Builder as AggregationBuilder;
use Exception;

trait DocumentManagerAwareTrait
{
    /**
     * @template T
     * @param class-string<T> $className
     * @return T|null
     * @throws Exception
     */
    protected static function findDocumentById(string $className, string $id, bool $refresh = false)
    {
        $document = self::getDocumentManager()->find($className, $id);
        if ($document && $refresh) {
            self::refreshDocument($document);
        }

        return $document;
    }

    /**
     * @throws MongoDBException
     */
    protected static function flushDocuments(): void
    {
        self::getDocumentManager()->flush();
    }

    protected static function getDocumentManager(): DocumentManager
    {
        return static::getContainer()->get('doctrine_mongodb')->getManager();
    }

    /**
     * @param object $document
     * @return string
     * @throws MongoDBException
     */
    protected static function persistDocument(object $document): string
    {
        self::getDocumentManager()->persist($document);
        self::getDocumentManager()->flush();

        return $document->getId();
    }

    /**
     * @throws Exception
     */
    protected static function refreshDocument(object $document): void
    {
        self::getDocumentManager()->refresh($document);
    }

    /**
     * @param class-string $className
     * @param string $id
     * @throws MongoDBException
     * @throws Exception
     */
    protected static function removeDocumentById(string $className, string $id): void
    {
        $document = self::findDocumentById($className, $id);
        if ($document) {
            self::getDocumentManager()->remove($document);
            self::getDocumentManager()->flush();
        }
    }

    /**
     * @param Builder|AggregationBuilder $builder
     * @param array $query
     * @return array
     */
    protected static function assertBuiltQueryEquals(Builder|AggregationBuilder $builder, array $query): array
    {
        $debug = $builder->getQuery()->debug();
        self::assertArrayHasKey('query', $debug);
        self::assertIsArray($debug['query']);
        sort($query);
        sort($debug['query']);
        self::assertSame($query, $debug['query']);

        return $debug['query'];
    }
}