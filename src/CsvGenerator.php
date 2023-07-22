<?php
/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @package Devolegkosarev\Parsing
 * @author     Oleg Kosarev <dev.oleg.kosarev@outlook.com>
 * @version    0.0.1
 * @since      0.0.1
 */

namespace Devolegkosarev\Parsing;

use RuntimeException;
use InvalidArgumentException;
use Devolegkosarev\Parsing\Controllers\OptionsHandlerController;
use Devolegkosarev\Parsing\Controllers\FeaturesHandlerController;
use Devolegkosarev\Parsing\Controllers\CategoryTranslateController;

class CsvGenerator
{
    /**
     * The header of the CSV file.
     *
     * @var array
     */
    protected $csv_header;

    /**
     * The number of fields for each product.
     *
     * @var int
     */
    protected $product_fields;

    protected array $languages;

    /**
     * CsvGenerator constructor.
     */
    public function __construct(array $languages)
    {
        $this->csv_header = [
            "Product code",
            "Product name",
            "Category",
            "Price",
            "List price",
            "Quantity",
            "Features",
            "Description",
            "Vendor",
            "Language",
            "Status",
            "Options"
        ];
        $this->product_fields = count($this->csv_header);

        $this->languages = $languages;
    }

    /**
     * Gets the headers of the CSV file.
     *
     * @return array The headers as an array of strings.
     */
    function getHeaders(): array
    {
        return $this->csv_header;
    }


    /**
     * Generates a CSV file with a list of products.
     *
     * @param string $filename The name of the CSV file to save.
     * @param array $products The array of products.
     * @return bool True if the CSV file was successfully generated, false otherwise.
     * @throws InvalidArgumentException If invalid arguments are provided.
     * @throws RuntimeException If an error occurs while creating the CSV file.
     */
    public function generate(string $filename, array $products): bool
    {
        try {
            // Open the file for writing
            $file = fopen($filename, 'w');
            if (!$file) {
                throw new RuntimeException('Error creating the CSV file.');
            }

            // Write the CSV header
            fputcsv($file, $this->csv_header);

            // Write the products as CSV rows
            foreach ($products as $product) {
                // Validate the product format
                $this->validateProductFields($product);

                // Extract the product fields
                [$code, $name, $category, $price, $list_price, $quantity, $features, $description, $vendor, $fromLanguage, $status, $options] = $product;

                foreach ($this->languages as $key => $toLanguage) {
                    // Write the CSV row
                    fputcsv($file, [
                        $code,
                        $name,
                        $this->setCategory($category, $toLanguage, $fromLanguage),
                        number_format($price, 6),
                        number_format($list_price, 6),
                        (string) $quantity,
                        $this->formatFeatures($features, $toLanguage, $fromLanguage),
                        $this->cleanDescription($description),
                        $vendor,
                        strtolower($toLanguage),
                        strtoupper($status),
                        $this->formatOptions($options, $toLanguage, $fromLanguage),
                    ]);
                }
            }



            // Close the file
            fclose($file);

            // Return true if the file was successfully created
            return true;
        } catch (\Exception $e) {
            // Handle the exception
            throw new RuntimeException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    /**
     * Sets the category name for the product based on the language and vendor.
     *
     * @param array $CategoryNameArray The array of category names.
     * @param string $language The language code.
     * @param string $from The language code of the source text. // TODO: Add this parameter to the method signature and implementation
     * @return string The formatted category name.
     */
    function setCategory(array $CategoryNameArray, string $language, string $from = "en"): string
    {
        $CategoryTranslateController = new CategoryTranslateController($language);
        $CategoryNameTmpArray = [];
        foreach ($CategoryNameArray as $CategoryNameKey => $CategoryNameValue) {
            $CategoryNameTmpArray[] = $CategoryTranslateController->get($CategoryNameValue);
        }
        return implode("///", $CategoryNameTmpArray);
    }

    /**
     * Validates the product fields and throws exceptions if invalid data is provided.
     *
     * @param array $product The product array.
     * @throws InvalidArgumentException If invalid arguments are provided.
     */
    private function validateProductFields(array $product)
    {
        // Check the product format
        if (!is_array($product) || count($product) !== $this->product_fields) {
            throw new InvalidArgumentException('Invalid product format.');
        }

        // Extract the product fields
        [$code, $name, $category, $price, $list_price, $quantity, $features, $description, $vendor, $language, $status, $options] = $product;


        // Validate the product fields and throw exceptions if invalid data is provided
        if (!is_string($code) || empty($code)) {
            throw new InvalidArgumentException('Invalid product code.');
        }
        if (!is_string($name) || empty($name)) {
            throw new InvalidArgumentException('Invalid product name.');
        }
        if (!is_array($category)) {
            throw new InvalidArgumentException('Invalid product category.');
        }
        if (!is_numeric($price)) {
            throw new InvalidArgumentException('Invalid product price.');
        }
        if (!is_numeric($list_price)) {
            throw new InvalidArgumentException('Invalid product list price.');
        }
        if (!is_int($quantity)) {
            throw new InvalidArgumentException('Invalid product quantity.');
        }
        if (!is_array($features)) {
            throw new InvalidArgumentException('Invalid product features.');
        }
        if (!is_string($description)) {
            throw new InvalidArgumentException('Invalid product description.');
        }
        if (!is_string($vendor) || empty($vendor)) {
            throw new InvalidArgumentException('Invalid product vendor.');
        }

        if (!is_string($language) || empty($language) || !in_array(strtolower($language), $this->languages)) {
            $valid_languages = implode(", ", $this->languages);
            throw new InvalidArgumentException("Invalid product language. It should be one of the following: $valid_languages");
        }
        if (!is_string($status) || !in_array($status, ['A', 'H'])) {
            throw new InvalidArgumentException('Invalid product status.');
        }
        if (!is_array($options)) {
            throw new InvalidArgumentException('Invalid product options.');
        }
    }

    /**
     * Formats the list of features for the product.
     *
     * @param array $features The array of features.
     * @param string $language The language code.ъ
     * @param string $from The language code of the source text. // TODO: Add this parameter to the method signature and implementation
     * @return string The formatted list of features.
     */
    private function formatFeatures(array $features, string $language, string $from = "en"): string
    {
        $FeaturesHandlerController = new FeaturesHandlerController($language);
        // Format the list of features for the product
        return implode("; ", $FeaturesHandlerController->handleFeatures($features));
    }

    /**
     * Formats the list of options for the product.
     *
     * @param array $options The array of options.
     * @param string $language The language code.
     * @param string $from The language code of the source text. // TODO: Add this parameter to the method signature and implementation
     * @return string The formatted list of options.
     */
    private function formatOptions(array $options, string $language, string $from = "en"): string
    {
        $OptionsHandlerController = new OptionsHandlerController();
        // Format the list of options for the product
        return implode("; ", $OptionsHandlerController->getMultipleOptions($options, $language));
    }

    /**
     * Cleans the description of the product from HTML tags and newline characters.
     *
     * @param string $description The description of the product.
     * @return string The cleaned description of the product.
     */
    private function cleanDescription(string $description): string
    {
        // Clean the description of the product from HTML tags and newline characters
        return str_replace("\n", ' ', strip_tags($description));
    }

}