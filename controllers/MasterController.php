<?php

namespace jx\admin\controllers;

use jx\admin\components\AdminLog;
use Yii;
use jx\admin\models\form\Login;
use jx\admin\models\form\PasswordResetRequest;
use jx\admin\models\form\ResetPassword;
use jx\admin\models\form\Signup;
use jx\admin\models\form\ChangePassword;
use jx\admin\models\Master;
use jx\admin\models\searchs\Master as MasterSearch;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\base\UserException;
use yii\mail\BaseMailer;
use yii\captcha\CaptchaAction;

/**
 * @author Au zn <690550322@qq.com>
 * @since Full version
 */
class MasterController extends BaseController
{
    private $_oldMailPath;

	/*
		 * 验证码
		 * */
	public function actions()
	{
		return [
			'captcha' => [
				'class' => CaptchaAction::className(),
				'minLength' => 4,
				'maxLength' => 4,
				'backColor' => 0x00A17D,
				'foreColor' => 0xFFFFFF,
				'transparent' => FALSE,
			],
		];
	}

	/**
	 * @inheritdoc
	 * @throws BadRequestHttpException
	 */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (Yii::$app->has('mailer') && ($mailer = Yii::$app->getMailer()) instanceof BaseMailer) {
                /* @var $mailer BaseMailer */
                $this->_oldMailPath = $mailer->getViewPath();
                $mailer->setViewPath('@jx/admin/mail');
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        if ($this->_oldMailPath !== null) {
            Yii::$app->getMailer()->setViewPath($this->_oldMailPath);
        }
        return parent::afterAction($action, $result);
    }

    /**
     * Lists all Master models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

	/**
	 * Displays a single Master model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException
	 */
    public function actionView($id)
    {
        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

	/**
	 * Deletes an existing Master model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
	    $this->showMessage('danger',Yii::t('rbac-admin', 'Delete Success'));
	    return $this->redirect(['index']);
    }

    /**
     * Login
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->getUser()->isGuest) {
            return $this->goHome();
        }
        $model = new Login();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
	        AdminLog::addLog(1);
            return $this->goBack();
        } else {
	        return $this->renderPartial('login', [
		        'model' => $model,
	        ]);
        }
    }

    /**
     * Logout
     * @return string
     */
    public function actionLogout()
    {
	    AdminLog::addLog(2);
        Yii::$app->getUser()->logout();
        return $this->goHome();
    }

    /**
     * Signup new master
     * @return string
     */
    public function actionSignup()
    {
        $model = new Signup();
        if ($model->load(Yii::$app->getRequest()->post())) {
            if ($user = $model->signup()) {
	            $this->showMessage('success',Yii::t('rbac-admin', 'Create Success'));
	            return $this->redirect(['index']);
            }
        }
        return $this->render('signup', [
                'model' => $model,
        ]);
    }

    /**
     * Request reset password
     * @return string
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequest();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            if ($model->sendEmail()) {
	            $this->showMessage('success', 'Check your email for further instructions.');
	            return $this->redirect(['index']);
            } else {
	            $this->showMessage('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
                'model' => $model,
        ]);
    }

	/**
	 * Reset password
	 * @return string
	 * @throws BadRequestHttpException
	 */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPassword($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate() && $model->resetPassword()) {
	        $this->showMessage('success', 'New password was saved.');

	        return $this->redirect(['index']);
        }

        return $this->render('resetPassword', [
                'model' => $model,
        ]);
    }

    /**
     * Reset password
     * @return string
     */
    public function actionChangePassword()
    {
        $model = new ChangePassword();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->change()) {
	        $this->showMessage('success',Yii::t('rbac-admin', 'Update Success'));
	        return $this->redirect(['index']);
        }
        return $this->render('change-password', [
                'model' => $model,
        ]);
    }

    /**
     * Activate new master
     * @param integer $id
     * @return \yii\web\Response
     * @throws UserException
     * @throws NotFoundHttpException
     */
    public function actionActivate($id)
    {
        /* @var $user Master */
        $user = $this->findModel($id);
        if ($user->status == Master::STATUS_INACTIVE) {
            $user->status = Master::STATUS_ACTIVE;
            if ($user->save()) {
	            return $this->redirect(['index']);
            } else {
                $errors = $user->firstErrors;
                throw new UserException(reset($errors));
            }
        }else{
	        $user->status = Master::STATUS_INACTIVE;
	        $user->save();
        }
	    return $this->redirect(['index']);
    }

    /**
     * Finds the Master model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Master the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Master::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
