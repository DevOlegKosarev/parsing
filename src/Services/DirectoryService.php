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
use Devolegkosarev\Parsing\Interfaces\DirectoryServiceInterface;

/**
 *
 * Service for managing directories.
 *
 */
class DirectoryService
{
    /**
     * Recursive deletion of a directory.
     *
     * @param string $directoryPath The path of the directory to delete.
     *
     * @return void
     */
    public function deleteDirectory(string $directoryPath): void
    {
        if (!is_dir($directoryPath)) {
            return;
        }

        $files = scandir($directoryPath);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $filePath = $directoryPath . '/' . $file;
            if (is_dir($filePath)) {
                $this->deleteDirectory($filePath);
            } else {
                unlink($filePath);
            }
        }

        rmdir($directoryPath);
    }

    /**
     * Create a copy of a directory in another directory.
     *
     * @param string $sourcePath The path of the source directory.
     * @param string $targetPath The path of the target directory.
     *
     * @return bool
     */
    public function mirrorDirectory(string $sourcePath, string $targetPath): bool
    {
        if (!is_dir($sourcePath)) {
            return false;
        }

        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0777, true);
        }

        $files = scandir($sourcePath);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $sourceFilePath = $sourcePath . '/' . $file;
            $targetFilePath = $targetPath . '/' . $file;

            if (is_file($sourceFilePath)) {
                // Check if the target file already exists and delete it
                if (file_exists($targetFilePath)) {
                    unlink($targetFilePath);
                }

                copy($sourceFilePath, $targetFilePath);
            } elseif (is_dir($sourceFilePath)) {
                $this->mirrorDirectory($sourceFilePath, $targetFilePath);
            }
        }

        return true;
    }

    /**
     * Copy a CSV file to a destination
     *
     * @param string $sourcePath The path to the source CSV file
     * @param string $destinationPath The path to the destination CSV file
     * @return bool True if the file was copied successfully, false otherwise
     */
    public function copy(string $sourcePath, string $destinationPath): bool
    {
        // Check if the source file exists
        if (!file_exists($sourcePath)) {
            // Create an empty file
            file_put_contents($sourcePath, '');

            // Return false only if the file was not created
            throw new Exception('The source file does not exist: ' . $sourcePath);
        }

        // Try to copy the source file to the destination file
        $isCopied = copy($sourcePath, $destinationPath);

        if (!$isCopied) {
            throw new Exception('Failed to copy the file: ' . $sourcePath);
        }

        // Return true
        return true;
    }
}