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
 * @version 0.0.1
 * @since   0.0.1
 */

namespace Devolegkosarev\Parsing\Services;

use Devolegkosarev\Parsing\Models\iDataPresetDescriptionModel;
use Devolegkosarev\Parsing\Models\iDataPresetFieldsModel;
use Devolegkosarev\Parsing\Models\iDataPresetModel;
use Devolegkosarev\Parsing\Models\iDataPresetStatesModel;
use Exception;
use stdClass;


/**
 * A class that provides a service for inserting data presets.
 */
class iDataPresetInsertService
{
    /**
     * @var array $languages Supported languages.
     */
    private $languages;

    /**
     * @var array $fields Fields.
     */
    private $fields;

    /**
     * The iDataPresetDescriptionModel instance.
     *
     * @var iDataPresetDescriptionModel
     */
    protected $iDataPresetDescriptionModel;

    /**
     * The iDataPresetFieldsModel instance.
     *
     * @var iDataPresetFieldsModel
     */
    protected $iDataPresetFieldsModel;

    /**
     * The iDataPresetModel instance.
     *
     * @var iDataPresetModel
     */
    protected $iDataPresetModel;

    /**
     * The iDataPresetStatesModel instance.
     *
     * @var iDataPresetStatesModel
     */
    protected $iDataPresetStatesModel;

    /**
     * Constructor.
     *
     * @param array $languages Supported languages. Default is an empty array.
     * @param array $fields    Fields. Must not be empty.
     *
     * @throws Exception When fields set is empty.
     */
    function __construct(array $languages = [], array $fields = [], $dbConnect)
    {
        $this->iDataPresetDescriptionModel = new iDataPresetDescriptionModel($dbConnect);
        $this->iDataPresetFieldsModel = new iDataPresetFieldsModel($dbConnect);
        $this->iDataPresetModel = new iDataPresetModel($dbConnect);
        $this->iDataPresetStatesModel = new iDataPresetStatesModel($dbConnect);

        $this->languages = $languages;
        if (count($fields) <= 0) {
            throw new Exception("fields set is required");
        }
        if (count($languages) <= 0) {
            throw new Exception("languages set is required");
        }
        $this->fields = $fields;
    }



    /**
     * Insert data preset.
     *
     * @param string $fileAbsolutePath Absolute path to the file.
     * @param int    $company_id       Company ID.
     *
     * @throws Exception When company_id or fileAbsolutePath is missing.
     */
    function insert(string $fileAbsolutePatch = null, int $company_id = 0)
    {
        if ($company_id <= 0) {
            throw new Exception("company_id is required");
        }
        if (empty($fileAbsolutePatch)) {
            throw new Exception("fileAbsolutePatch is required");
        }
        $PresetModelInsertID = $this->cscart_import_presets($company_id);
        if ($PresetModelInsertID <= 0) {
            throw new Exception("Error Insert Preset to CsCart");
        }
        $this->cscart_import_preset_descriptions($PresetModelInsertID, $fileAbsolutePatch);
        $this->cscart_import_preset_fields($PresetModelInsertID);
        $this->cscart_import_preset_states($PresetModelInsertID, $fileAbsolutePatch);

        /**
         * @todo add to cronJob for import
         */
    }

    /**
     * Import presets to CsCart.
     *
     * @param int $company_id Company.
     *
     * @return int Inserted preset ID.
     */
    private function cscart_import_presets(int $company_id): int
    {

        $cscart_import_presets = new stdClass;
        $cscart_import_presets->company_id = $company_id;
        $cscart_import_presets->object_type = "products";
        $cscart_import_presets->file_extension = "csv";
        $cscart_import_presets->options = json_encode(
            array(
                'target_node' => 'yml_catalog/shop/offers/offer',
                'images_path' => 'exim/backup/images/',
                'delimiter' => 'A',
                'price_dec_sign_delimiter' => '.',
                'category_delimiter' => '///',
                'features_delimiter' => '///',
                'files_path' => 'exim/backup/downloads/',
                'attachments_delimiter' => ',',
                'attachments_path' => 'exim/backup/attachments/',
                'images_delimiter' => '///',
                'test_import' => 'N',
                'import_strategy' => 'import_all',
                'reset_inventory' => 'N',
                'delete_files' => 'N',
                'remove_images' => 'N',
                'remove_attachments' => 'N',
                'skip_creating_new_products' => 'N',
            )
        );
        return $this->iDataPresetModel->insert($cscart_import_presets);
    }

    /**
     * Imports preset descriptions for different languages from a file.
     *
     * @param int $PresetModelInsertID The ID of the preset model to import.
     * @param string $fileAbsolutePatch The absolute path of the file containing the descriptions.
     * @return bool True on success, false on failure.
     */
    private function cscart_import_preset_descriptions(int $PresetModelInsertID, string $fileAbsolutePatch): bool
    {
        foreach ($this->languages as $key => $language) {
            $cscart_import_preset_descriptions = new stdClass;
            $cscart_import_preset_descriptions->preset_id = $PresetModelInsertID;
            $cscart_import_preset_descriptions->lang_code = $language;
            $cscart_import_preset_descriptions->preset = $fileAbsolutePatch;
            $this->iDataPresetDescriptionModel->insert($cscart_import_preset_descriptions);
        }
        return true;
    }

    /**
     * Imports preset fields for different properties from an array.
     *
     * @param int $PresetModelInsertID The ID of the preset model to import.
     * @return bool True on success, false on failure.
     */
    private function cscart_import_preset_fields(int $PresetModelInsertID): bool
    {
        foreach ($this->fields as $key => $field) {
            $cscart_import_preset_fields = new stdClass;
            if ($field == 'Description') {
                $cscart_import_preset_fields->preset_id = $PresetModelInsertID;
                $cscart_import_preset_fields->name = $field;
                $cscart_import_preset_fields->related_object_type = 'skip';
                $cscart_import_preset_fields->related_object = '';
                $cscart_import_preset_fields->modifier = '';
            } else {
                $cscart_import_preset_fields->preset_id = $PresetModelInsertID;
                $cscart_import_preset_fields->name = $field;
                $cscart_import_preset_fields->related_object_type = 'property';
                $cscart_import_preset_fields->related_object = $field;
                $cscart_import_preset_fields->modifier = '';
            }
            $this->iDataPresetFieldsModel->insert($cscart_import_preset_fields);
        }
        return true;
    }

    /**
     * Imports preset states for a given preset model from a file.
     *
     * @param int $PresetModelInsertID The ID of the preset model to import.
     * @param string $fileAbsolutePatch The absolute path of the file containing the states.
     * @return bool True on success, false on failure.
     */
    private function cscart_import_preset_states(int $PresetModelInsertID, string $fileAbsolutePatch): bool
    {
        $cscart_import_preset_states = new stdClass;
        $cscart_import_preset_states->preset_id = $PresetModelInsertID;
        $cscart_import_preset_states->company_id = 0;
        $cscart_import_preset_states->last_launch = 0;
        $cscart_import_preset_states->last_status = "X";
        $cscart_import_preset_states->last_result = null;
        $cscart_import_preset_states->file = $fileAbsolutePatch;
        $cscart_import_preset_states->file_type = "server";
        $this->iDataPresetStatesModel->insert($cscart_import_preset_states);
        return true;

    }
}