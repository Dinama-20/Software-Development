<?php

use PHPUnit\Framework\TestCase;
use Models\Database;

class DatabaseTest extends TestCase
{
    private $db;

    /**
     * @before
     */
    public function setUpDatabase(): void
    {
        $this->db = new Database();
    }

    /**
     * @test
     */
    public function itConnectsToTheDatabase()
    {
        $connection = $this->db->getConnection();
        $this->assertInstanceOf(PDO::class, $connection, "Database connection failed.");
    }

    /**
     * @test
     */
    public function itThrowsExceptionForInvalidConnection()
    {
        $this->expectException(\PDOException::class);
        $invalidDb = new Database('invalid_host', 'invalid_db', 'invalid_user', 'invalid_pass');
        $invalidDb->getConnection();
    }
}
