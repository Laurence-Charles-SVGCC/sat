<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    $this->title = 'Custom Student Listing';
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
     <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
        <a class="btn btn-info pull-right" href=<?=Url::toRoute(['/subcomponents/students/account-management/account-dashboard']);?> role="button"> Create</a>
    </div>
    
    <div class="box-body">
        <table class="table table-hover">
            <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'options' => [
                        'style' => 'margin:0 auto; width:98%'
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'format' => 'html',
                            'label' => 'Username',
                            'value' => function($row)
                                {
                                    if($row['iscomplete'] == 0)
                                    {
                                        return Html::a($row['username'], 
                                            Url::to(['account-management/account-dashboard', 'recordid' => $row['recordid']]));
                                    }
                                    else
                                    {
                                        if($row['studentregistrationid'] == true)
                                        { 
                                            return Html::a($row['username'], 
                                                Url::toRoute(['/subcomponents/students/profile/student-profile', 'personid' => $row['personid'], 'studentregistrationid' => $row['studentregistrationid']]));
                                        }
                                        else
                                        {
                                            return $row['username'];
                                        }
                                    }
                                }
                        ],
                        [
                            'attribute' => 'title',
                            'format' => 'text',
                            'label' => 'Title'
                        ],
                        [
                            'attribute' => 'firstname',
                            'format' => 'text',
                            'label' => 'First Name'
                        ],
                        [
                            'attribute' => 'middlename',
                            'format' => 'text',
                            'label' => 'Middle Name(s)'
                        ],
                        [
                            'attribute' => 'lastname',
                            'format' => 'text',
                            'label' => 'Last Name'
                        ],
                        [
                            'attribute' => 'progress',
                            'format' => 'text',
                            'label' => 'progress'
                        ],
                    ],
                ]); 
            ?> 
        </table>
    </div>
</div>