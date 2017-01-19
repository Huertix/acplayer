<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();

$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => __DIR__ . '/db/app.sqlite',
    'prefix' => '',

], 'default');

$capsule->bootEloquent();
$capsule->setAsGlobal();