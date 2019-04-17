<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \jx\admin\models\form\Signup */

$this->title = Yii::t('rbac-admin', 'Signup');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="list-table ibox panel-dep-edit">
	<?= Html::errorSummary($model) ?>
	<?php $form = ActiveForm::begin([
		'id' => $model->formName(),
		'options' => ['class' => 'form-horizontal'],
		'fieldConfig' => [
			'template' => '{label}<div class="col-sm-6">{input}</div><div class="col-sm-3">{hint}{error}</div>',
			'labelOptions' => ['class' => 'col-sm-3 control-label'],
			'inputOptions' => ['class' => 'form-control']
		],
	]); ?>
	<?= $form->field($model, 'username') ?>
	<?= $form->field($model, 'email') ?>
	<?= $form->field($model, 'password')->passwordInput() ?>
	<div class="form-group text-center">
		<?= Html::submitButton(Yii::t('rbac-admin', 'Signup'), ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
		<?=Html::a(Yii::t('rbac-admin', 'Return'),'javascript:history.go(-1)', ['class' => 'btn btn-default'] )?>
	</div>
	<?php ActiveForm::end(); ?>
</div>
