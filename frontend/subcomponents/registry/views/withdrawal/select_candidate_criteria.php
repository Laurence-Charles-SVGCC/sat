<?php

/* 
 * Author: Laurence Charles
 * Date Created: 04/12/2015
 * Date Last Modified: 07/12/2015
 */

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\grid\GridView;
    
    use frontend\models\Department;
    
    /* @var $this yii\web\View */
    $this->title = 'Withdrawal Listing Generation';
    $this->params['breadcrumbs'][] = $this->title;
?>

    <div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/students/student/find-a-student']);?>" title="Find A Student">     
                    <img class="custom_logo_students" src ="css/dist/img/header_images/sms_4.png" alt="student avatar">
                    <span class="custom_module_label">Welcome to the Student Management System</span> 
                    <img src ="css/dist/img/header_images/sms_4.png" alt="student avatar" class="pull-right">
                </a>    
            </div>
        
            <div class="custom_body">
                <h1 class="custom_h1"><?= $this->title?></h1>
                
                <div style="width:95%; margin: 0 auto"><br/>
                    <div>
                        <?php $form = ActiveForm::begin(
                                    [
                                        'action' => Url::to(['withdrawal/index']),
                                    ]); 
                        ?>
                            <?= Html::hiddenInput('application_periodid', $application_periodid); ?>
                        
                            <div >
                                <?= Html::label('Select application period you wish to generate withdrawal candidate list for: ', 'period_id_label'); ?>
                                <?= Html::dropDownList('period-id',  "Select...", $periods, ['id' => 'period_id_field', 'onchange' => 'toggleSubmitButton();']) ; ?>

                                <?= Html::submitButton('Generate', ['class' => 'btn btn-md btn-success pull-right', 'style' => 'margin-right:5%; display:none', 'id' => 'withdrawal-submit-button']) ?>
                             </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    
                    <?php if ($dataProvider) : ?>
                        <hr><h2 class="custom_h2" style="margin-left:0px"><?= $title ?></h2>
                        
                        <?= $this->render('withdrawal_candidate_result', [
                                'dataProvider' => $dataProvider,
                                'title' => $title,
                                'filename' => $filename,
                            ])
                        ?>
                    <?php endif; ?>
                        
                    <?php if ($dataProvider && (Yii::$app->user->can('System Administrator')  || Yii::$app->user->can('Registrar'))) : ?> 
                        <hr><h2 class="custom_h2" style="margin-left:0px">Student Promotion</h2>
                        
                        <ul>
                            <li>
                                Click the button below to perform the promotion of students from Level 1 to Level 2.
                            </li>
                            <li>
                                Please ensure you would have updated the statuses of the students appearing
                                on the above list to <strong>Academic Withdrawal</strong> or <strong>Probationary Retention</strong>.
                            </li>
                            <li>
                                Clicking the button will ensure that all students that have not been withdrawn;
                                have their 'Current Level' updated to Level 2.
                            </li>
                        </ul><br/>
                        
                        <div>
                            <a class="btn btn-success pull-left" style="width: 40%;margin-left:5%;margin-right: 5%;font-size:3 em;" href=<?=Url::toRoute(['/subcomponents/registry/withdrawal/promote-students', 'applicationperiodid' => $application_periodid]);?> role="button">  Promote Students</a>
                            <a class="btn btn-warning" style="width: 40%;font-size:3 em;" href=<?=Url::toRoute(['/subcomponents/registry/withdrawal/undo-promotions', 'applicationperiodid' => $application_periodid]);?> role="button">  Undo Promotions</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>