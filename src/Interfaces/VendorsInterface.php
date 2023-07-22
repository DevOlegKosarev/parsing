<?php
/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @package    Devolegkosarev\Parsing\Interfaces
 * @author     Oleg Kosarev <dev.oleg.kosarev@outlook.com>
 * @version    0.0.1
 * @since      0.0.1
 */

namespace Devolegkosarev\Parsing\Interfaces;

use InvalidArgumentException;

/**
 *
 * This interface defines the contract for interacting with vendors and their products.
 */
interface VendorsInterface
{
    /**
     * Get all products.
     *
     * Retrieves all products from the vendor specified in the features array.
     * 
     * @see \Devolegkosarev\Parsing\Interfaces\VendorsInterface::setFeatures() See the setFeatures function for more details on how to set and validate the features.
     * @see \Devolegkosarev\Parsing\Interfaces\VendorsInterface::setOptions() See the setOptions function for more details on how to set and validate the options.
     * 
     * @return array The array of products. Each product is an associative array with the following keys and values:
     * - product_code: string, the unique identifier of the product, e.g. "LAP-001"
     * - product_name: string, the name of the product, e.g. "Lenovo IdeaPad 3 Laptop"
     * - category: array, the array of categories that define the product group, e.g. ["Computers", "Laptops", "Lenovo"]
     * - price: float, the price of the product in the currency specified in the options array, e.g. 500 EUR
     * - list_price: float, the price of the product before discount or promotion, e.g. 550 EUR
     * - quantity: int, the number of units of the product available in stock, e.g. 10
     * - features: array, the array of key-value pairs that describe the product features, e.g. ["CPU" => "Intel Core i5", "RAM" => "8 GB", "Drive" => "256 GB SSD"]. 
     * - description: string, the detailed description of the product, its benefits and features, e.g. "Lenovo IdeaPad 3 Laptop is a reliable and powerful assistant for work and entertainment."
     * - vendor: string, the name of the manufacturer or supplier of the product, e.g. "Lenovo"
     * - language: string, the code of the language in which the product description is written by default, e.g. "en". This language will be used as the source language for translation.     
     * - status: string, the one-letter status of the product: A or H. A means that the product is active and available for purchase. H means that the product is passive and not available for purchase, e.g. "A"
     * - options: array, the array of strings that contain the names of additional services or products that can be ordered together with the product, e.g. ["Keyboard Stickers", "Install Programs", "Transfer Data"]
     */
    public function getAllProducts(): array;

    /**
     * Sync all products.
     *
     * Synchronizes all products with the vendor's database.
     *
     * @return array The result of the sync operation. It is an associative array with the following structure:
     * - $categoryName: array, defines the fields for each category.
     * - 'iData': int, the value for the iData field.
     * - $vendorName: int, the value for the specific vendor field.
     */
    public function syncAllProducts(): array;

    /**
     * Sets the features of a product based on its dimensions and name.
     *
     * This function takes an array of dimensions and a product name as arguments and returns an object with the product features.
     * These are the names of the product features keys and the values should be returned in this format:
     * 
     * @param array $DimensionsObject An array with dimensions such as length, width, height and weight
     * @param string $ProductName The name of the product
     * @return array The array of Features. Each features is an associative array with the following keys and values:
     * Additional Info: Additional information about the product, such as Brand New Battery, Chip/Crack, Knox warranty void, etc. This can be a string or an array of strings separated by '///'.
     * Appearance: The appearance of the product, such as Brand New, Grade A-C, Incomplete, Motherboard only, Swap, etc. This should be a string.
     * Band Type: The type of the band for watches or bracelets, such as leather, metal, silicone, etc. This should be a string.
     * Battery cycles: The number of charge-discharge cycles of the device battery. This should be an integer or string.
     * Battery status: The status of the device battery, such as High Cycle Count, Excellent, Untested, etc. This should be a string.
     * Boxed: Aftermarket Box, Charger included, Damaged Box, Empty Kit, Full Kit, etc. This should be a string. -
     * Brand: The brand of the product. This should be a string.
     * Capacity: The amount of memory or disk of the device, such as 16 GB, 256 GB, etc. This should be a string with a unit.
     * Cloud Lock: The presence of a cloud service lock on the device, such as iCloud, Google Account, etc. This should be a boolean value ('Yes' or 'No').
     * COA: The presence of a certificate of authenticity for the product. This should be a String value ("Windows 10 Proffesional"). -
     * Color: The color of the case or screen of the device. This should be a string.
     * Color Band: The color of the strap for watches or bracelets. This should be a string.
     * Color Case: The color of the case or cover of the device. This should be a string.
     * CPU: The processor of the computer or laptop. This should be a string with the model name and speed.
     * Customized: The presence of customization of the product, such as engraving, stickers, modifications, etc. This should be a string value ('Branding'). -
     * Drive: The type of disk of the device, such as 1 TB HDD, 128 GB SSD, etc. This should be a string with the model name and capacity. -
     * Drive Type: The type of drive of the device, such as SSD, HDD, etc. This should be a string.
     * Form factor: The form factor of the computer or laptop, such as desktop, all-in-one, ultrabook, DT, 1-4U rack, USFF, USDT etc. This should be a string. -
     * Functionality: The functionality of the product Incomplete Working, Power tested, Visually tested, Working*, Working. This should be an string.
     * GPU: The graphics processor of the computer or laptop. This should be a string with the model name and memory size.
     * Keyboard: The type of keyboard of the device in the form of ISO 3166-1 alpha-3 country code, such as RUS, USA, etc. This should be a string with three uppercase letters.
     * LCD Graphics array: The resolution of the device screen "2.2K (2240x1400)" or "2160x1440" or "XGA (1024x768)". This should be a string with two numbers separated by 'x'. -
     * Network Lock: The presence of a network operator lock on the device, such as AT&T, Verizon, etc. This should be a boolean value ('Yes' or 'No').
     * PC Additional Fault: The presence of additional faults in the computer or laptop. This should be a string value ('Cam Issue' or 'Faulty Fingerprint Scanner' or 'Minor LCD Fault (Discoloration/Dust)').
     * PC Fault Descriptions: The description of the main faults in the computer or laptop. This can be a string or an array of strings separated by '///'.
     * Quality: The quality of the product 'Original'.
     * RAM: The amount of RAM of the device, typically measured in gigabytes (GB) or megabytes (MB). For example, it can be represented as "20GB" or "2GB" or "40GB".
     * Stand: The presence of a stand for the computer or laptop should be represented as a string value. It can be one of the following options: 'Missing', 'Table stand', 'Wall mount', or any other appropriate description based on the available options.
     * LCD Size: This option defines the diagonal length of the display in inches, such as 14 or 15.6. This should be a number.
     * 
     * @example \Devolegkosarev\Example\Controllers\ExampleController.php description
     * <code>
     * array( 
     *  array($keyFeatures1 => $valueFeatures1), 
     *  array($keyFeatures2 => $valueFeatures2),
     *  array($keyFeatures3 => $valueFeatures3) 
     * )
     * </code>
     * 
     */
    public function setFeatures(array $DimensionsObject, string $ProductName): array;

    /**
     * Sets the options for a given category name.
     *
     * @param array $categoryName The name of the category to set the options for.
     * @return array The options for the category name.
     * @throws InvalidArgumentException If the category name is not valid.
     */
    public function setOptions(array $categoryName): array;
}