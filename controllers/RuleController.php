<?php

namespace jx\admin\controllers;

use Yii;
use jx\admin\models\BizRule;
use jx\admin\models\searchs\BizRule as BizRuleSearch;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use jx\admin\components\Helper;
use jx\admin\components\Configs;

/**
 * Description of RuleController
 *
 * @author Au zn <690550322@qq.com>
 * @since Full version
 */
class RuleController extends BaseController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BizRuleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
        ]);
    }

	/**
	 * Displays a single AuthItem model.
	 * @param  string $id
	 * @return mixed
	 * @throws NotFoundHttpException
	 */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', ['model' => $model]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BizRule(null);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Helper::invalidate();
	        $this->showMessage('success',Yii::t('rbac-admin', 'Create Success'));
	        return $this->redirect(['index']);
        } else {
            return $this->render('create', ['model' => $model,]);
        }
    }

	/**
	 * Updates an existing AuthItem model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param  string $id
	 * @return mixed
	 * @throws NotFoundHttpException
	 */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Helper::invalidate();
	        $this->showMessage('success',Yii::t('rbac-admin', 'Update Success'));
	        return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model,]);
    }

	/**
	 * Deletes an existing AuthItem model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param  string $id
	 * @return mixed
	 * @throws NotFoundHttpException
	 */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Configs::authManager()->remove($model->item);
        Helper::invalidate();
	    $this->showMessage('danger',Yii::t('rbac-admin', 'Delete Success'));
	    return $this->redirect(['index']);
    }

	/**
	 * Finds the AuthItem model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param  string $id
	 * @return BizRule the loaded model
	 * @throws NotFoundHttpException
	 */
    protected function findModel($id)
    {
        $item = Configs::authManager()->getRule($id);
        if ($item) {
            return new BizRule($item);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
