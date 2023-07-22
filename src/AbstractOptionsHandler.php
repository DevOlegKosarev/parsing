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

use Devolegkosarev\Parsing\Controllers\OptionsTitleTranslateController;
use Devolegkosarev\Parsing\Controllers\OptionsValueTranslateController;
use Devolegkosarev\Parsing\Interfaces\OptionsHandlerInterface;

/**
 * An abstract class that handles options for parsing
 */
abstract class AbstractOptionsHandler implements OptionsHandlerInterface
{
    /**
     * A protected property that holds the base options array
     *
     * @var array
     */
    protected $baseOptions = [
        "Keyboard Stickers" => [
            "RG" => [
                "No Need" => [],
                "Russian" => [
                    "modifier" => "15.000",
                    "modifier_type" => "A"
                ],
                "English" => [
                    "modifier" => "15.000",
                    "modifier_type" => "A"
                ]
            ],
            "options" => [
                "missing_variants_handling" => 'M',
                "multiupload" => 'N',
                "required" => 'N',
                "status" => 'A'
            ]
        ],
        "Install Programs" => [
            "RG" => [
                "No Need" => [],
                "Need" => [
                    "modifier" => "25.000",
                    "modifier_type" => "A"
                ]
            ],
            "options" => [
                "missing_variants_handling" => 'M',
                "multiupload" => 'N',
                "required" => 'N',
                "status" => 'A'
            ]
        ],
        "Transfer Data" => [
            "RG" => [
                "No Need" => [],
                "Need" => [
                    "modifier" => "25.000",
                    "modifier_type" => "A"
                ]
            ],
            "options" => [
                "missing_variants_handling" => 'M',
                "multiupload" => 'N',
                "required" => 'N',
                "status" => 'A'
            ]
        ]
    ];

    /**
     * A private method that returns the options for the given option name or an empty array if not found.
     *
     * @param string $optionsName The option name to get.
     * @return array The options array or an empty array if not found.
     */
    private function getOptions(string $optionsName): array
    {
        // If the argument is empty, return an empty array
        if (empty($optionsName)) {
            return [];
        }

        // If the argument is not a key in the base options array, return an empty array
        if (array_key_exists($optionsName, $this->baseOptions) == false) {
            return [];
        }

        // Return the value of the base options array at the given key
        return $this->baseOptions[$optionsName];
    }

    /**
     * A public method that returns a string of options for the given option name or an empty string if not found.
     *
     * @param string|null $optionsName The option name to get.
     * @param string $lang The language code.
     * @return string The options string or an empty string if not found    
     */
    public function setOptions(string $optionsName = null, string $lang = 'en'): string
    {

        $optionsNameTranslation = $optionsName;
        $OptionsTitleTranslateController = new OptionsTitleTranslateController($lang);
        $OptionsValueTranslateController = new OptionsValueTranslateController($lang);
        $optionsNameTranslation = $OptionsTitleTranslateController->get($optionsName);

        // Call the private getOptions method with the option name as an argument and store the result in a variable
        $valueOption = $this->getOptions($optionsName);

        // If the option is empty, return an empty string
        if (empty($valueOption)) {
            return "";
        }

        // Get the options subarray from the value
        $OptionOptions = $valueOption["options"];

        // Implode the options subarray into a string with /// as a separator
        $options = implode(
            '///',
            array_map(
                function ($v, $k) {
                    if (is_array($v)) {
                        return $k . '[]=' . implode('&' . $k . '[]=', $v);
                    } else {
                        return $k . '=' . $v;
                    }
                },
                $OptionOptions,
                array_keys($OptionOptions)
            )
        );

        // Get the RG subarray from the value
        $OptionRgOptions = $valueOption["RG"];

        // Initialize an empty RG output array
        $OptionsArrayRG = [];
        // Loop through the RG subarray
        foreach ($OptionRgOptions as $keyOptionRG => $valueOptionRG) {

            // Initialize an empty RG subarray
            $rg1 = [];
            // If the value is not empty, loop through it
            if (count($valueOptionRG) > 0) {

                foreach ($valueOptionRG as $keyOptionRG1 => $valueOptionRG1) {
                    // Append the key and value pair to the RG subarray with = as a separator
                    $rg1[] = $keyOptionRG1 . '=' . $valueOptionRG1;

                }

                // Append the key and the imploded RG subarray to the RG output array with /// as a separator
                $OptionsArrayRG[] = $OptionsValueTranslateController->get($optionsName . '.' . $keyOptionRG) . "///" . implode("///", $rg1);
            } else {
                // Append only the key to the RG output array
                $OptionsArrayRG[] = $OptionsValueTranslateController->get($optionsName . '.' . $keyOptionRG);
            }
        }

        // Return the key, the imploded RG output array and the options string with : and /// as separators or an empty string if no option name is given
        return !empty($optionsNameTranslation) ? "$optionsNameTranslation: RG[" . implode(",", $OptionsArrayRG) . "]///$options" : "";
    }
}