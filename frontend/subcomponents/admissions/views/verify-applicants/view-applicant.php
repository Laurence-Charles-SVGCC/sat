<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CsecCentreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $type . ' Applicants';
$this->params['breadcrumbs'][] = ['label' => $centrename, 
    'url' => ['verify-applicants/centre-details', 'centre_id' => $centreid, 'centre_name' => $centrename]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="verify-applicants-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
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
                       return Html::a($username, 
                               Url::to(['verify-applicants/view-applicant-qualifications', 'applicantid' => $model->personid,
                                   'centrename' => $centrename, 'cseccentreid' =>$centreid, 'type' =>$type]));
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