<?php

use PHPUnit\Framework\TestCase;

class GeneratePdfTest extends TestCase
{
    /**
     * @test
     */
    public function itFailsWhenCartIsEmpty()
    {
        $_SESSION['cart'] = [];
        $this->expectOutputString('Cart is empty. Please add items to the cart before generating a PDF.');
        include __DIR__ . '/../public/generate_pdf.php';
    }

    /**
     * @test
     */
    public function itFailsWhenUserIsNotLoggedIn()
    {
        unset($_SESSION['user']);
        $this->expectOutputString('User is not logged in. Please log in to proceed.');
        include __DIR__ . '/../public/generate_pdf.php';
    }

    /**
     * @test
     */
    public function itGeneratesPdfSuccessfully()
    {
        $_SESSION['cart'] = [['name' => 'Watch', 'price' => 100]];
        $_SESSION['user'] = ['username' => 'testuser', 'email' => 'test@example.com'];

        ob_start();
        include __DIR__ . '/../public/generate_pdf.php';
        $output = ob_get_clean();

        $this->assertNotEmpty($output, "PDF generation failed.");
    }
}
