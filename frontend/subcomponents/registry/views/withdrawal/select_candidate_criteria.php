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
                <a href="<?= Url::toRoute(['/subcomponents/registry/withdrawl/index', 'new' => 1]);?>" title="Find A Student">     
                    <img class="custom_logo_students" src ="css/dist/img/header_images/sms_4.png" alt="student avatar">
                    <span class="custom_module_label">Welcome to the Withdrawal Management</span> 
                    <img src ="css/dist/img/header_images/sms_4.png" alt="student avatar" class="pull-right">
                </a>       
            </div>
        
            <div class="custom_body">
                <h1 class="custom_h1"><?= $this->title?></h1>
                
                <div style="width:95%; margin: 0 auto"><br/>
                    <div>
                        <?php $form = ActiveForm::begin(
                                    [
//                                        'action' => Url::to(['withdrawal/index']),
                                        'action' => Url::to(['withdrawal/generate-withdrawal-candidates']),
                                    ]); 
                        ?>
                            
                            <div >
                                <?= Html::label('Select application period you wish to generate withdrawal candidate list for: ', 'period_id_label'); ?>
                                <?= Html::dropDownList('period-id',  "Select...", $periods, ['id' => 'period_id_field', 'onchange' => 'toggleSubmitButton();']) ; ?>

                                <?= Html::submitButton('Generate', ['class' => 'btn btn-md btn-success pull-right', 'style' => 'margin-right:5%; display:none', 'id' => 'withdrawal-submit-button']) ?>
                             </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>