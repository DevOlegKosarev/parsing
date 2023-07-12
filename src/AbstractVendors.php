<?php

namespace Devolegkosarev\Parsing;

use Devolegkosarev\Parsing\Libraries\DiffCsvLibraries;
use Devolegkosarev\Parsing\Libraries\VendorsLibraries;
use Devolegkosarev\Parsing\Interfaces\VendorsInterface;
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
     * @var DiffCsvLibraries The library for diffing CSV files.
     */
    protected $diffCsvLibraries;

    /**
     * @var VendorsLibraries The library for interacting with vendors.
     */
    protected $vendorsLibraries;

    /**
     * @var MarkupCalculatorCoreLibrary The core library for calculating markup.
     */
    protected $markupCalculatorCoreLibrary;

    /**
     * AbstractVendors constructor.
     */
    public function __construct()
    {
        $this->diffCsvLibraries = new DiffCsvLibraries();
        $this->vendorsLibraries = new VendorsLibraries();
        $this->markupCalculatorCoreLibrary = new MarkupCalculatorCoreLibrary();
    }

    /**
     * Get the DiffCsvLibraries instance.
     *
     * @return DiffCsvLibraries The DiffCsvLibraries instance.
     */
    protected function getDiffCsvLibraries(): DiffCsvLibraries
    {
        return $this->diffCsvLibraries;
    }

    /**
     * Get the VendorsLibraries instance.
     *
     * @return VendorsLibraries The VendorsLibraries instance.
     */
    protected function getVendorsLibraries(): VendorsLibraries
    {
        return $this->vendorsLibraries;
    }

    /**
     * Get the MarkupCalculatorCoreLibrary instance.
     *
     * @return MarkupCalculatorCoreLibrary The MarkupCalculatorCoreLibrary instance.
     */
    protected function getMarkupCalculatorCoreLibrary(): MarkupCalculatorCoreLibrary
    {
        return $this->markupCalculatorCoreLibrary;
    }
}