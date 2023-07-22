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

use Devolegkosarev\Parsing\CsvGenerator;
use Devolegkosarev\Parsing\Config\Database;
use Devolegkosarev\Parsing\Models\iDataSyncModel;
use Devolegkosarev\Parsing\Models\iDataVendorModel;
use Devolegkosarev\Parsing\Models\iDataLanguageModel;
use Devolegkosarev\Parsing\Services\DirectoryService;
use Devolegkosarev\Parsing\Services\DiffCSVService;
use Devolegkosarev\Parsing\Interfaces\VendorsInterface;
use Devolegkosarev\Parsing\Models\iDataPresetStatesModel;
use Devolegkosarev\Parsing\Services\AliasGeneratorService;
use Devolegkosarev\Parsing\Services\iDataPresetInsertService;
use Devolegkosarev\Parsing\Libraries\MarkupCalculatorCoreLibrary;

/**
 * Class AbstractVendors
 *
 * This class provides a base implementation for the VendorsInterface.
 * It includes the instantiation of required libraries.
 */
abstract class AbstractVendors implements VendorsInterface
{
    /**
     * The diff csv libraries instance.
     *
     * @var DiffCSVService
     */
    protected static $DiffCSVService;

    /**
     * The markup calculator core library instance.
     *
     * @var MarkupCalculatorCoreLibrary
     */
    protected $markupCalculatorCoreLibrary;

    /**
     * The csv generator instance.
     *
     * @var CsvGenerator
     */
    protected static $CsvGenerator;

    /**
     * The Alias Generator instance.
     *
     * @var AliasGeneratorService
     */
    protected static $AliasGeneratorService;
    /**
     * The Alias Generator instance.
     *
     * @var iDataPresetInsertService
     */
    protected static $iDataPresetInsertService;

    /**
     * The iDataSyncModel instance.
     *
     * @var iDataSyncModel
     */
    protected $iDataSyncModel;

    /**
     * The iDataVendorModel instance.
     *
     * @var iDataVendorModel
     */
    protected $iDataVendorModel;

    /**
     * The iDataPresetStatesModel instance.
     *
     * @var iDataPresetStatesModel
     */
    protected $iDataPresetStatesModel;

    /**
     * @var \CodeIgniter\Database\BaseConnection $dbIData The database connection object for iData group
     */
    protected \CodeIgniter\Database\BaseConnection $dbIData;

    protected array $getAllLanguages;
    protected static DirectoryService $DirectoryService;

    /**
     * AbstractVendors constructor.
     */
    public function __construct()
    {
        $this->dbIData = \Config\Database::connect(Database::iData());
        $this->iDataSyncModel = new iDataSyncModel($this->dbIData);
        $this->iDataVendorModel = new iDataVendorModel($this->dbIData);

        $this->iDataPresetStatesModel = new iDataPresetStatesModel($this->dbIData);
        $languages = new iDataLanguageModel($this->dbIData);

        $this->getAllLanguages = $languages->getAllLanguages();


        $this->markupCalculatorCoreLibrary = new MarkupCalculatorCoreLibrary();

        if (!isset(self::$CsvGenerator)) {
            self::$CsvGenerator = new CsvGenerator($this->getAllLanguages);
        }

        if (!isset(self::$iDataPresetInsertService)) {
            self::$iDataPresetInsertService = new iDataPresetInsertService($this->getAllLanguages, $this->getCsvGenerator()->getHeaders(), $this->dbIData);
        }

        if (!isset(self::$AliasGeneratorService)) {
            self::$AliasGeneratorService = new AliasGeneratorService();
        }

        if (!isset(self::$DirectoryService)) {
            self::$DirectoryService = new DirectoryService();
        }


        // Check if the dependencies are instantiated
        if (!isset(self::$DiffCSVService)) {
            self::$DiffCSVService = new DiffCSVService($this->getAllLanguages, $this->getCsvGenerator()->getHeaders());
        }

    }


    protected function getiDataPresetInsertService(): iDataPresetInsertService
    {
        return self::$iDataPresetInsertService;
    }


    /**
     * Gets the diff csv libraries instance.
     *
     * @return DiffCSVService The diff csv libraries instance.
     */
    protected function getDiffCSVService(): DiffCSVService
    {
        return self::$DiffCSVService;
    }


    /**
     * Summary of getDirectoryService
     * @return DirectoryService
     */
    protected function getDirectoryService(): DirectoryService
    {
        return self::$DirectoryService;
    }

    /**
     * Gets the csv generator instance.
     *
     * @return CsvGenerator The csv generator instance.
     */
    protected function getCsvGenerator(): CsvGenerator
    {
        return self::$CsvGenerator;
    }

    /**
     * Gets the Alias Generator instance.
     *
     * @return AliasGeneratorService The csv generator instance.
     */
    protected function getAliasGeneratorService(): AliasGeneratorService
    {
        return self::$AliasGeneratorService;
    }
}