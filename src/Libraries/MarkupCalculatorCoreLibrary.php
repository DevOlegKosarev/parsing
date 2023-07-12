<?php
namespace DevOlegKosarev\Parsing\Libraries;

/**
 * A class that provides methods for calculating the marked-up price of products based on their price, name and category.
 * This class implements the logic for applying markup and VAT to the original price of the product, depending on its characteristics.
 * The class defines some properties that store the default values for markup ranges, percentages and VAT, which are used 
 * for products that do not have a specific markup or category.
 * These properties can be overridden by subclasses that inherit from this class, or they can be passed as arguments to
 *  the methods of this class, if different values are needed for different products or categories.
 * The class also defines some methods that calculate the marked-up price for a product, either by its name or by its category. 
 * These methods use the properties of the class or the arguments passed to them to apply the appropriate markup and VAT to the original price.
 * The methods of this class can also be overridden by subclasses, if a different logic or algorithm is needed for calculating the marked-up price.
 */
class MarkupCalculatorCoreLibrary
{
    /**
     * The markup ranges and values for default categories of products.
     *
     * @var array
     */
    protected $defaultMarkupRanges = [
        ['min' => 1, 'max' => 100, 'markup' => 60],
        ['min' => 101, 'max' => 200, 'markup' => 50],
        ['min' => 201, 'max' => 400, 'markup' => 40],
        ['min' => 401, 'markup' => 30],
    ];

    /**
     * The markup percentages for specific products.
     *
     * @var array<string, int>
     */
    protected $products = [
        'iPhone' => 21,
        'iPad' => 21,
    ];

    /**
     * The default VAT percentage.
     *
     * @var int
     */
    protected $defaultMarkupVat = 21;

    /**
     * The markup percentages and VAT for specific categories of products.
     *
     * @var array<string, array<int>>
     */
    protected $categories = [
        'Working///Mobiles/Tablet' => [15, 21],
    ];
    public function calculateMarkup(float $price = 0.00, string $category = 'Other', string $productName = 'Unknown'): float
    {
        if ($price === 0.00) {
            return $price;
        }

        $markedUpPrice = $this->calculateProductMarkup($price, $productName);

        if ($markedUpPrice === null) {
            $markedUpPrice = $this->calculateCategoryMarkup($price, $category);
        }

        return $markedUpPrice;
    }

    /**
     * Calculates the marked-up price for a product based on its name.
     *
     * @param float  $price       The price of the product.
     * @param string $productName The name of the product.
     *
     * @return float|null The marked-up price of the product or null if not matched.
     */
    protected function calculateProductMarkup(float $price, string $productName): ?float
    {

        if (isset($products[$productName])) {
            return $this->applyMarkup($price, $this->products[$productName]);
        }

        return null;
    }

    /**
     * Calculates the marked-up price for a product based on its category.
     *
     * @param float  $price    The price of the product.
     * @param string $category The category of the product.
     *
     * @return float The marked-up price of the product.
     */
    protected function calculateCategoryMarkup(float $price, string $category): float
    {


        if (isset($categories[$category])) {
            return $this->applyMarkup($price, ...$this->categories[$category]);
        }

        return $this->computeOtherMarkup($price);
    }

    /**
     * Calculates the price with markup and VAT applied.
     *
     * @param float $price  The original price.
     * @param int   $markup The markup percentage.
     *
     * @return float The marked-up price with VAT applied.
     */
    protected function applyMarkup(float $price, int $markup): float
    {
        if ($price <= 0) {
            return $price;
        }

        $markedUpPrice = $price + ($price * $markup / 100);

        return $this->applyVat($markedUpPrice);
    }

    /**
     * Applies VAT to the given price.
     *
     * @param float $price The price.
     *
     * @return float The price with VAT applied.
     */
    protected function applyVat(float $price): float
    {
        return $price + ($price * $this->defaultMarkupVat / 100);
    }

    /**
     * Computes the marked-up price for a product in the 'Other' category.
     *
     * @param float $price The price of the product.
     *
     * @return float The marked-up price of the product.
     */
    private function computeOtherMarkup(float $price): float
    {
        $range = array_filter($this->defaultMarkupRanges, function ($range) use ($price) {
            return $price >= $range['min'] && (!isset($range['max']) || $price <= $range['max']);
        });

        if (!empty($range)) {
            return $this->applyMarkup($price, reset($range)['markup']);
        }
        return $price;
    }
}