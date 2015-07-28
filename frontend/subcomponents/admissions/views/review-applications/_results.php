<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

?>
<div class="verify-applicants-index">
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'format' => 'html',
                'label' => 'Applicant ID',
                'value' => function($row) use ($application_status)
                    {
                        $middlename = $row['middlename'] ? $row['middlename'] : "";
                       return Html::a($row['applicantid'], 
                               Url::to(['review-applications/view-applicant-certificates', 'applicantid' => $row['applicantid'],
                                   'applicationid' => $row['applicationid'], 'firstname' => $row['firstname'], 'middlename' =>$middlename , 
                                   'lastname' => $row['lastname'], 'programme' => $row['programme'], 'application_status' => $application_status]));
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
                'label' => 'Middle Name(s)'
            ],
            [
                'attribute' => 'lastname',
                'format' => 'text',
                'label' => 'Last Name'
            ],
            [
                'attribute' => 'programme',
                'format' => 'text',
                'label' => 'Programme'
            ],
            [
                'attribute' => 'subjects_no',
                'format' => 'text',
                'label' => 'No. of Subjects'
            ],
            [
                'attribute' => 'ones_no',
                'format' => 'text',
                'label' => 'No. of Ones'
            ],
            [
                'attribute' => 'twos_no',
                'format' => 'text',
                'label' => 'No. of Twos'
            ],
            [
                'attribute' => 'threes_no',
                'format' => 'text',
                'label' => 'No. of Threes'
            ],
        ],
    ]); ?>

</div>