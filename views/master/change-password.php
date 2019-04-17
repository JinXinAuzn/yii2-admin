<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \jx\admin\models\form\ChangePassword */

$this->title = Yii::t('rbac-admin', 'Change Password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="list-table ibox panel-dep-edit">
	<?php $form = ActiveForm::begin([
		'id' => $model->formName(),
		'options' => ['class' => 'form-horizontal'],
		'fieldConfig' => [
			'template' => '{label}<div class="col-sm-6">{input}</div><div class="col-sm-3">{hint}{error}</div>',
			'labelOptions' => ['class' => 'col-sm-3 control-label'],
			'inputOptions' => ['class' => 'form-control']
		],
	]); ?>
	<?= $form->field($model, 'oldPassword')->passwordInput() ?>
	<?= $form->field($model, 'newPassword')->passwordInput() ?>
	<?= $form->field($model, 'retypePassword')->passwordInput() ?>
	<div class="form-group text-center">
		<?= Html::submitButton(Yii::t('rbac-admin', 'Update'), ['class' => 'btn btn-success', 'name' => 'change-button']) ?>
		<?=Html::a(Yii::t('rbac-admin', 'Return'),'javascript:history.go(-1)', ['class' => 'btn btn-default'] )?>
	</div>
	<?php ActiveForm::end(); ?>
</div>
