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

class iDataPresetStatesModel extends Model
{
    protected $DBGroup = 'Foxway';
    protected $table = 'cscart_import_preset_states';
    protected $primaryKey = 'preset_id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        "preset_id",
        "company_id",
        "last_launch",
        "last_status",
        "last_result",
        "file",
        "file_type"
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function getLastStatus($patch)
    {
        if (empty($patch)) {
            return null;
        }
        $builder = $this->db->table($this->table);
        $builder->like('file', $patch);
        $this->db->close();
        return $builder->get()->getRowObject();
    }
}