<?php
/**
 * GraphQL Srcset plugin for Craft CMS 3.x
 *
 * Adds the @srcset GraphQL directive for generating a comma-separated list of image transforms.
 *
 * @link      https://tas.dev
 * @copyright Copyright (c) 2020 Jayden Smith
 */

namespace tasdev\graphqlsrcset\variables;

use tasdev\graphqlsrcset\GraphqlSrcset as GraphqlSrcsetPlugin;

/**
 * Variable class.
 *
 * @author    Jayden Smith
 * @package   GraphqlSrcset
 * @since     1.0.0
 */
class GraphqlSrcset
{
    /**
     * Get the plugin instance.
     *
     * @return GraphqlSrcsetPlugin
     */
    public function getPlugin(): GraphqlSrcsetPlugin
    {
        return GraphqlSrcsetPlugin::getInstance();
    }
}
