<?php

namespace jx\admin\controllers;

use Yii;

/**
 * DefaultController
 *
 * @author Au zn <690550322@qq.com>
 * @since Full version
 */
class DefaultController extends BaseController
{

	/**
	 * Action index
	 * @param string $page
	 * @return string|\yii\console\Response|\yii\web\Response
	 */
    public function actionIndex($page = 'README.md')
    {
        if (strpos($page, '.png') !== false) {
            $file = Yii::getAlias("@jx/admin/{$page}");
            return Yii::$app->getResponse()->sendFile($file);
        }
        return $this->render('index', ['page' => $page]);
    }
}
