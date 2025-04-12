<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

return [
    'status' => 'error',
    'name' => $name,
    'message' => $message,
    'code' => $exception->getCode(),
]; 