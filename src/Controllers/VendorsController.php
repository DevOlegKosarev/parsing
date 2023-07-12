<?php
// Declare the namespace for this controller
namespace DevOlegKosarev\Parsing\Controllers;

// Import the classes that are used in this file
use stdClass;
use ErrorException;
use LogicException;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Class VendorsController
 * Handles the parsing and syncing of products from different vendors
 */
class VendorsController extends BaseController
{
    // Define constants for class names and namespaces
    const BASE_VENDOR_NAMESPACE = 'Devolegkosarev\\';
    const BASE_VENDOR_CONFIG_NAMESPACE = 'Devolegkosarev\\';

    /**
     * Method parsing_products
     * Gets all the products from a given vendor and returns them as JSON
     * @param string|null $vendor The name of the vendor
     * @return ResponseInterface The response object with the status code and the JSON data
     */
    public function parsing_products(?string $vendor = null): ResponseInterface
    {
        try {
            // Check if the vendor name is valid and create the vendor controller object
            $vendorController = $this->checkVendors($vendor);
            // Get all the products from the vendor controller
            $products = $vendorController->getAllProducts();
            // Return a success response with the products as JSON
            return $this->response->setStatusCode(200)->setJSON(['result' => true, 'products' => $products ?? []]);
        } catch (\Throwable $th) {
            // Return an error response with the exception message as JSON
            return $this->response->setStatusCode(400)->setJSON(['result' => false, 'error' => $th->getMessage()]);
        }
    }

    /**
     * Method syncProducts
     * Syncs all the products from a given vendor and returns them as an array
     * @param string|null $vendor The name of the vendor
     * @return array The array of synced products
     */
    public function syncProducts(?string $vendor = null): array
    {
        try {
            // Check if the vendor name is valid and create the vendor controller object
            $vendorController = $this->checkVendors($vendor);
            // Sync all the products from the vendor controller
            $products = $vendorController->syncAllProducts();

            return $products;
        } catch (\Throwable $th) {
            // Throw an error exception with the exception message
            throw new ErrorException($th->getMessage());
        }
    }

    /**
     * Method checkVendors
     * Checks if the vendor name is valid and returns the vendor controller object
     * @param string|null $vendor The name of the vendor
     * @return object The vendor controller object
     */
    protected function checkVendors(?string $vendor)
    {
        // Use null coalescing operator to set default value if parameter is null
        $vendor = $vendor ?? '';

        // Check if the vendor name is not empty
        if ($vendor) {
            // Create and return the vendor controller object
            return $this->createVendorController($vendor);
        } else {
            // Throw a logic exception if the vendor name is empty
            throw new LogicException('Vendor name is required');
        }
    }

    /**
     * Method createVendorController
     * Creates and returns the vendor controller object with the given vendor name and config
     * @param string $vendor The name of the vendor
     * @return object The vendor controller object
     */
    protected function createVendorController(string $vendor)
    {
        // Get the vendor class name from the vendor name
        $vendorClass = $this->getVendorClassName($vendor);
        // Load the vendor config from the vendor name
        $vendorConfig = $this->loadVendorConfig($vendor);

        // Instantiate and return the vendor controller object with the class name and config
        return $this->instantiateVendorController($vendorClass, $vendorConfig);
    }

    /**
     * Method getVendorClassName
     * Gets and returns the vendor class name from the vendor name using constants and string interpolation 
     * @param string $vendor The name of the vendor 
     * @return string The vendor class name 
     */
    protected function getVendorClassName(string $vendor): string
    {

        // Use constants and string interpolation to build the vendor namespace 
        $vendorNamespace = self::BASE_VENDOR_NAMESPACE . ucfirst($vendor) . "\\Controllers\\" . ucfirst($vendor) . "Controller";

        // Check if the vendor class exists 
        if (!class_exists($vendorNamespace)) {
            // Throw a logic exception if the vendor class does not exist 
            throw new LogicException('Vendor class does not exist');
        }

        return $vendorNamespace;
    }


    /**
     * Method loadVendorConfig
     * Loads and returns the vendor config object from the vendor name using constants and string interpolation
     * @param string $vendor The name of the vendor
     * @return object The vendor config object
     * @throws LogicException if the vendor config class does not exist
     * @throws LogicException if failed to initialize the vendor config
     */
    protected function loadVendorConfig(string $vendor)
    {
        // Use constants and string interpolation to build the vendor config class name
        $vendorConfigClass = self::BASE_VENDOR_CONFIG_NAMESPACE . ucfirst($vendor) . "\\Config\\" . ucfirst($vendor) . "Config";

        // Check if the vendor config class exists
        if (!class_exists($vendorConfigClass)) {
            // Throw a custom exception if the vendor config does not exist 
            throw new LogicException('Vendor Config does not exist');
        }

        // Instantiate the vendor config object from the class name
        $vendorConfig = new $vendorConfigClass();

        // Use an if statement to check if the object is null
        if ($vendorConfig === null) {
            // Throw a logic exception if the vendor config could not be instantiated
            throw new LogicException('Vendor config could not be instantiated');
        }

        // Set the base path property of the vendor config object using constants and string interpolation
        $vendorConfig->basePath = WRITEPATH . "CSV" . DIRECTORY_SEPARATOR . $vendor;

        // Create the vendor directory if it does not exist
        if (!is_dir($vendorConfig->basePath)) {
            $this->createVendorDirectory($vendorConfig->basePath);
        }

        return $vendorConfig;
    }
    /**
     * Method instantiateVendorController
     * Instantiates and returns the vendor controller object from the vendor class name and config
     * @param string $vendorClass The vendor class name
     * @param object $vendorConfig The vendor config object
     * @return object The vendor controller object
     */
    protected function instantiateVendorController(string $vendorClass, $vendorConfig)
    {
        // Instantiate the vendor controller object from the class name and config
        $vendorController = new $vendorClass($vendorConfig);
        // Use an if statement to check if the object is null
        if ($vendorController === null) {
            // Throw a logic exception if the vendor controller could not be instantiated
            throw new LogicException('Vendor controller could not be instantiated');
        }
        return $vendorController;
    }

    /**
     * Method createVendorDirectory
     * Creates the vendor directory if it does not exist using the given base path
     * @param string $basePath The base path of the vendor directory
     * @return void 
     */
    protected function createVendorDirectory(string $basePath): void
    {
        // Check if the directory does not exist 
        if (!file_exists($basePath)) {
            // Create the directory with permissions and recursive option 
            mkdir($basePath, 0777, true);
        }
    }
}