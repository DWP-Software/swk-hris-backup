<?php

use common\models\entity\ActiveContract;
use common\models\entity\Allocation;
use common\models\entity\Bundle;
use common\models\entity\Coupon;
use common\models\entity\Distribution;
use common\models\entity\Employee;
use common\models\entity\Item;
use common\models\entity\Client;
use common\models\entity\LatestContract;
use common\models\entity\LatestContractPlacement;
use common\models\entity\Log;
use common\models\entity\Payment;
use common\models\entity\Payroll;
use common\models\entity\PayrollDetail;
use common\models\entity\User;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;

?>

<?= $this->render('_dashboard-Rekrutmen') ?>
<?= $this->render('_dashboard-Kehadiran') ?>
<?= $this->render('_dashboard-Keuangan') ?>