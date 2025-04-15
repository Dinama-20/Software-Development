<?php

use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    /**
     * @before
     */
    public function setUpCart(): void
    {
        $_SESSION['cart'] = [];
    }

    /**
     * @test
     */
    public function itAddsProductToCart()
    {
        $product = ['name' => 'Watch', 'price' => 100];
        $_SESSION['cart'][] = $product;

        $this->assertCount(1, $_SESSION['cart'], "Product was not added to the cart.");
    }

    /**
     * @test
     * @depends itAddsProductToCart
     */
    public function itRemovesProductFromCart()
    {
        $_SESSION['cart'] = [['name' => 'Watch', 'price' => 100]];
        array_pop($_SESSION['cart']);

        $this->assertEmpty($_SESSION['cart'], "Cart is not empty after removing the product.");
    }

    /**
     * @test
     */
    public function itClearsTheCart()
    {
        $_SESSION['cart'] = [['name' => 'Watch', 'price' => 100]];
        $_SESSION['cart'] = [];

        $this->assertEmpty($_SESSION['cart'], "Cart was not cleared.");
    }

    /**
     * @test
     */
    public function itMeasuresCartPerformance()
    {
        $start = microtime(true);

        for ($i = 0; $i < 1000; $i++) {
            $_SESSION['cart'][] = ['name' => "Product $i", 'price' => rand(1, 100)];
        }

        $end = microtime(true);
        $this->assertLessThan(1, $end - $start, "Cart operations took too long.");
    }
}
