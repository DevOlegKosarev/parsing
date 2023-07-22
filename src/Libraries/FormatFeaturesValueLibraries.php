<?php
/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @package    Devolegkosarev\Parsing\Libraries
 * @author     Oleg Kosarev <dev.oleg.kosarev@outlook.com>
 * @version    0.0.1
 * @since      0.0.1
 */

namespace Devolegkosarev\Parsing\Libraries;

use Exception;

/**
 * A library class for formatting features and values.
 */
class FormatFeaturesValueLibraries
{
    public function __construct()
    {
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
        $digits = rtrim(substr($size, 0, $pos));
        $letters = substr($size, $pos);

        // Return the formatted string with a space and uppercase letters
        return trim(strtoupper($digits . " " . $letters));
    }

    function format_os($element): string
    {
        // Define an associative array of OS editions and their full names
        $edition_array = array(
            "sta" => "Starter",
            "home" => "Home",
            "hb" => "Home Basic",
            "hp" => "Home Premium",
            "pro" => "Professional",
            "ent" => "Enterprise",
            "ult" => "Ultimate",
            "edu" => "Education",
            "pfw" => "Pro for Workstations",
            "rt" => "RT",
            "chn" => "China",
            "mce" => "Media Center Edition",
            "tpe" => "Tablet PC Edition"
        );

        // Convert the element to lowercase and remove any trailing spaces
        $lower_element = trim(strtolower($element));

        // Remove 'installed' from the element if it exists
        $lower_element = str_replace(" installed", "", $lower_element);

        // Remove 'win' from the element if it exists
        $lower_element = preg_replace("/^win\s*/", "", $lower_element);

        // Split the element into parts by space
        $parts = explode(" ", $lower_element);

        // Check if the second part is in the edition array and replace it with the full name
        if (isset($parts[1]) && isset($edition_array[$parts[1]])) {
            $parts[1] = $edition_array[$parts[1]];
        }

        // Return the element with 'Windows' added at the beginning
        return "Windows " . implode(" ", $parts);
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