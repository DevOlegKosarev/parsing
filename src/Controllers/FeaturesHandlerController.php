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

use Devolegkosarev\Parsing\AbstractFeaturesHandler;
use Devolegkosarev\Parsing\Libraries\FormatFeaturesValueLibraries;
use Devolegkosarev\Parsing\Controllers\FeaturesTitleTranslateController;
use Devolegkosarev\Parsing\Controllers\FeaturesValueTranslateController;
use Devolegkosarev\Parsing\Controllers\FeaturesSuffixTranslateController;

/**
 * 
 * A controller that inherits from an abstract class and implements the specific logic of processing features
 */
class FeaturesHandlerController extends AbstractFeaturesHandler
{
    /**
     * @var string The language.
     */
    private $lang;

    /**
     * @var FormatFeaturesValueLibraries The format features value libraries.
     */
    private $FormatFeaturesValueLibraries;

    /**
     * @var FeaturesValueTranslateController The features value translate controller.
     */
    private $FeaturesValueTranslateController;

    /**
     * @var FeaturesTitleTranslateController The features title translate controller.
     */
    private $FeaturesTitleTranslateController;
    /**
     * @var FeaturesSuffixTranslateController The features suffix translate controller.
     */
    private $FeaturesSuffixTranslateController;

    /**
     * MyClass constructor.
     *
     * @param string $lang The language.
     */
    public function __construct($lang)
    {
        $this->lang = $lang;
        $this->FormatFeaturesValueLibraries = new FormatFeaturesValueLibraries();
        $this->FeaturesValueTranslateController = new FeaturesValueTranslateController($this->lang);
        $this->FeaturesTitleTranslateController = new FeaturesTitleTranslateController($this->lang);
        $this->FeaturesSuffixTranslateController = new FeaturesSuffixTranslateController($this->lang);
        $this->noTranslateValue = ['Brand', 'Model', 'Capacity', 'Drive', 'COA', 'Battery Cycles', 'GPU', 'LCD Graphics array', 'Keyboard', 'RAM', 'CPU', 'LCD Size'];

    }

    /**
     * A method that takes an array of features and returns an array of processed features
     *
     * @param array $features An array of features in the format [name => value]
     * @return array An array of processed features in the format [name: suffix [value]]
     */
    public function handleFeatures(array $features): array
    {
        // Define an array of replacements 
        $replacements = [
            "KB" => $this->FeaturesValueTranslateController->get("size.kb"),
            "MB" => $this->FeaturesValueTranslateController->get("size.mb"),
            "GB" => $this->FeaturesValueTranslateController->get("size.gb"),
            "TB" => $this->FeaturesValueTranslateController->get("size.tb"),
            "PB" => $this->FeaturesValueTranslateController->get("size.pb"),
            "EB" => $this->FeaturesValueTranslateController->get("size.eb"),
            "ZB" => $this->FeaturesValueTranslateController->get("size.zb"),
            "YB" => $this->FeaturesValueTranslateController->get("size.yb"),
        ];

        // Create an empty array for the results
        $results = [];
        // Loop through the array of features
        foreach ($features as $name => $value) {

            if (strpos($name, 'COA') !== false && preg_match('/win/i', $value)) {
                $value = $this->FormatFeaturesValueLibraries->format_os($value);
            }

            if (strpos($name, 'Capacity') !== false) {
                $value = str_replace(array_keys($replacements), array_values($replacements), $this->FormatFeaturesValueLibraries->format_size($value));

            }

            // If the dimension is "Drive", check if it has multiple values separated by "+"
            if ($name === "Drive") {

                // Format the size of each element of the array 
                $formatted_array = array_map([$this->FormatFeaturesValueLibraries, 'format_size'], explode("///", $value));

                // Apply the replacements to each element of the array 
                $translated_array = array_map(function ($v) use ($replacements) {
                    return str_replace(array_keys($replacements), array_values($replacements), $v);
                }, $formatted_array);

                // Join the elements of the array with a separator 
                $value = implode("///", $translated_array);

                // Check if the value contains both "SSD" and "HDD"
                $drive_types = [];
                if (strpos($value, "SSD") !== false) {
                    $drive_types[] = "SSD";
                }
                if (strpos($value, "HDD") !== false) {
                    $drive_types[] = "HDD";
                }
                $drive_types = array_unique($drive_types); // remove duplicates
                if (!empty($drive_types)) {
                    // Add a new dimension for "Drive Type" with the values from the array
                    $results[] = trim($this->FeaturesTitleTranslateController->get("Drive Type") . ": " . $this->FeaturesSuffixTranslateController->get("Drive Type") . "[" . implode("///", $drive_types) . "]");
                }
            }

            if (!in_array($name, $this->noTranslateValue)) {
                // Call the translateValue function and pass it the name and value of the feature
                $value = trim($this->translateValue($name, $value));
            }

            // Add the name, prefix "S:[" and suffix "]" to the feature value
            $newValue = trim($this->FeaturesTitleTranslateController->get("$name") . ": " . trim($this->FeaturesSuffixTranslateController->get($name)) . "[" . trim($value) . "]");
            // Add the new value to the results array
            $results[] = trim($newValue);


        }
        // Return the results array
        return $results;
    }


    /**
     * Translate a value depending on the name of the feature
     * @param string $name The name of the feature
     * @param string $value The value of the feature
     * @return string The translated value
     */
    function translateValue($name, $value)
    {

        // Use switch to handle different values
        switch ($name) {
            case 'COA':
                // If the value contains 'Missing', then translate the value
                if (strpos($value, 'Missing') !== false) {
                    $value = $this->FeaturesValueTranslateController->get($name . "." . $value);
                }
                break;
            case 'Drive':

                break;
            case 'Battery Cycles':

                break;
            // Add other cases as needed
            default:
                // By default, translate all values
                $value = $this->FeaturesValueTranslateController->get($name . "." . $value);
        }
        // Return the translated value
        return $value;
    }

    /**
     * Parses the given features and returns them in the format [name: suffix [value]]
     * 
     * @param string $features The features to parse
     * @return array The parsed features in the format [name: suffix [value]]
     */
    public function parseFeatures(string $features): string
    {
        // Split the string by ";"
        $items = explode(';', $features);

        // Create an empty array for the output
        $output = [];

        // Loop through all items
        foreach ($items as $item) {
            // Find substrings like X:[Y] or SX:[Y] or EX:[Y] or MX:[Y]
            preg_match('/^(.*?):\s*(.*?)\[(.*?)\]/', $item, $match);

            // Get the key X and remove spaces
            $key = trim($match[1]);

            // Get the value Y and remove spaces
            $value = trim($match[3]);

            // Get the optional prefix S or E and remove spaces
            $prefix = isset($match[2]) ? trim($match[2]) : '';

            // Check if the value should be translated
            $translateValue = !in_array($key, $this->noTranslateValue);

            // Use your FeaturesTitleTranslateController or translation function here
            $translatedKey = $this->FeaturesTitleTranslateController->get($key);

            // Translate the value if needed
            $translatedValue = $translateValue ? $this->FeaturesValueTranslateController->get($key . "." . $value) : $value;

            // Add the parsed feature to the output array
            $output[] = $translatedKey . ': ' . $prefix . '[' . $translatedValue . ']';
        }

        // Return the parsed features
        return implode(';', $output);
    }



}