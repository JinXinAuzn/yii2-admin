<?php


namespace jx\admin\controllers;

use jx\admin\models\searchs\Logs;
use Yii;

/**
 * @author Au zn <690550322@qq.com>
 * @since Full version
 */
class LogsController extends BaseController
{
	/**
	 * Lists all  Logs models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new Logs();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
}
