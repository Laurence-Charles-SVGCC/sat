<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

use common\models\User;
use frontend\models\Applicant;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CsecCentreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $type . ' Applicants';
$this->params['breadcrumbs'][] = ['label' => $centrename, 
    'url' => ['verify-applicants/centre-details', 'centre_id' => $centreid, 'centre_name' => $centrename]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="verify-applicants-index">
    <div class = "custom_wrapper">
        <div class="custom_header">
            <a href="<?= Url::toRoute(['/subcomponents/admissions/admissions/index']);?>" title="Admissions Home">     
                <img class="custom_logo_students" src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar">
                <span class="custom_module_label">Welcome to the Admissions Management System</span> 
                <img src ="<?=Url::to('../images/admissions.png');?>" alt="admission-avatar" class="pull-right">
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
                        'value' => function($model) use($centrename, $centreid, $type)
                            {
                                $user = User::findOne(['personid' => $model->personid]);
                                $username = $user ? $user->username : $model->personid;
                                
                                if (Yii::$app->user->can('Registrar') || Yii::$app->user->can('Assistant Registrar') || Applicant::isVerified($user->personid) == false)
                                {
                                    return Html::a($username, 
                                           Url::to(['verify-applicants/view-applicant-qualifications', 'applicantid' => $model->personid,
                                               'centrename' => $centrename, 'cseccentreid' =>$centreid, 'type' =>$type]));
                                }
                                else
                                {
                                    return $username;
                                }
                            }
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
                ],
            ]); ?>
        </div>
    </div>
</div>