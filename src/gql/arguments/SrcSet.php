<?php
/**
 * GraphQL Srcset plugin for Craft CMS 3.x
 *
 * Adds the @srcset GraphQL directive for generating a comma-separated list of image transforms.
 *
 * @link      https://tas.dev
 * @copyright Copyright (c) 2020 Jayden Smith
 */

namespace tasdev\graphqlsrcset\gql\arguments;

use craft\gql\base\Arguments;
use GraphQL\Type\Definition\Type;

/**
 * Class SrcSet
 *
 * @author    Jayden Smith
 * @package   GraphqlSrcset
 * @since     1.0.0
 */
class SrcSet extends Arguments
{
    /**
     * @inheritdoc
     */
    public static function getArguments(): array
    {
        return [
            'widths' => [
                'name' => 'widths',
                'type' => Type::listOf(Type::int()),
                'description' => 'Optional aspect ratio for the generated transforms'
            ],
            'ratio' => [
                'name' => 'ratio',
                'type' => [Type::float(), Type::string()],
                'description' => 'Optional aspect ratio for the generated transforms'
            ],
            'mode' => [
                'name' => 'mode',
                'type' => Type::string(),
                'description' => 'The mode to use for the generated transform'
            ],
            'position' => [
                'name' => 'position',
                'type' => Type::string(),
                'description' => 'The position to use when cropping, if no focal point specified.'
            ],
            'interlace' => [
                'name' => 'interlace',
                'type' => Type::string(),
                'description' => 'The interlace mode to use for the transform'
            ],
            'quality' => [
                'name' => 'quality',
                'type' => Type::int(),
                'description' => 'The quality of the transform'
            ],
            'format' => [
                'name' => 'format',
                'type' => Type::string(),
                'description' => 'The format to use for the transform'
            ],
            'immediately' => [
                'name' => 'immediately',
                'type' => Type::boolean(),
                'description' => 'Whether the transforms should be generated immediately or only when the image is requested used the generated URL'
            ],
        ];
    }
}
