<?php
/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @package Devolegkosarev\Parsing\Services
 * @author     Oleg Kosarev <dev.oleg.kosarev@outlook.com>
 * @version    0.0.1
 * @since      0.0.1
 */

namespace Devolegkosarev\Parsing\Services;

use Exception;
use Devolegkosarev\Parsing\Interfaces\TranslatorInterface;

/**
 * 
 * A service class that provides translation methods using different APIs
 */
class TranslationService implements TranslatorInterface
{
    /**
     * @var string The name of the translation service to use
     */
    private string $service;

    /**
     * TranslationService constructor.
     * @param string $service The name of the translation service to use. Possible values: "Google", "Bing"
     */
    public function __construct(string $service)
    {
        $this->service = $service;
    }


    /**
     * Translate a given text from one language to another using the selected service
     * @param string $key The text to translate
     * @param string $toLang The target language code
     * @param string $fromLang The source language code (default: 'en')
     * @return string The translated text
     * @throws Exception If the text is empty, the target language is invalid or the service is unknown
     */
    public function translate(string $key, string $toLang, string $fromLang = 'en'): string
    {

        // Check if the text is provided
        if (empty($key) === true) {
            throw new Exception("Text to translate not set");
        }

        // Check if the target language is provided and different from 'en'
        if ($toLang == null or !in_array($toLang, ['en'])) {
            // Check the name of the translation service
            switch ($this->service) {
                // If it is Google, use the googleTranslateApi method to get the translation
                case "Google":
                    return $this->googleTranslateApi($key, $toLang, $fromLang);
                // If it is Bing, use the bingTranslateApi method to get the translation
                case "Bing":
                    return $this->bingTranslateApi($key, $toLang, $fromLang);
                // If it is something else, throw an exception or return null
                default:
                    throw new Exception("Unknown translation service");
            }
        } else {
            return $key;
        }
    }

    /**
     * Translate a given text from one language to another using the Google Translate API
     * @param string $text The text to translate
     * @param string $sourceLang The source language code
     * @param string $targetLang The target language code
     * @return string The translated text
     */
    private function googleTranslateApi(string $text, string $sourceLang, string $targetLang): string
    {
        // Encode the text for the URL
        $text = urlencode($text);

        // Build the URL for the Google Translate API
        $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=$targetLang&tl=$sourceLang&dt=t&q=$text";

        // Get the JSON response from the API
        $response = file_get_contents($url);

        // Decode the JSON and extract the translated text
        $translation = json_decode($response)[0][0][0];

        // Return the translated text
        return $translation;
    }


    /**
     * Translate a given text from one language to another using the Bing Translate API
     * @param string $key The text to translate
     * @param string $toLang The target language code
     * @param string $fromLang The source language code (default: 'en')
     * @return string The translated text
     * @throws Exception If the service is not implemented yet
     */
    private function bingTranslateApi(string $key, string $toLang, string $fromLang): string
    {
        throw new Exception("WIP (work-in-progress) translation service");
        // return $translation;
    }

    /**
     * Set the name of the translation service to use
     * @param string $service The name of the translation service to use. Possible values: "Google", "Bing"
     * @return self The current instance of the class
     */
    public function setService(string $service): self
    {
        $this->service = $service;
        return $this;
    }
}