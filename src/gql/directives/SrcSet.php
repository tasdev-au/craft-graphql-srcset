<?php
/**
 * GraphQL Srcset plugin for Craft CMS 3.x
 *
 * Adds the @srcset GraphQL directive for generating a comma-separated list of image transforms.
 *
 * @link      https://tas.dev
 * @copyright Copyright (c) 2020 Jayden Smith
 */

namespace tasdev\graphqlsrcset\gql\directives;

use Craft;
use craft\elements\Asset;
use craft\gql\base\Directive;
use craft\gql\GqlEntityRegistry;
use GraphQL\Language\DirectiveLocation;
use GraphQL\Type\Definition\Directive as GqlDirective;
use GraphQL\Type\Definition\FieldArgument;
use GraphQL\Type\Definition\ResolveInfo;

use tasdev\graphqlsrcset\GraphqlSrcset;
use tasdev\graphqlsrcset\gql\arguments\SrcSet as SrcSetArguments;

/**
 * Class SrcSet
 *
 * @author    Jayden Smith
 * @package   GraphqlSrcset
 * @since     1.0.0
 */
class SrcSet extends Directive
{
    public function __construct(array $config)
    {
        $args = &$config['args'];

        foreach ($args as &$argument) {
            $argument = new FieldArgument($argument);
        }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public static function create(): GqlDirective
    {
        if ($type = GqlEntityRegistry::getEntity(self::name())) {
            return $type;
        }

        $type = GqlEntityRegistry::createEntity(static::name(), new self([
            'name' => static::name(),
            'locations' => [
                DirectiveLocation::FIELD,
            ],
            'args' => SrcSetArguments::getArguments(),
            'description' => 'This directive is used to return a comma separated list of [asset transform](https://docs.craftcms.com/v3/image-transforms.html) URLs for an image. It accepts the same arguments you would use for a transform in Craft and adds the `immediately`, `widths` and `ratio` arguments.'
        ]));

        return $type;
    }

    /**
     * @inheritdoc
     */
    public static function name(): string
    {
        return 'srcset';
    }

    /**
     * @inheritdoc
     */
    public static function apply($source, $value, array $arguments, ResolveInfo $resolveInfo)
    {
        $onAssetElement = $source === null && $value instanceof Asset;
        $onAssetElementList = $source === null && is_array($value) && !empty($value);
        $onApplicableAssetField = $source instanceof Asset && in_array($resolveInfo->fieldName, ['url']);

        if ($onAssetElementList) {
            return $value;
        }

        if (!$onAssetElement && !$onApplicableAssetField) {
            return $value;
        }

        $generateNow = $arguments['immediately'] ?? Craft::$app->getConfig()->general->generateTransformsBeforePageLoad;
        $widths = $arguments['widths'] ?? GraphqlSrcset::getInstance()->getSettings()->widths;

        // Ensure widths are ascending.
        sort($widths);

        $imageWidth = $onAssetElement ? $value->getWidth() : $source->getWidth();

        $transforms = [];
        foreach ($widths as $width) {
            if ($width <= $imageWidth) {
                $transforms[] = [
                    'width' => $width,
                    'height' => isset($arguments['ratio']) ? round($width * floatval($arguments['ratio'])) : null,
                    'mode' => isset($arguments['mode']) ? $arguments['mode'] : null,
                    'format' => isset($arguments['format']) ? $arguments['format'] : null,
                ];
            }
        }

        $lastWidth = end($widths);
        $lastTransform = end($transforms);
        if (!$lastTransform || ($lastTransform['width'] < $imageWidth && $imageWidth < $lastWidth)) {
            $transforms[] = [
                'width' => $imageWidth,
                'height' => isset($arguments['ratio']) ? round($imageWidth * floatval($arguments['ratio'])) : null,
                'mode' => isset($arguments['mode']) ? $arguments['mode'] : null,
                'format' => isset($arguments['format']) ? $arguments['format'] : null,
            ];
        }

        $urls = [];
        foreach ($transforms as $transform) {
            $width = $transform['width'];
            $urls[] = Craft::$app->getAssets()->getAssetUrl($source, $transform, $generateNow) . ' ' . $width . 'w';
        }

        return implode(', ', $urls);
    }
}
