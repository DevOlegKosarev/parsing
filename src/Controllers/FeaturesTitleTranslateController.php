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

use Exception;
use Devolegkosarev\Parsing\AbstractTranslater;
use Devolegkosarev\Parsing\Services\TranslationService;
use Devolegkosarev\Parsing\Interfaces\TranslatorInterface;

/**
 * 
 * A controller class that extends the abstract options handler class
 * 
 */
class FeaturesTitleTranslateController extends AbstractTranslater
{

    /**
     * The constructor for the category translate controller class.
     *
     * @param string $vendorNamePacage The vendor name for the package.
     * @param string $lang The language code for the translations.
     */
    public function __construct(string $lang)
    {
        // Assign a value to the translator property here
        $this->translator = new TranslationService("Google");

        // Call the parent constructor with the vendor name, language code and file name for the translations
        parent::__construct($lang, 'FeaturesTitle', $this->translator);
    }
    /**
     * Get the translation for the given key.
     *
     * @param string      $key   The translation key.
     * @param string|null $value The default value if the translation key is not found.
     * @return string|null The translated string or null if the key is not found.
     */
    public function get(string $key): string
    {
        $this->loadTranslations();

        if (!isset($this->Translations[$key])) {
            $this->add($key, $key);
        }

        $translation = $this->Translations[$key][1];

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
        if ($this->Translations === null) {
            $this->loadTranslations();
        }

        $this->Translations[$key] = ["S", $this->translator->translate($key, $this->language, "en")];
        $this->saveTranslations();

        return $value;
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
     * @return void
     */
    public function update(string $key, ?string $value = null): string
    {
        throw new Exception("WIP (work-in-progress) " . __METHOD__);
    }
}