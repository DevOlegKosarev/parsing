<?php
/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @package    CodeIgniter 4
 * @subpackage DevOlegKosarev\Parsing\Libraries
 * @author     Oleg Kosarev <dev.oleg.kosarev@outlook.com>
 * @version    0.0.1
 * @since      Version 0.0.1
 */

namespace DevOlegKosarev\Parsing\Libraries;

use Exception;

/**
 * Class VendorsLibraries
 * Provides methods for working with vendors
 *
 * A vendor is a third-party service or provider that can be integrated with the application
 * For example, a vendor can be a service that provides a list of products for sale
 * Each vendor has a corresponding file and class in the Controllers/Vendors directory
 * Each vendor also has a configuration file and class in the Config/Vendors directory
 * The configuration file contains the settings and credentials for the vendor
 * The vendor class contains the methods for interacting with the vendor's API or SDK
 *
 * This class provides methods for checking the existence and validity of vendor files and classes
 * It also provides methods for generating aliases and prices for vendors
 */
class VendorsLibraries
{
    // /**
    //  * Check if a vendor file and class exist
    //  *
    //  * @param string $vendor The name of the vendor
    //  * @return bool True if both exist, false otherwise
    //  * @throws Exception If the file or class does not exist
    //  */
    // public function checkVendor(string $vendor): bool
    // {
    //     // Get the path to the vendor file
    //     $vendorFile = APPPATH . 'Controllers' . DIRECTORY_SEPARATOR . 'Vendors' . DIRECTORY_SEPARATOR . $vendor . '.php';

    //     // Check if the file exists
    //     if (!file_exists($vendorFile)) {
    //         // File not found
    //         throw new Exception('Vendor file not found: ' . $vendorFile, 500);
    //     }

    //     // Include the file to make the class available for checking
    //     include_once($vendorFile);

    //     // Check if the class exists
    //     $vendorClass = 'App\\Controllers\\Vendors\\' . $vendor;
    //     if (!class_exists($vendorClass)) {
    //         // Class does not exist
    //         throw new Exception('Class does not exist: ' . $vendorFile, 500);
    //     }

    //     // Return true if both exist
    //     return true;
    // }

    // /**
    //  * Check if a vendor configuration file and class exist
    //  *
    //  * @param string $vendor The name of the vendor
    //  * @return bool True if both exist, false otherwise
    //  */
    // public function checkVendorConfig(string $vendor): bool
    // {
    //     // Get the path to the vendor configuration file
    //     $path = APPPATH . 'Config' . DIRECTORY_SEPARATOR . 'Vendors' . DIRECTORY_SEPARATOR . $vendor . '.php';

    //     // Try to require the configuration file
    //     try {
    //         require_once($path);
    //     } catch (\Throwable $e) {
    //         // Handle the error if the file cannot be loaded
    //         return false;
    //     }

    //     // Check if the class exists
    //     $configClass = 'App\\Config\\Vendors\\' . $vendor;
    //     if (!class_exists($configClass)) {
    //         // Return false if it does not exist
    //         return false;
    //     }

    //     // Return true if both exist
    //     return true;
    // }

    /**
     * Generate an alias from a category name
     *
     * @param string $category The category name
     * @return string The alias in lowercase with dashes instead of spaces or other characters
     */
    public function generateAlias($category)
    {
        $alias = strtolower($category);
        $alias = preg_replace('/[^a-z0-9]/', '-', $alias);
        $alias = preg_replace('/-+/', '-', $alias);
        return $alias;
    }

    /**
     * Calculate the price with margin and VAT
     *
     * @param float $price The original price
     * @param int $margin The margin percentage (default 0)
     * @param int $vat The VAT percentage (default 21)
     * @return int The rounded price with margin and VAT applied
     */
    public function price(float $price = 0.00, int $margin = 0, int $vat = 21): int
    {
        if ((int) $price <= 0) {
            return $price;
        }

        if ($margin > 0) {
            $price = $price + ($price * $margin / 100);
        }

        if ($vat > 0) {
            $price = $price + ($price * $vat / 100);
        }

        return round($price);
    }

