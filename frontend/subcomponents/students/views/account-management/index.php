<?php

/* 
 * Author: Laurence Charles
 * Date Created: 09/12/2015
 */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    /* @var $this yii\web\View */
    $this->title = 'Custom Student Listing';
    $this->params['breadcrumbs'][] = $this->title;
?>

    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">     
                    <img class="custom_logo_students" src ="<?=Url::to('../images/sms_4.png');?>" alt="Find A Student">
                    <span class="custom_module_label">Welcome to the Student Management System</span> 
                    <img src ="<?=Url::to('../images/sms_4.png');?>" alt="student avatar" class="pull-right">
                </a>   
            </div>
        
            <div class="custom_body"> 
                <h1 class="custom_h1"><?= $this->title?></h1>
                
                <br/>
                <a class="btn btn-success glyphicon glyphicon-plus pull-right" style="margin-right:2%" href=<?=Url::toRoute(['/subcomponents/students/account-management/account-dashboard']);?> role="button"> Create New Account</a>
                <br/>
                
                
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
                ]); ?> 
            </div>
        </div>
    </div>