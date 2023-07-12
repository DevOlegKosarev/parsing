<?php

namespace Devolegkosarev\Parsing\Interfaces;

/**
 * Interface VendorsInterface
 *
 * This interface defines the contract for interacting with vendors and their products.
 */
interface VendorsInterface
{
    /**
     * Get all products.
     *
     * Retrieves all products from the vendor.
     *
     * @return array The array of products. Each product is represented as an associative array with the following keys:
     *   - id: int, the unique identifier of the product.
     *   - name: string, the name of the product.
     *   - price: float, the price of the product.
     */
    public function getAllProducts(): array;

    /**
     * Sync all products.
     *
     * Synchronizes all products with the vendor's database.
     *
     * @return array The result of the sync operation. It is an associative array with the following structure:
     *   - $categoryName: array, defines the fields for each category.
     *     - 'iData': int, the value for the iData field.
     *     - $vendorName: int, the value for the specific vendor field.
     */
    public function syncAllProducts(): array;
}