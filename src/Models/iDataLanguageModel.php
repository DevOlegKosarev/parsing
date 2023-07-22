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
use Exception;

class iDataLanguageModel extends Model
{
    protected $table = 'cscart_languages';
    protected $primaryKey = 'lang_id';
    protected $allowedFields = ['lang_code'];
    protected $returnType = 'array';
    public function getAllLanguages()
    {
        $this->select("lang_code");
        $this->where("status", "A");
        return array_map(function ($item) {
            return $item['lang_code'];
        }, $this->findAll());
    }

    /**
     * Override the save() method to prevent working with the database
     *
     * @param array|object|null $data Ignored data
     * @return bool Always false
     * @throws Exception If called
     */
    public function save($data = null): bool
    {
        // Return false or throw an exception
        // return false;
        throw new Exception('Save operation is not allowed for this model');
    }

    /**
     * Override the delete() method to prevent working with the database
     *
     * @param int|string|null $id Ignored identifier
     * @param bool $purge Ignored flag
     * @return bool Always false
     * @throws Exception If called
     */
    public function delete($id = null, bool $purge = false): bool
    {
        // Return false or throw an exception
        // return false;
        throw new Exception('Delete operation is not allowed for this model');
    }

    /**
     * Override the update() method to prevent working with the database
     *
     * @param int|string|array|null $id Ignored identifier or data array
     * @param array|object|null $data Ignored data or null if an array is passed as the first parameter.
     * @return bool Always false
     * @throws Exception If called
     */
    public function update($id = null, $data = null): bool
    {
        // Return false or throw an exception
        // return false;
        throw new Exception('Update operation is not allowed for this model');
    }

    /**
     * Override the insert() method to prevent working with the database
     *
     * @param int|string|array|null $id Ignored identifier or data array
     * @param array|object|null $data Ignored data or null if an array is passed as the first parameter.
     * @return bool Always false
     * @throws Exception If called
     */
    public function insert($data = null, bool $returnID = true): bool
    {
        // Return false or throw an exception
        // return false;
        throw new Exception('Insert operation is not allowed for this model');
    }
}