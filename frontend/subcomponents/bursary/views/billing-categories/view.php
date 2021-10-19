<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Billing Category Details";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Configurations", "url" => ["configurations/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Billing Categories", "url" => ["index"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<div class="box box-primary table-responsive no-padding">
    <div class="box-body">
        <table class="table table-striped">
            <tr>
                <th>Name</th>
                <td><?= $model->name ?></td>
            </tr>

            <tr>
                <th>Scope</th>
                <td><?= $modelScope ?></td>
            </tr>
        </table>
    </div>
</div>