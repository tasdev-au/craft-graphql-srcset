<?php
/**
 * GraphQL Srcset plugin for Craft CMS 3.x
 *
 * Adds the @srcset GraphQL directive for generating a comma-separated list of image transforms.
 *
 * @link      https://tas.dev
 * @copyright Copyright (c) 2020 Jayden Smith
 */

/**
 * GraphQL Srcset config.php
 *
 * This file exists only as a template for the GraphQL Srcset settings.
 * It does nothing on its own.
 *
 * Don't edit this file, instead copy it to 'craft/config' as 'graphql-srcset.php'
 * and make your changes there to override default settings.
 *
 * Once copied to 'craft/config', this file will be multi-environment aware as
 * well, so you can have different settings groups for each environment, just as
 * you do for 'general.php'
 */

return [

    'widths' => [ 640, 768, 1024, 1366, 1600, 1920, 2560 ],

];
