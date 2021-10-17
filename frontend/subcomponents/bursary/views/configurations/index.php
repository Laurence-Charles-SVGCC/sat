<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Configurations";

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<ol>
    <li>
        <?=
            Html::a(
                "Billing Types",
                Url::toRoute(["billing-types/index"]),
                ["id" => "billing-types-button"]
            );
        ?>
    </li>

    <li>
        <?=
            Html::a(
                "Billing Categories",
                Url::toRoute(["billing-categories/index"]),
                ["id" => "billing-categories-button"]
            );
        ?>
    </li>

    <li>
        <?=
            Html::a(
                "Billing Scopes",
                Url::toRoute(["billing-scopes/index"]),
                ["id" => "billing-scopes-button"]
            );
        ?>
    </li>

    <li>
        <?=
            Html::a(
                "Payment Methods",
                Url::toRoute(["payment-methods/index"]),
                ["id" => "payment-methods-button"]
            );
        ?>
    </li>
</ol>