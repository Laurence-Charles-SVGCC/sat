<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;

     $this->title = $programme_name . ' Course Catalog';
     $this->params['breadcrumbs'][] = ['label' => 'Graduation Requirements', 'url' => ['programme-graduation-requirements']];
     $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
    </div>
    
    <div class="alert alert-info" style = "width:98%; margin: 0 auto">
        The following course catalog lists the course required to graduation from the stated programme.
    </div><br/>
    
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $course_catalog_dataprovider,
            'columns' => 
                [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'coursecode',
                        'format' => 'text',
                        'label' => 'Course Code'
                    ],
                    [
                        'attribute' => 'name',
                        'format' => 'text',
                        'label' => 'Name'
                    ],
                ],
            ]); 
        ?>
     </div>
</div>