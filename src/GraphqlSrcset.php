<?php
/**
 * GraphQL Srcset plugin for Craft CMS 3.x
 *
 * Adds the @srcset GraphQL directive for generating a comma-separated list of image transforms.
 *
 * @link      https://tas.dev
 * @copyright Copyright (c) 2020 Jayden Smith
 */

namespace tasdev\graphqlsrcset;

use Craft;
use craft\base\Plugin;
use craft\events\RegisterGqlDirectivesEvent;
use craft\services\Gql;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\helpers\UrlHelper;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

use tasdev\graphqlsrcset\models\Settings;
use tasdev\graphqlsrcset\gql\directives\SrcSet;
use tasdev\graphqlsrcset\variables\GraphqlSrcset as GraphqlSrcsetVariable;

/**
 * Class GraphqlSrcset
 *
 * @author    Jayden Smith
 * @package   GraphqlSrcset
 * @since     1.0.0
 *
 */
class GraphqlSrcset extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var GraphqlSrcset
     */
    public static $plugin;


    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    /**
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * @var bool
     */
    public $hasCpSection = false;


    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->_registerEventHandlers();
        $this->_registerVariable();
    }

    /**
     * @inheritdoc
     */
    public function getSettingsResponse()
    {
        return Craft::$app->getResponse()->redirect(UrlHelper::cpUrl('graphql-srcset/settings'));
    }


    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }


    // Private Methods
    // =========================================================================

    /**
     * Register the event handlers.
     */
    private function _registerEventHandlers()
    {
        Event::on(Gql::class, Gql::EVENT_REGISTER_GQL_DIRECTIVES, function (RegisterGqlDirectivesEvent $event) {
            $event->directives[] = SrcSet::class;
        });

        // Redirect after plugin install
        Event::on(Plugins::class, Plugins::EVENT_AFTER_INSTALL_PLUGIN, function (PluginEvent $event) {
            if ($event->plugin === $this) {
                Craft::$app->getGql()->flushCaches();

                if (Craft::$app->getRequest()->isCpRequest) {
                    Craft::$app->getResponse()->redirect(
                        UrlHelper::cpUrl('graphql-srcset/settings')
                    )->send();
                }
            }
        });
    }

    /**
     * Register Auctions template variable
     */
    private function _registerVariable()
    {
        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function(Event $event) {
            /** @var CraftVariable $variable */
            $variable = $event->sender;
            $variable->set('graphqlSrcset', GraphqlSrcsetVariable::class);
        });
    }
}
