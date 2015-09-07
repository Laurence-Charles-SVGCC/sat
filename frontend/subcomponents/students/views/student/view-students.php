<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CsecCentreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Students';
/*$this->params['breadcrumbs'][] = ['label' => $centrename, 
    'url' => ['verify-applicants/centre-details', 'centre_id' => $centreid, 'centre_name' => $centrename]];*/
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="verify-applicants-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'studentno',
                'format' => 'html',
                'label' => 'Studentnt No.',
                'value' => function($row)
                    { 
                        return Html::a($row['studentno'], 
                                        Url::to(['student/view-student', 'studentid' => $row['studentid'], 'username' => $row['studentno']]));
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
            [
                'attribute' => 'dob',
                'format' => 'text',
                'label' => 'Date of Birth'
            ],
            [
                'attribute' => 'studentmail',
                'format' => 'text',
                'label' => 'Student Mail'
            ],         
            [
                'attribute' => 'admissiondate',
                'format' => 'text',
                'label' => 'Admission Date'
            ],
            [
                'attribute' => 'applicantno',
                'format' => 'text',
                'label' => 'Applicant ID'
            ],        
        ],
    ]); ?>

</div>