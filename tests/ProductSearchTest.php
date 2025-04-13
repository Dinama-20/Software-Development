<?php

use PHPUnit\Framework\TestCase;
use App\Product;
use App\ProductRepository;  // Asumimos que la clase ProductRepository está en /app/models

class ProductSearchTest extends TestCase
{
    public function testSearchByName()
    {
        $repository = new ProductRepository();
        $repository->addProduct(new Product('Watch', 100));
        $repository->addProduct(new Product('Clock', 50));

        $result = $repository->searchByName('Watch');

        $this->assertCount(1, $result);  // Ensure only one product is found
        $this->assertEquals('Watch', $result[0]->getName());  // Ensure the product name matches
    }

    public function testFilterByPrice()
    {
        $repository = new ProductRepository();
        $repository->addProduct(new Product('Watch', 100));
        $repository->addProduct(new Product('Clock', 50));

        $result = $repository->filterByPrice(60, 150);

        $this->assertCount(1, $result);  // Ensure the correct product is returned
        $this->assertEquals('Watch', $result[0]->getName());
    }
}
