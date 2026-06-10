<?php

declare(strict_types=1);

class Database
{
    /** @var PDO|null Shared connection instance */
    private static ?PDO $instance = null;

    private const DB_HOST = '';
    private const DB_NAME = '';
    private const DB_USER = '';
    private const DB_PASS = '';
    private const DB_CHARSET = 'utf8mb4';


    private function __construct() {}

 
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                self::DB_HOST,
                self::DB_NAME,
                self::DB_CHARSET
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            self::$instance = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
        }

        return self::$instance;
    }
}
