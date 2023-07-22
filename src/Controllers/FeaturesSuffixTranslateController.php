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

use Devolegkosarev\Parsing\AbstractTranslater;
use Devolegkosarev\Parsing\Services\TranslationService;
use Devolegkosarev\Parsing\Interfaces\TranslatorInterface;
use Exception;

/**
 * 
 * A controller class that extends the abstract options handler class
 * 
 */
class FeaturesSuffixTranslateController extends AbstractTranslater
{

    /**
     * The constructor for the category translate controller class.
     *
     * @param string $lang The language code for the translations.
     */
    public function __construct(string $lang)
    {
        $this->translator = new TranslationService("Google");

        // Call the parent constructor with the vendor name, language code and file name for the translations
        parent::__construct($lang, 'FeaturesTitle');
    }
    /**
     * Get the translation for the given key.
     *
     * @param string      $key   The translation key.
     * @param string|null $value The default value if the translation key is not found.
     * @return string The translated string or null if the key is not found.
     */
    public function get(string $key): string
    {
        $this->loadTranslations();

        if (!isset($this->Translations[$key])) {
            throw new Exception("Suffix $key requires a title to be initialized. Please call FeaturesTitleTranslateController->get($key) first.");
        }

        $translation = $this->Translations[$key][0];

        return $translation;

    }

    /**
     * Add a new translation to the translations array and save it to the file.
     *
     * @param string $key   The translation key.
     * @param string $value The translation value.
     * @return string
     */
    public function add(string $key, ?string $value = null): string
    {
        return parent::add($key, $value);
    }

    /**
     * Remove translation to the translations array and save it to the file.
     *
     * @param string $key The translation key.
     * @return bool
     */
    public function remove(string $key): bool
    {
        throw new Exception("WIP (work-in-progress) " . __METHOD__);
    }

    /**
     * Remove translation to the translations array and save it to the file.
     *
     * @param string $key The translation key.
     * @param string $value The translation value.
     * @return string
     */
    public function update(string $key, ?string $value = null): string
    {
        throw new Exception("WIP (work-in-progress) " . __METHOD__);
    }
}