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

use Exception;
use Devolegkosarev\Parsing\Interfaces\TranslaterInterface;
use Devolegkosarev\Parsing\Interfaces\TranslatorInterface;

abstract class AbstractTranslater implements TranslaterInterface
{

    /**
     * @var array The translations array
     */
    protected array $Translations = [];

    /**
     * @var string The path to the translations file
     */
    protected string $path;

    /**
     * @var TranslatorInterface The instance of the translator service
     */
    protected ?TranslatorInterface $translator;

    /**
     * @var string The language code
     */
    protected string $language;

    /**
     * Constructor
     *
     * @param string $lang The language code
     * @param string $fileName The name of the translations file
     * @throws Exception If the vendor directory or the package directory does not exist
     */
    public function __construct(string $language, string $fileName, ?TranslatorInterface $translator = null)
    {
        $this->path = '';
        $this->language = $language;

        $basePath = FCPATH . 'vendor' . DIRECTORY_SEPARATOR . 'devolegkosarev';
        $vendorPackagePath = $basePath . DIRECTORY_SEPARATOR . 'parsing' . DIRECTORY_SEPARATOR . 'src';
        $langPath = $vendorPackagePath . DIRECTORY_SEPARATOR . 'Language' . DIRECTORY_SEPARATOR . $language;
        if (is_dir($langPath) === false) {
            mkdir($langPath);
        }

        $this->path = $langPath . DIRECTORY_SEPARATOR . $fileName . '.php';
        $this->loadTranslations();
    }


    /**
     * Load translations from the file.
     *
     * @return void
     *
     * @throws Exception If the file does not exist, is not readable, or does not contain a valid array.
     */
    protected function loadTranslations(): void
    {
        if (!file_exists($this->path)) {
            $this->createEmptyTranslationsFile();
        }

        if (!is_readable($this->path)) {
            throw new Exception("File is not readable: " . $this->path);
        }

        $data = include $this->path;

        if (!is_array($data)) {
            throw new Exception("File does not contain a valid array: " . $this->path);
        }

        $this->Translations = $data;
    }

    protected function createEmptyTranslationsFile(): void
    {
        if (!file_put_contents($this->path, '<?php return [];')) {
            throw new Exception("Failed to create translations file: " . $this->path);
        }
    }

    protected function saveTranslations(): void
    {
        $data = '<?php return ' . var_export($this->Translations, true) . ';';

        if (file_put_contents($this->path, $data) === false) {
            throw new Exception('Failed to save translations to: ' . $this->path);
        }
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
        // Load the translations if not loaded yet
        if (count($this->Translations) <= 0) {
            $this->loadTranslations();
        }

        // Check if the translation exists
        if (!isset($this->Translations[$key])) {
            // Add the translation to the array and save it to the file if not found
            $this->add($key, $key);
        }

        // Return the translation if found or null otherwise
        return $this->Translations[$key];
    }

    /**
     * Add a new translation to the translations array and save it to the file.
     *
     * @param string      $key   The translation key.
     * @param string|null $value The translation value. If null, use the key as the value.
     * @return string The added translation value.
     */
    public function add(string $key, ?string $value = null): string
    {
        // Load the translations if not loaded yet
        if (count($this->Translations) <= 0) {
            $this->loadTranslations();
        }

        // Use the key as the value if no value is given
        $value = $value ?? $key;

        // Add the translation to the array and save it to the file
        if (!isset($this->Translations[$key])) {
            $this->Translations[$key] = $this->translator->translate($key, $this->language, "en");
            ;
            $this->saveTranslations();
        }

        // Return the added value
        return $value;
    }

    /**
     * Remove a translation from the translations array and save the changes to the file.
     *
     * @param string $key The translation key.
     * @return bool True if the translation was successfully removed, false otherwise.
     */
    public function remove(string $key): bool
    {
        // Load the translations if not loaded yet
        if (count($this->Translations) <= 0) {
            $this->loadTranslations();
        }

        // Check if the translation exists
        if (isset($this->Translations[$key])) {
            // Remove the translation from the array and save it to the file
            unset($this->Translations[$key]);
            $this->saveTranslations();

            // Return true to indicate success
            return true;
        }

        // Return false to indicate failure
        return false;
    }

    /**
     * Update a translation in the translations array and save the changes to the file.
     *
     * @param string      $key   The translation key.
     * @param string|null $value The translation value. If null, use the key as the value.
     *
     * @return string The updated or added translation value.
     */
    public function update(string $key, ?string $value = null): string
    {
        // Load the translations if not loaded yet
        if (count($this->Translations) <= 0) {
            $this->loadTranslations();
        }

        // Use the key as the value if no value is given
        $value = $value ?? $key;

        // Check if the translation exists
        if (isset($this->Translations[$key])) {
            // Update the translation in the array and save it to the file if it is different from the previous value
            if ($this->Translations[$key] !== $value) {
                $this->Translations[$key] = $value;
                $this->saveTranslations();
            }

            // Return the updated value
            return $value;
        } else {
            // Add a new translation if the key is not found
            return $this->add($key, $value);
        }
    }


}