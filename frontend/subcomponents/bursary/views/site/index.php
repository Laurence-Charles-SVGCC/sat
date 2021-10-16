<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Bursary Dashboard';
?>

<div class="row">
    <div class="col-sm-4 col-md-4">
        <div class="thumbnail" style="min-height:100px">
            <div class="caption text-center">
                <?=
                    Html::a(
                        '<h3>Find Account</h3>',
                        Url::toRoute(['profiles/search'])
                    );
                ?>
                <p>
                    Find applicant and student accounts.
                </p>
            </div>
        </div>
    </div>

    <div class="col-sm-4 col-md-4">
        <div class="thumbnail" style="min-height:100px">
            <div class="caption text-center">
                <?=
                    Html::a(
                        '<h3>Fee Catalog</h3>',
                        Url::toRoute(['fees/index'])
                    );
                ?>
                <p>
                    Manage applicant and registration fee listings.
                </p>
            </div>
        </div>
    </div>

    <?php if (Yii::$app->user->can("System Administrator") == true) : ?>
        <div class="col-sm-4 col-md-4">
            <div class="thumbnail" style="min-height:100px">
                <div class="caption text-center">
                    <?=
                        Html::a(
                            '<h3>Configurations</h3>',
                            Url::toRoute(['configurations/index'])
                        );
                    ?>
                    <p>
                        Manage reference tables.
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="row">
    <div class="col-sm-4 col-md-4">
        <div class="thumbnail" style="min-height:100px">
            <div class="caption text-center">
                <?=
                    Html::a(
                        '<h3>Reports</h3>',
                        Url::toRoute(['reports/index'])
                    );
                ?>
                <p>
                    Generate transaction reports.
                </p>
            </div>
        </div>
    </div>
</div>