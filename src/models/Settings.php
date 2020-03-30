<?php
/**
 * GraphQL Srcset plugin for Craft CMS 3.x
 *
 * Adds the @srcset GraphQL directive for generating a comma-separated list of image transforms.
 *
 * @link      https://tas.dev
 * @copyright Copyright (c) 2020 Jayden Smith
 */

namespace tasdev\graphqlsrcset\models;

use tasdev\graphqlsrcset\GraphqlSrcset;

use Craft;
use craft\base\Model;

/**
 * @author    Jayden Smith
 * @package   GraphqlSrcset
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $widths = [ 640, 768, 1024, 1366, 1600, 1920, 2560 ];

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['widths', 'required'],
            ['widths', 'validateWidths'],
        ];
    }

    /**
     * Validates the widths setting.
     *
     * @param $attribute
     */
    public function validateWidths($attribute)
    {
        $values = $this->$attribute;

        if (!is_array($values)) {
            $this->addError($attribute, Craft::t('graphql-srcset', 'A valid array is required.'));
            return;
        }

        foreach ($values as $value) {
            if (!is_numeric($value) || $value != floor($value)) {
                $this->addError($attribute, Craft::t('graphql-srcset', 'All widths must be valid, whole integers.'));
                return;
            }
        }
    }
}
