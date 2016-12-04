<?php

namespace Refactoring\Example01;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Driver\Statement;

class Db
{
    const TABLE_NAME = 'images';

    /**
     * @var Connection
     */
    protected static $conn;

    /**
     * @var Db
     */
    protected static $instance;

    protected function __construct()
    {
    }

    /**
     * @return Db
     */
    public static function getInstance()
    {
        if (null === static::$conn) {
            static::$conn = static::getConnection();
        }
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @return Connection
     * @throws \Doctrine\DBAL\DBALException
     */
    private static function getConnection()
    {
        $connectionParams = [
            'url' => 'sqlite:///:memory:',
        ];
        return DriverManager::getConnection($connectionParams);
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function createImageTable()
    {
        $table = new Table(static::TABLE_NAME);
        $table->addColumn('id', 'integer', ['unsigned' => true]);
        $table->addColumn('type', 'string');
        $table->setPrimaryKey(['id']);
        $sm = static::$conn->getSchemaManager();
        $sm->dropAndCreateTable($table);
    }

    public function dropImageTable()
    {
        $sm = static::$conn->getSchemaManager();
        $sm->dropTable(static::TABLE_NAME);
    }

    /**
     * @param array $list
     */
    public function insertImageData(array $list)
    {
        foreach ($list as $data) {
            static::$conn->insert(static::TABLE_NAME, $data);
        }
    }

    /**
     * @return QueryBuilder
     */
    public function createQuery()
    {
        return static::$conn->createQueryBuilder();
    }

    /**
     * @param Statement $stmt
     * @return array
     */
    public function fetchAll(Statement $stmt)
    {
        $result = [];
        while ($row = $stmt->fetch()) {
            $result[] = $row;
        }
        return $result;
    }
}