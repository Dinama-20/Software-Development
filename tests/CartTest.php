<?php

use PHPUnit\Framework\TestCase;
use App\Cart;
use App\Product;  // Asumimos que la clase Product está en /app/models

class CartTest extends TestCase
{
    public function testAddProductToCart()
    {
        $product = new Product('Watch', 100);
        $cart = new Cart();
        
        $cart->addProduct($product);

        $this->assertCount(1, $cart->getProducts());  // Check if the product was added
    }

    public function testRemoveProductFromCart()
    {
        $product = new Product('Watch', 100);
        $cart = new Cart();

        $cart->addProduct($product);
        $cart->removeProduct($product);

        $this->assertCount(0, $cart->getProducts());  // Check if the product was removed
    }

    public function testCalculateCartTotal()
    {
        $cart = new Cart();
        $cart->addProduct(new Product('Watch', 100));
        $cart->addProduct(new Product('Clock', 50));

        $total = $cart->calculateTotal();

        $this->assertEquals(150, $total);  // Check if the total is correct
    }
}