    /**
     * Translate a text using Google Translate API
     *
     * @param string $text The text to translate
     * @param string $sourceLang The source language code (default 'en')
     * @param string $targetLang The target language code
     * @return string|null The translated text or null if the parameters are invalid
     */
    public function google_translate(string $text = null, string $sourceLang = 'en', string $targetLang = null)
    {
        // Check if the text and target language are provided
        if ($text == null or $targetLang == null) {
            return null;
        }

        // Encode the text for the URL
        $text = urlencode($text);

        // Build the URL for the Google Translate API
        $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=$sourceLang&tl=$targetLang&dt=t&q=$text";

        // Get the JSON response from the API
        $response = file_get_contents($url);

        // Decode the JSON and extract the translated text
        $translatedText = json_decode($response)[0][0][0];

        // Return the translated text
        return $translatedText;
    }

    /**
     * Format a size string with a space and uppercase letters
     *
     * @param string $size The size string to format
     * @return string The formatted size string
     * @throws Exception If the size string is invalid
     */
    public function format_size(string $size): string
    {
        // Check if the input string is valid
        if (empty($size)) {
            throw new Exception("The size string cannot be empty.");
        }
        if (!preg_match("/[a-zA-Z]/", $size)) {
            throw new Exception("The size string must contain at least one letter.");
        }

        // Find the position of the first letter in the string
        $pos = strcspn($size, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");

        // Split the string into digits and letters
        $digits = substr($size, 0, $pos);
        $letters = substr($size, $pos);

        // Remove everything after kb, mb, gb or tb
        $units = ["kb", "mb", "gb", "tb"];
        foreach ($units as $unit) {
            if (stripos($letters, $unit) !== false) {
                $letters = substr($letters, 0, stripos($letters, $unit) + 2);
                break;
            }
        }

        // Return the formatted string with a space and uppercase letters
        return trim(strtoupper($digits . " " . $letters));
    }


    /**
     * Undocumented function
     *
     * @param [type] $dir
     * @return mixed
     */
    public function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    /**
     * Summary of copyDirectory
     * @param mixed $src
     * @param mixed $dst
     * @return void
     */
    public function copyDirectory($src, $dst)
    {
        $dir = opendir($src);
        mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->copyDirectory($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
        return;
    }



    /**
     * Convert an ISO 3166-1 alpha-3 code to an English short name
     *
     * @param string $code The ISO 3166-1 alpha-3 code
     * @return string The English short name or the original code if not found
     */
    public function iso_to_name_func($code)
    {
        // An array that maps ISO 3166-1 alpha-3 codes to English short names
        $iso_to_name = array(
            'AFG' => 'Afghanistan',
            'ALB' => 'Albania',
            'DZA' => 'Algeria',
            'ASM' => 'American Samoa',
            '\\AND\\' => 'Andorra',
            'AGO' => 'Angola',
            'AIA' => 'Anguilla',
            'ATA' => 'Antarctica',
            'ATG' => 'Antigua and Barbuda',
            'ARG' => 'Argentina',
            'ARM' => 'Armenia',
            'ABW' => 'Aruba',
            'AUS' => 'Australia',
            'AUT' => 'Austria',
            'AZE' => 'Azerbaijan',
            'BHS' => 'Bahamas',
            'BHR' => 'Bahrain',
            'BGD' => 'Bangladesh',
            'BRB' => 'Barbados',
            'BLR' => 'Belarus',
            'BEL' => 'Belgium',
            'BLZ' => 'Belize',
            'BEN' => 'Benin',
            'BMU' => 'Bermuda',
            'BTN' => 'Bhutan',
            'BOL' => 'Bolivia',
            'BIH' => 'Bosnia and Herzegovina',
            'BWA' => 'Botswana',
            'BVT' => 'Bouvet Island',
            'BRA' => 'Brazil',
            'IOT' => 'British Indian Ocean Territory',
            'BRN' => 'Brunei',
            'BGR' => 'Bulgaria',
            'BFA' => 'Burkina Faso',
            'BDI' => 'Burundi',
            'KHM' => 'Cambodia',
            'CMR' => 'Cameroon',
            'CAN' => 'Canada',
            'CPV' => 'Cape Verde',
            'CYM' => 'Cayman Islands',
            'CAF' => 'Central African Republic',
            'TCD' => 'Chad',
            'CHL' => 'Chile',
            'CHN' => 'China',
            'CXR' => 'Christmas Island',
            'CCK' => 'Cocos (Keeling) Islands',
            'COL' => 'Colombia',
            'COM' => 'Comoros',
            'COG' => 'Congo',
            'COD' => 'Congo, the Democratic Republic of the',
            'COK' => 'Cook Islands',
            'CRI' => 'Costa Rica',
            'CIV' => 'Ivory Coast',
            'HRV' => 'Croatia',
            'CUB' => 'Cuba',
            'CYP' => 'Cyprus',
            'CZE' => 'Czech Republic',
            'DNK' => 'Denmark',
            'DJI' => 'Djibouti',
            'DMA' => 'Dominica',
            'DOM' => 'Dominican Republic',
            'ECU' => 'Ecuador',
            'EGY' => 'Egypt',
            'SLV' => 'El Salvador',
            'GNQ' => 'Equatorial Guinea',
            'ERI' => 'Eritrea',
            'EST' => 'Estonia',
            'ETH' => 'Ethiopia',
            'FLK' => 'Falkland Islands (Malvinas)',
            'FRO' => 'Faroe Islands',
            'FJI' => 'Fiji',
            'FIN' => 'Finland',
            'FRA' => 'France',
            'GUF' => 'French Guiana',
            'PYF' => 'French Polynesia',
            'ATF' => 'French Southern Territories',
            'GAB' => 'Gabon',
            'GMB' => 'Gambia',
            'GEO' => 'Georgia',
            'DEU' => 'Germany',
            'GHA' => 'Ghana',
            'GIB' => 'Gibraltar',
            'GRC' => 'Greece',
            'GRL' => 'Greenland',
            'GRD' => 'Grenada',
            'GLP' => 'Guadeloupe',
            'GUM' => 'Guam',
            'GTM' => 'Guatemala',
            'GGY' => 'Guernsey',
            'GIN' => 'Guinea',
            'GNB' => 'Guinea-Bissau',
            'GUY' => 'Guyana',
            'HTI' => 'Haiti',
            'HMD' => 'Heard Island and McDonald Islands',
            'VAT' => 'Holy See (Vatican City State)',
            'HND' => 'Honduras',
            'HKG' => 'Hong Kong',
            'HUN' => 'Hungary',
            'ISL' => 'Iceland',
            'IND' => 'India',
            'IDN' => 'Indonesia',
            'IRN' => 'Iran, Islamic Republic of',
            'IRQ' => 'Iraq',
            'IRL' => 'Ireland',
            'IMN' => 'Isle of Man',
            'ISR' => 'Israel',
            'ITA' => 'Italy',
            'JAM' => 'Jamaica',
            'JPN' => 'Japan',
            'JEY' => 'Jersey',
            'JOR' => 'Jordan',
            'KAZ' => 'Kazakhstan',
            'KEN' => 'Kenya',
            'KIR' => 'Kiribati',
            'PRK' => 'Korea, Democratic People\'s Republic of',
            'KOR' => 'South Korea',
            'KWT' => 'Kuwait',
            'KGZ' => 'Kyrgyzstan',
            'LAO' => 'Lao People\'s Democratic Republic',
            'LVA' => 'Latvia',
            'LBN' => 'Lebanon',
            'LSO' => 'Lesotho',
            'LBR' => 'Liberia',
            'LBY' => 'Libya',
            'LIE' => 'Liechtenstein',
            'LTU' => 'Lithuania',
            'LUX' => 'Luxembourg',
            'MAC' => 'Macao',
            'MKD' => 'Macedonia, the former Yugoslav Republic of',
            'MDG' => 'Madagascar',
            'MWI' => 'Malawi',
            'MYS' => 'Malaysia',
            'MDV' => 'Maldives',
            'MLI' => 'Mali',
            'MLT' => 'Malta',
            'MHL' => 'Marshall Islands',
            'MTQ' => 'Martinique',
            'MRT' => 'Mauritania',
            'MUS' => 'Mauritius',
            'MYT' => 'Mayotte',
            'MEX' => 'Mexico',
            'FSM' => 'Micronesia, Federated States of',
            'MDA' => 'Moldova, Republic of',
            'MCO' => 'Monaco',
            'MNG' => 'Mongolia',
            'MNE' => 'Montenegro',
            'MSR' => 'Montserrat',
            'MAR' => 'Morocco',
            'MOZ' => 'Mozambique',
            'MMR' => 'Burma',
            'NAM' => 'Namibia',
            'NRU' => 'Nauru',
            'NPL' => 'Nepal',
            'NLD' => 'Netherlands',
            'ANT' => 'Netherlands Antilles',
            'NCL' => 'New Caledonia',
            'NZL' => 'New Zealand',
            'NIC' => 'Nicaragua',
            'NER' => 'Niger',
            'NGA' => 'Nigeria',
            'NIU' => 'Niue',
            'NFK' => 'Norfolk Island',
            'MNP' => 'Northern Mariana Islands',
            'NOR' => 'Norway',
            'OMN' => 'Oman',
            'PAK' => 'Pakistan',
            'PLW' => 'Palau',
            'PSE' => 'Palestinian Territory, Occupied',
            'PAN' => 'Panama',
            'PNG' => 'Papua New Guinea',
            'PRY' => 'Paraguay',
            'PER' => 'Peru',
            'PHL' => 'Philippines',
            'PCN' => 'Pitcairn',
            'POL' => 'Poland',
            'PRT' => 'Portugal',
            'PRI' => 'Puerto Rico',
            'QAT' => 'Qatar',
            'REU' => 'Réunion',
            'ROU' => 'Romania',
            'RUS' => 'Russia',
            'RWA' => 'Rwanda',
            'SHN' => 'Saint Helena, Ascension and Tristan da Cunha',
            'KNA' => 'Saint Kitts and Nevis',
            'LCA' => 'Saint Lucia',
            'SPM' => 'Saint Pierre and Miquelon',
            'VCT' => 'St. Vincent and the Grenadines',
            'WSM' => 'Samoa',
            'SMR' => 'San Marino',
            'STP' => 'Sao Tome and Principe',
            'SAU' => 'Saudi Arabia',
            'SEN' => 'Senegal',
            'SRB' => 'Serbia',
            'SYC' => 'Seychelles',
            'SLE' => 'Sierra Leone',
            'SGP' => 'Singapore',
            'SVK' => 'Slovakia',
            'SVN' => 'Slovenia',
            'SLB' => 'Solomon Islands',
            'SOM' => 'Somalia',
            'ZAF' => 'South Africa',
            'SGS' => 'South Georgia and the South Sandwich Islands',
            'SSD' => 'South Sudan',
            'ESP' => 'Spain',
            'LKA' => 'Sri Lanka',
            'SDN' => 'Sudan',
            'SUR' => 'Suriname',
            'SJM' => 'Svalbard and Jan Mayen',
            'SWZ' => 'Swaziland',
            'SWE' => 'Sweden',
            'CHE' => 'Switzerland',
            'SYR' => 'Syrian Arab Republic',
            'TWN' => 'Taiwan',
            'TJK' => 'Tajikistan',
            'TZA' => 'Tanzania, United Republic of',
            'THA' => 'Thailand',
            'TLS' => 'Timor-Leste',
            'TGO' => 'Togo',
            'TKL' => 'Tokelau',
            'TON' => 'Tonga',
            'TTO' => 'Trinidad and Tobago',
            'TUN' => 'Tunisia',
            'TUR' => 'Turkey',
            'TKM' => 'Turkmenistan',
            'TCA' => 'Turks and Caicos Islands',
            'TUV' => 'Tuvalu',
            'UGA' => 'Uganda',
            'UKR' => 'Ukraine',
            'ARE' => 'United Arab Emirates',
            'GBR' => 'United Kingdom',
            'USA' => 'United States',
            'UMI' => 'United States Minor Outlying Islands',
            'URY' => 'Uruguay',
            'UZB' => 'Uzbekistan',
            'VUT' => 'Vanuatu',
            'VEN' => 'Venezuela',
            'VNM' => 'Vietnam',
            'VGB' => 'Virgin Islands, British',
            'VIR' => 'Virgin Islands, U.S.',
            'WLF' => 'Wallis and Futuna',
            'ESH' => 'Western Sahara',
            'YEM' => 'Yemen',
            'ZMB' => 'Zambia',
            'ZWE' => 'Zimbabwe',

            'SCA' => 'Scandinavian',
            'SWE/FIN' => 'Sweden/Finland'
        );

        // Check if the code is valid and in the array
        if ($code && isset($iso_to_name[$code])) {
            // Return the corresponding name
            return $iso_to_name[$code];
        }

        // Return the original code if not found
        return $code;
    }

}