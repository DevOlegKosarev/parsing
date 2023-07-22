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

use Devolegkosarev\Parsing\Libraries\DiffCsvLibraries;
use Devolegkosarev\Parsing\Controllers\OptionsHandlerController;
use Devolegkosarev\Parsing\Controllers\FeaturesHandlerController;
use Devolegkosarev\Parsing\Controllers\CategoryTranslateController;

class DiffCSVService
{
    protected $getAllLanguages;
    protected $csvHeaders;
    protected $DiffCsvLibraries;
    public function __construct(array $getAllLanguages, $csvHeaders)
    {
        var_dump($csvHeaders);
        $this->getAllLanguages = $getAllLanguages;
        $this->csvHeaders = $csvHeaders;
        $this->DiffCsvLibraries = new DiffCsvLibraries();
    }


    /**
     * Compare two CSV files and return some result
     * @param string $currentDirictoryFileCsv The path to the current CSV file
     * @param string $previousDirictoryFileCsv The path to the previous CSV file
     * @return array An associative array with keys "Status", "Total rows skipped" and "Hide Products"
     */
    public function diffCSV(string $currentDirictoryFileCsv, string $previousDirictoryFileCsv): array
    {
        // Initialize some variables to store the result of the comparison
        $status = false; // The status of the comparison (true if there are any differences, false otherwise)
        $totalRowsSkipped = 0; // The number of rows that were skipped in the comparison
        $hideProducts = 0; // The number of products that were hidden in the current file

        if (!file_exists($currentDirictoryFileCsv) || !file_exists($previousDirictoryFileCsv)) {
            return [
                "Status" => $status,
                "TotalRowsSkipped" => $totalRowsSkipped,
                "HideProducts" => $hideProducts,
            ];
        }

        // Call the diff method from the DiffCsvLibraries class and store the result in a variable
        $diffedRows = $this->DiffCsvLibraries->diff($currentDirictoryFileCsv, $previousDirictoryFileCsv, "Product code");

        // Check if there are any differences between the two files
        if (count($diffedRows["DiffedRows"]) > 0) {
            // Set the status to true
            $status = true;
            // Get the number of rows that were skipped in the comparison from the diffedRows array
            $totalRowsSkipped = $diffedRows["TotalRowsSkipped"];
            // Get the number of products that were hidden in the current file from the diffedRows array
            $hideProducts = $diffedRows["HideProducts"];
            // Open the current file in append mode
            $handle = fopen($currentDirictoryFileCsv, 'a');

            // $csvHeaders = $CsvGenerator->getHeaders();

            // Loop through each difference and process it
            foreach ($diffedRows["DiffedRows"] as $diffedRowsKey => $diffedRow) {
                // Get the index of the "Status" column in the CSV header
                $csvHeaderStatus = $this->DiffCsvLibraries->getIndexOf("Status", $this->csvHeaders);
                $csvCategoryIndex = $this->DiffCsvLibraries->getIndexOf("Category", $this->csvHeaders);
                $csvFeaturesIndex = $this->DiffCsvLibraries->getIndexOf("Features", $this->csvHeaders);
                $csvLanguageIndex = $this->DiffCsvLibraries->getIndexOf("Language", $this->csvHeaders);
                $csvOptionsIndex = $this->DiffCsvLibraries->getIndexOf("Options", $this->csvHeaders);
                // Check if the status of the difference is "A"
                if ($diffedRow[$csvHeaderStatus] == "A") {
                    // Change the status to "H"
                    $diffedRow[$csvHeaderStatus] = "H";
                    // Set the quantity to zero
                    $diffedRow[$this->DiffCsvLibraries->getIndexOf("Quantity", $this->csvHeaders)] = 0;
                    // Add the difference to the current file as a new row
                    fputcsv($handle, $diffedRow);

                    foreach ($this->getAllLanguages as $key => $language) {
                        if ($language == 'en') {
                            continue;
                        }
                        // Instantiate the CategoryTranslateController with the current language
                        $CategoryTranslateHandlerController = new CategoryTranslateController($language);

                        // Create a new copy of the $diffedRow array at the beginning of each iteration
                        $newDiffedRow = array_merge([], $diffedRow);

                        // Parse the features using the FeaturesHandlerController and update the corresponding index in $newDiffedRow
                        if (!empty($newDiffedRow[$csvFeaturesIndex])) {
                            // Instantiate the FeaturesHandlerController with the current language
                            $FeaturesHandlerController = new FeaturesHandlerController($language);
                            $newDiffedRow[$csvFeaturesIndex] = $FeaturesHandlerController->parseFeatures($newDiffedRow[$csvFeaturesIndex]);
                        }

                        if ($newDiffedRow[$csvOptionsIndex]) {
                            $OptionsHandlerController = new OptionsHandlerController();
                            $newDiffedRow[$csvOptionsIndex] = $OptionsHandlerController->extractNamesOptions((string) $newDiffedRow[$csvOptionsIndex], $language);
                        }

                        // Split the category string into an array
                        $categoryArray = explode("///", (string) $newDiffedRow[$csvCategoryIndex]);

                        // Translate each category using the CategoryTranslateHandlerController
                        $categoryArrayTranslation = array_map([$CategoryTranslateHandlerController, 'get'], $categoryArray);

                        // Join the translated categories back into a string using "///" as the separator
                        $newDiffedRow[$csvCategoryIndex] = implode("///", $categoryArrayTranslation);

                        $newDiffedRow[$csvLanguageIndex] = $language;
                        // Write the updated row to the file using fputcsv
                        fputcsv($handle, $newDiffedRow);
                    }

                }
            }
            // Close the file handle
            fclose($handle);
        }
        // Return an associative array with keys "Status", "Total rows skipped" and "Hide Products"
        return [
            "Status" => $status,
            "TotalRowsSkipped" => $totalRowsSkipped,
            "HideProducts" => $hideProducts,
        ];
    }

    // Other methods and dependencies...
}