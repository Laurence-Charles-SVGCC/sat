<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Reporting Dashboard';
?>

<h1><?= $this->title ?></h1>

<div class="row">
    <div class="col-sm-4 col-md-4">
        <div class="thumbnail" style="min-height:100px">
            <div class="caption text-center">
                <?=
                Html::a(
                    '<h3>Quality Assurance</h3>',
                    Url::toRoute(['quality-assurance/index'])
                );
                ?>
                <p>
                    View quality assurance reporting options.
                </p>
            </div>
        </div>
    </div>
</div>