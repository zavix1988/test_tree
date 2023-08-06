<?php

namespace Core;

use PDO;

/**
 * DB class
 */
class DB
{
    use TSingleton;

    /**
     * @var PDO
     */
    private PDO $dbDriver;


    /**
     * @var int
     */
    public static int $countSql = 0;

    /**
     * @var array
     */
    public static array $queries = [];

    /**
     *
     */
    private function __construct()
    {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        $this->dbDriver = new PDO(DB_CONNECTION . ":host=" . DB_HOST . ";port=" . DB_PORT. ";dbname=" . DB_DATABASE,
            DB_USERNAME,
            DB_PASSWORD,
            $options
        );
    }

    /**
     * @param string $sql
     * @param array $params
     * @return bool
     */
    public function execute(string $sql, array $params = []): bool
    {
        self::$countSql++;
        self::$queries[] = $sql;
        $stmt = $this->dbDriver->prepare($sql);
        return $stmt->execute($params);
    }


    /**
     * @param string $sql
     * @param array $params
     * @return array|false
     */
    public function query(string $sql, array $params = [])
    {
        self::$countSql++;
        self::$queries[] = $sql;

        $stmt = $this->dbDriver->prepare($sql);
        $res =  $stmt->execute($params);

        return ($res !== false) ? $stmt->fetchAll() : [];
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array|mixed
     */
    public function getSingle(string $sql, array $params = [])
    {
        self::$countSql++;
        self::$queries[] = $sql;

        $stmt = $this->dbDriver->prepare($sql);
        $res =  $stmt->execute($params);

        return ($res !== false) ? $stmt->fetch() : [];
    }

    /**
     * @return false|string
     */
    public function lastInsertId()
    {
        return $this->dbDriver->lastInsertId();
    }

}