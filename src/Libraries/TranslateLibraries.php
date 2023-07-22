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

use CodeIgniter\Cache\CacheInterface;
use Config\Services;

/**
 *
 * This class provides translation functionality by loading translations from a file and retrieving translations
 * based on a given key. It also allows adding new translations and saving them back to the file.
 */
class TranslateLibraries
{
    /**
     * The array that holds the loaded translations.
     *
     * @var array
     */
    private static array $Translations = [];

    /**
     * The file path for translations.
     *
     * @var string
     */
    private static string $path;

    private static string $basePath = 'C:' . DIRECTORY_SEPARATOR . 'laragon' . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'parsing_dev' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'devolegkosarev' . DIRECTORY_SEPARATOR . 'foxway' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Language';

    /**
     * The cache instance.
     *
     * @var CacheInterface|null
     */
    private static ?CacheInterface $cache = null;

    /**
     * Set the base path and language file name for translations.
     *
     * @param string $lang The base path for translations.
     * @param string $fileName The language file name.
     * @return void
     */
    public static function setPath(string $lang, string $fileName): void
    {
        self::$path = self::$basePath . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . $fileName . '.php';
    }

    /**
     * Get the translation for the given key.
     *
     * @param string      $key   The translation key.
     * @param string|null $value The default value if the translation key is not found.
     * @return string|null The translated string or null if the key is not found.
     * @throws \Exception If the translation file does not exist.
     */
    public static function getFeaturesTitle(string $key, ?string $value = null): ?string
    {
        self::loadTranslations();

        if (!isset(self::$Translations[$key])) {
            self::addTranslationFeaturesTitle($key, $value ?? $key);
        }

        $translation = self::$Translations[$key][1];

        return $translation;

    }

    public static function getFeaturesValue(string $key, ?string $value = null): ?string
    {
        self::loadTranslations();
    }

    public static function getCategory(string $key, ?string $value = null): ?string
    {
        var_dump(__FUNCTION__);
        self::loadTranslations();

        if (!isset(self::$Translations[$key])) {
            self::addTranslationCategory($key, $value ?? $key);
        }

        $translation = self::$Translations[$key];

        return $translation;
    }

    public static function getOptionsTitle(string $key, ?string $value = null): ?string
    {
        self::loadTranslations();

    }

    public static function getOptionsValue(string $key, ?string $value = null): ?string
    {
        self::loadTranslations();

    }

    /**
     * Load translations from the file.
     *
     * @return void
     */
    private static function loadTranslations(): void
    {
        if (!empty(self::$Translations)) {
            return;
        }
        if (file_exists(self::$path)) {
            self::$Translations = include(self::$path);
        } else {
            throw new \Exception("File does not exist");
        }


    }

    /**
     * Save translations to the file.
     *
     * @return void
     */
    private static function saveTranslations(): void
    {
        $data = var_export(self::$Translations, true);
        $string = '<?php return ' . $data . ';';
        file_put_contents(self::$path, $string);
    }

    /**
     * Add a new translation to the translations array and save it to the file.
     *
     * @param string $key   The translation key.
     * @param string $value The translation value.
     * @return void
     */
    private static function addTranslationFeaturesTitle(string $key, string $value): void
    {
        self::$Translations[$key] = ["S", $value];
        self::saveTranslations();
    }

    /**
     * Add a new translation to the translations array and save it to the file.
     *
     * @param string $key   The translation key.
     * @param string $value The translation value.
     * @return void
     */
    private static function addTranslationCategory(string $key, string $value): void
    {
        self::$Translations[$key] = $value;
        self::saveTranslations();
    }

    //addTranslationCategory
}