<?php
/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @package    Devolegkosarev\Parsing\Models
 * @author     Oleg Kosarev <dev.oleg.kosarev@outlook.com>
 * @version    0.0.1
 * @since      0.0.1
 */

namespace Devolegkosarev\Parsing\Models;

use CodeIgniter\Model;

class iDataSyncModel extends Model
{
    protected $DBGroup = 'iData';

    public function countProducts(string $category, string $subcategory, $company_id = 0): int
    {
        $db = $this->db; // Получаем экземпляр базы данных

        // Execute the first query using the $category parameter
        $query1 = $db->table('cscart_seo_names')
            ->select('object_id')
            ->like('name', $category)
            ->like('type', 'c')
            ->like('lang_code', 'en')
            ->orderBy('name', 'ASC')
            ->get();

        // Check if the query returned any rows
        if ($query1->getNumRows() == 0) {
            // Handle the case where the query returned no rows
            return 0;
        }

        // Get the ID from the first query
        $id1 = $query1->getRow()->object_id;

        // Execute the second query using the $subcategory parameter and the ID from the first query
        $query2 = $db->table('cscart_seo_names')
            ->select('object_id')
            ->like('name', '%' . $subcategory . '%')
            ->where('type', 'c')
            ->where('path', $id1)
            ->orderBy('name', 'ASC')
            ->get();

        // Check if the query returned any rows
        if ($query2->getNumRows() == 0) {
            // Handle the case where the query returned no rows
            return 0;
        }

        // Get the ID from the second query
        $id2 = $query2->getRow()->object_id;

        // Execute the third query using the ID from the second query
        $query3 = $db->table('cscart_categories')
            ->select('category_id')
            ->like('id_path', '%' . $id2 . '%')
            ->where('level', 3)
            ->orderBy('id_path', 'ASC')
            ->get();

        // Get an array of categories from the third query
        $categories = $query3->getResultArray();

        if (empty($categories)) {
            // Handle the case where the query returned no rows
            return 0;
        }

        // Create an array of category IDs
        $categoryIds = array_column($categories, 'category_id');

        // Execute the fourth query using the array of category IDs
        $query4 = $db->table('cscart_products_categories')
            ->select('COUNT(*) as count')
            ->join('cscart_products', 'cscart_products.product_id = cscart_products_categories.product_id')
            ->whereIn('cscart_products_categories.category_id', $categoryIds)
            ->where('cscart_products.status', 'A');
        if ($company_id > 0) {
            $query4->where('cscart_products.company_id', $company_id);

        }
        $query4Get = $query4->get();
        // Get the total number of products from the fourth query
        $totalCount = $query4Get->getRow()->count;

        $this->db->close();

        // Return the total number of products
        return $totalCount;
    }
}