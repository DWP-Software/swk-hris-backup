<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */

use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$extenderModelClass = StringHelper::basename($generator->extenderModelClass);
if ($modelClass === $extenderModelClass) {
    $modelAlias = $modelClass . 'Model';
}
$rules = $generator->generateSearchRules();
$labels = $generator->generateSearchLabels();
$searchAttributes = $generator->getSearchAttributes();
$searchConditions = $generator->generateSearchConditions();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->extenderModelClass, '\\')) ?>;

use Yii;
use yii\base\Model;
use <?= ltrim($generator->modelClass, '\\') . (isset($modelAlias) ? " as $modelAlias" : "") ?>;

/**
 * <?= $extenderModelClass ?> is extended model class for "<?= $modelClass ?>".
 */
class <?= $extenderModelClass ?> extends <?= isset($modelAlias) ? $modelAlias : $modelClass ?>

{
    
}
