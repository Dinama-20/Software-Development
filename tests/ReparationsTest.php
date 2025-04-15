<?php

use PHPUnit\Framework\TestCase;
use Models\Database;

class ReparationsTest extends TestCase
{
    private $db;

    /**
     * @before
     */
    public function setUpDatabase(): void
    {
        $this->db = (new Database())->getConnection();
    }

    /**
     * @test
     */
    public function itSubmitsRepairRequestSuccessfully()
    {
        $query = "INSERT INTO repairs (service_type, details, contact_info, preferred_date, image_path) 
                  VALUES ('watch-repair', 'Broken glass', 'test@example.com', '2023-12-01', NULL)";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute();

        $this->assertTrue($result, "Repair request submission failed.");
    }

    /**
     * @test
     */
    public function itFetchesRepairRequests()
    {
        $query = "SELECT * FROM repairs";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $repairs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->assertIsArray($repairs, "Failed to fetch repair requests.");
    }
}
