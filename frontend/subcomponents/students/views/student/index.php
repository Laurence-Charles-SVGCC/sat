<?php
    use yii\helpers\Html;

    $this->title = 'SVGCC Administrative Terminal';
?>

<div class="site-index">

    <div class="jumbotron">
        <h1>Students Dashboard</h1>
        <h2>Select a Task</h2>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <?php if (Yii::$app->user->can('manageStudents')): ?>
                    <?= Html::a('Manage Students', ['student/manage-students'], ['class' => 'btn btn-success']) ?>
                <?php endif; ?>
            </div>
            <div class="col-lg-4">
                <?php if (Yii::$app->user->can('searchStudents')): ?>
                    <?= Html::a('Search Students', ['student/search-students'], ['class' => 'btn btn-success']) ?>
                <?php endif; ?>
            </div>
        </div>
        <br/>
    </div>
</div>
