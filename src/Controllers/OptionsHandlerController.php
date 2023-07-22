<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @package    Devolegkosarev\Parsing\Controllers
 * @version    0.0.1
 * @since      0.0.1
 */

namespace Devolegkosarev\Parsing\Controllers;

use Devolegkosarev\Parsing\AbstractOptionsHandler;

/**
 * A controller class that extends the abstract options handler class
 * 
 */
class OptionsHandlerController extends AbstractOptionsHandler
{
    /**
     * A public function that takes an array of option names and returns a string of options
     *
     * @param array $optionNames The array of option names to get
     * @return array The options string or an empty string if no options are found
     */
    public function getMultipleOptions(array $optionNames = [], string $lang = 'en'): array
    {
        // Initialize an empty array of options
        $optionsArray = [];
        // Loop through the option names
        foreach ($optionNames as $optionName) {
            // Call the private getOptions method with the option name as an argument
            $option = parent::setOptions($optionName, $lang);
            // If the option is not empty, add it to the options array with the option name as a key
            if (!empty($option)) {
                $optionsArray[] = $option;
            }
        }
        // Call the setOptions method with the options array as an argument and return the result
        return $optionsArray;
    }

    /**
     * Extracts the first part of each key-value pair from a string.
     *
     * @param string $stringOptions The string to extract the first parts from.
     * @param string $lang The language used for formatting the options. Default is 'en'.
     * @return string The formatted options separated by ';'.
     */
    function extractNamesOptions(string $stringOptions, string $lang = 'en'): string
    {
        // Initialize an empty array to store the first parts of the key-value pairs
        $arrNameOptions = [];

        // Split the string by ";" into an array of parts
        $parts = explode(';', $stringOptions);

        // Iterate over each part
        foreach ($parts as $part) {
            // Split each part by ":" into key and value
            $keyAndValue = explode(':', $part);
            // Add the first part (key) to the array
            $arrNameOptions[] = trim($keyAndValue[0]);
        }

        // Get the formatted options array based on the first parts of the key-value pairs
        $formatedOptionsArray = $this->getMultipleOptions($arrNameOptions, $lang);

        // Join the formatted options array with ";" and return as a string
        return implode('; ', $formatedOptionsArray);
    }

}