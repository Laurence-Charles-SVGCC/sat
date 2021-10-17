<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Payment Method Details";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Configurations", "url" => ["configurations/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Payment Methods", "url" => ["index"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div class="box box-primary table-responsive no-padding">
    <div class="box-header"></div>

    <div class="box-body">
        <table class="table table-striped">
            <tr>
                <th>Name</th>
                <td><?= $model->name ?></td>
            </tr>
        </table>
    </div>
</div>