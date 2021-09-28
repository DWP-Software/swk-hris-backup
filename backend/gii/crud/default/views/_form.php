<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
if (($tableSchema = $generator->getTableSchema()) !== false) {
    $flag_select2       = false;
    $flag_datepicker    = false;
    $imported_models    = [];
    foreach ($tableSchema->columns as $column) {
        if (substr($column->name, -3) == '_id') {
            $flag_select2 = true;
            $imported_models[] = str_replace(StringHelper::basename($generator->modelClass), Inflector::camelize(substr($column->name, 0, -3)), $generator->modelClass);
        }
        if ($column->type == 'date') {
            $flag_datepicker = true;
        }
    }
}
?>

use yii\helpers\Url;
use yii\helpers\Html;
<?php if ($flag_select2) echo "use yii\\helpers\\ArrayHelper;\n"; ?>
use yii\bootstrap\ActiveForm;
<?php if ($flag_select2) echo "use kartik\\widgets\\Select2;\n"; ?>
<?php if ($flag_datepicker) echo "use kartik\\widgets\\DatePicker;\n"; ?>
<?php 
    foreach ($imported_models as $imported_model) {
        echo "use " . $imported_model . ";\n";
    }
?>

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

<div class="row">
<div class="col-md-8 col-sm-12">

    <?= "<?php " ?>$form = ActiveForm::begin(); ?>

<?php foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
        $column = $tableSchema->columns[$attribute];
        if ($column->name != 'created_at' && $column->name != 'updated_at' && $column->name != 'created_by' && $column->name != 'updated_by') {
            if (substr($column->name, -3) == '_id') {
                echo "    <?= \$form->field(\$model, '" . $column->name . "')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(" . Inflector::camelize(substr($column->name, 0, -3)) . "::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => ''],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>\n\n";
            } elseif ($column->type == 'date') {
                echo "    <?= \$form->field(\$model, '" . $column->name . "')->widget(DatePicker::classname(), [
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'readonly' => true,
        'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd'],
    ]); ?>\n\n";
            } else {
                echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
            }
        }
    }
} ?>    
    <div class="form-panel">
        <div class="row">
    	    <div class="col-sm-12">
    	        <?= "<?= " ?>Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? <?= $generator->generateString('Create') ?> : <?= $generator->generateString('Update') ?>), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
	    </div>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
</div>

</div>
