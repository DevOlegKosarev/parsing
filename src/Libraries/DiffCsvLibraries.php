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

use LogicException;

/**
 * A class that compares two CSV files and returns the differences based on a shared unique id column.
 */
class DiffCsvLibraries
{
    /**
     * Compare two CSV files and return the rows that are not present in the existing file but are present in the current file.
     * @param string $existingTranslations The path to the existing CSV file.
     * @param string $currentTranslationData The path to the current CSV file.
     * @param string $sharedUniqueId The name of the column that contains the unique id for each row.
     * @return array An associative array with keys "TotalRowsSkipped", "HideProducts" and "DiffedRows".
     * @throws LogicException If the unique id column is not found in either file.
     */
    public function diff($existingTranslations, $currentTranslationData, $sharedUniqueId)
    {
        try {
            // Open the existing file and read the unique ids into an array
            $handle = fopen($existingTranslations, 'r');

            $uniqueIds = [];
            $loop = 1;
            $uniqueIdRowIndex = null;
            while ($row = fgetcsv($handle, 0, ',')) {
                if ($loop === 1) {
                    // Find the index of the unique id column in the first row (header)
                    $uniqueIdRowIndex = $this->getIndexOf($sharedUniqueId, $row);
                    if (is_null($uniqueIdRowIndex)) {
                        throw new LogicException("Cannot find unique header {$sharedUniqueId} in {$existingTranslations}");
                    }
                }
                if ($loop > 1) {
                    // Add the unique id value to the array
                    $uniqueIds[] = $row[$uniqueIdRowIndex];
                }
                $loop++;
            }
            fclose($handle);

            // Open the current file and compare each row with the existing file
            $handle = fopen($currentTranslationData, 'r');
            $loop = 1;
            $uniqueIdRowIndex = null;
            $diffedRows = [];
            $result = [];
            $cSkipped = 0;
            while ($row = fgetcsv($handle, 0, ',')) {
                if ($loop === 1) {
                    // Find the index of the unique id column in the first row (header)
                    $uniqueIdRowIndex = $this->getIndexOf($sharedUniqueId, $row);
                    if (is_null($uniqueIdRowIndex)) {
                        throw new LogicException("Cannot find unique header {$sharedUniqueId} in {$currentTranslationData}");
                    }
                }
                if ($loop > 1) {
                    // Get the unique id and language values from the row
                    $id = $row[$uniqueIdRowIndex];
                    $Language = $row[9];
                    // Only consider rows with English language
                    if ($Language == "en") {
                        // Check if the unique id is already present in the existing file
                        if (in_array($id, $uniqueIds)) {
                            // Skip this row as it is not different
                            $cSkipped++;
                        } else {
                            // Add this row to the diffed rows array with the unique id as key
                            $diffedRows[$id] = $row;
                        }
                    }
                }
                $loop++;
            }
            fclose($handle);

            $result["TotalRowsSkipped"] = $cSkipped;
            $result["HideProducts"] = count($diffedRows);
            $result["DiffedRows"] = $diffedRows;

            return $result;
        } catch (\Throwable $th) {
            throw new LogicException($th->getMessage());
        }
    }

    /**
     * Find the index of a value in an array.
     * @param mixed $value The value to search for.
     * @param array $arrayData The array to search in.
     * @return int|null The index of the value in the array, or null if not found.
     */
    public function getIndexOf($value, $arrayData)
    {
        foreach ($arrayData as $index => $itemValue) {
            if ($itemValue === $value) {
                return $index;
            }
        }

        return null;
    }

    /**
     * Print the contents of a row for debugging purposes.
     * @param array $row The row to print.
     */
    private function dumpRow($row)
    {
        echo "Content of the row:";
        echo "\n";
        foreach ($row as $index => $value) {
            echo "[{$index}] {$value}";
            echo "\n";
        }
    }
}