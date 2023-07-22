<?php
/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @package    Devolegkosarev\Parsing\Interfaces
 * @author     Oleg Kosarev <dev.oleg.kosarev@outlook.com>
 * @version    0.0.1
 * @since      0.0.1
 */


namespace Devolegkosarev\Parsing\Interfaces;

/**
 *
 * This interface defines the contract for interacting with ...
 */
interface TranslaterInterface
{
    /**
     * Get the translation for the given key.
     *
     * @param string      $key   The translation key.
     * @param string|null $value The default value if the translation key is not found.
     * @return string|null The translated string or null if the key is not found.
     */
    public function get(string $key): ?string;

    /**
     * Add a new translation to the translations array and save it to the file.
     *
     * @param string $key   The translation key.
     * @param string $value The translation value.
     * @return void
     */
    public function add(string $key, string $value): ?string;

    /**
     * Remove translation to the translations array and save it to the file.
     *
     * @param string $key   The translation key.
     * @param string $value The translation value.
     * @return bool
     */
    public function remove(string $key): bool;

    /**
     * Remove translation to the translations array and save it to the file.
     *
     * @param string $key   The translation key.
     * @param string $value The translation value.
     * @return void
     */
    public function update(string $key, string $value): string;

}