<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;

    use common\models\User;
    use frontend\models\Applicant;

    $this->title = $type . ' Applicants';
    $this->params['breadcrumbs'][] = ['label' => $centrename, 
        'url' => ['verify-applicants/centre-details', 'centre_id' => $centreid, 'centre_name' => $centrename]];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/verify-applicants']);?>" title="Process Applications">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/>

<h2 class="text-center"><?= $this->title;?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em">
    <div class="box-body">
        
    </div>
</div>



<div class="verify-applicants-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="css/dist/img/header_images/admissions.png" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="css/dist/img/header_images/admissions.png" alt="admission-avatar" class="pull-right">
            </a>    
        </div>
        
        <div class="custom_body">
            <h1 class="custom_h1"><?= Html::encode($this->title) ?></h1>
            
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'options' => [
                            'style' => 'width:95%; margin: 0 auto;' 
                        ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'personid',
                        'format' => 'html',
                        'label' => 'Applicant ID',
                        'value' => function($row) use($centrename, $centreid, $type)
                            {
                                $user = User::findOne(['personid' => $row["personid"]]);
                                $username = $user ? $user->username : $row["personid"];
                                
                                if (Yii::$app->user->can('Registrar') || Yii::$app->user->can('Assistant Registrar') || Yii::$app->user->can('Registry Staff')  ||Applicant::isVerified($user->personid) == false)
                                {
                                    return Html::a($username, 
                                           Url::to(['verify-applicants/view-applicant-qualifications', 'applicantid' => $row["personid"],
                                               'centrename' => $centrename, 'cseccentreid' =>$centreid, 'type' =>$type]));
                                }
                                else
                                {
                                    return $username;
                                }
                            }
//                        'value' => function($model) use($centrename, $centreid, $type)
//                            {
//                                $user = User::findOne(['personid' => $model->personid]);
//                                $username = $user ? $user->username : $model->personid;
//                                
//                                if (Yii::$app->user->can('Registrar') || Yii::$app->user->can('Assistant Registrar') || Applicant::isVerified($user->personid) == false)
//                                {
//                                    return Html::a($username, 
//                                           Url::to(['verify-applicants/view-applicant-qualifications', 'applicantid' => $model->personid,
//                                               'centrename' => $centrename, 'cseccentreid' =>$centreid, 'type' =>$type]));
//                                }
//                                else
//                                {
//                                    return $username;
//                                }
//                            }
                    ],
                    [
                        'attribute' => 'firstname',
                        'format' => 'text',
                        'label' => 'First Name'
                    ],
                    [
                        'attribute' => 'middlename',
                        'format' => 'text',
                        'label' => 'Middle Name(s)',
                    ],
                    [
                        'attribute' => 'lastname',
                        'format' => 'text',
                        'label' => 'Last Name',
                    ],
                    [
                        'attribute' => 'gender',
                        'format' => 'text',
                        'label' => 'Gender'
                    ], 
                    [
                        'attribute' => 'division',
                        'format' => 'text',
                        'label' => 'Division'
                    ],
                    [
                        'attribute' => 'verifier',
                        'format' => 'text',
                        'label' => 'Verifying Officer'
                    ], 
                ],
            ]); ?>
        </div>
    </div>
</div>