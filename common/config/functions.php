<?php

/**
 * Debug function
 * d($var);
 */
function d($var)
{
    echo '<pre>';
    yii\helpers\VarDumper::dump($var, 10, true);
    echo '</pre>';
}

/**
 * Debug function with die() after
 * dd($var);
 */
function dd($var)
{
    d($var);
    die();
} 

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function months() 
{
    $months = [];
    for ($i = 1; $i <= 12 ; $i++) { 
        $months[$i] = date('F', mktime(0, 0, 0, $i, 10));
    }
    return $months;
}

function years() 
{
    $years = [];
    for ($i = date('Y'); $i >= 2018 ; $i--) { 
        $years[$i] = $i;
    }
    return $years;
}

function monthName($month) 
{
    return date('F', mktime(0, 0, 0, $month, 10));
}

function monthsRoman($month)
{
    $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
    return $romans[date($month)-1];
}

function uploadFile($model, $field, $uploadedFile)
{
    $filename = $model->id .'.'. $uploadedFile->extension;
    $directory = Yii::getAlias(Yii::$app->params['storage'].$model->tableName().'/'.$field);
    if (!file_exists($directory)) mkdir($directory, 0777, true);
    $uploadedFile->saveAs($directory.'/'.$filename);
    $model->$field = $filename;
    $model->save();
}

function parsePhone($phone)
{
    if ($phone) {
        if (substr($phone,0,1) == '+')  $phone = $phone;
        if (substr($phone,0,2) == '62') $phone = '+'.$phone;
        if (substr($phone,0,1) >= '1')  $phone = '+62'.$phone;
        if (substr($phone,0,1) == '0')  $phone = '+62'.ltrim($phone, '0');
    }
    return $phone;
}

function areaFields()
{
    return [
        'province_id',
        'district_id',
        'subdistrict_id',
        'village_id',
    ];
}

function dateDiff($dateString1, $dateString2, $days = 1)
{
    $datetime1 = new DateTime($dateString1);
    $datetime2 = new DateTime($dateString2);
    $difference = $datetime1->diff($datetime2);
    return $days ? $difference->days : $difference;
}