<?php
/**
 * GraphQL Srcset plugin for Craft CMS 3.x
 *
 * Adds the @srcset GraphQL directive for generating a comma-separated list of image transforms.
 *
 * @link      https://tas.dev
 * @copyright Copyright (c) 2020 Jayden Smith
 */

namespace tasdev\graphqlsrcset\controllers;

use Craft;
use craft\errors\MissingComponentException;
use craft\web\Controller as BaseController;

use yii\web\BadRequestHttpException;
use yii\web\Response;

use tasdev\graphqlsrcset\GraphqlSrcset;
use tasdev\graphqlsrcset\models\Settings as SettingsModel;

/**
 * Class SettingsController
 *
 * @author    Jayden Smith
 * @package   GraphqlSrcset
 * @since     1.0.0
 */
class SettingsController extends BaseController
{
    // Public Methods
    // =========================================================================

    /**
     * @return Response
     * @throws MissingComponentException
     * @throws BadRequestHttpException
     */
    public function actionSaveSettings(): Response
    {
        $this->requirePostRequest();
        $postData = Craft::$app->getRequest()->getParam('settings');

        $widths = [];
        foreach ($postData['widths'] as $width) {
            $widths[] = $width['width'];
        }

        sort($widths);

        $postData = ['widths' => $widths] + $postData;
        $settings = new SettingsModel($postData);

        if (!$settings->validate()) {
            Craft::$app->getSession()->setError(Craft::t('graphql-srcset', 'Couldn’t save settings.'));

            return $this->renderTemplate('graphql-srcset/settings', ['settings' => $settings]);
        }

        Craft::$app->getPlugins()->savePluginSettings(GraphqlSrcset::getInstance(), $settings->toArray());

        Craft::$app->getSession()->setNotice(Craft::t('graphql-srcset', 'Settings saved.'));

        return $this->redirectToPostedUrl();
    }
}
