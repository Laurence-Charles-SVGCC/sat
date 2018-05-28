<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    
    use common\models\User;
    use frontend\models\ApplicationPeriod;
    use frontend\models\PackageType;
    use frontend\models\PackageProgress;
    use frontend\models\Package;
    
    $this->title = 'Package Testing';
    
    $this->params['breadcrumbs'][] = ['label' => 'Packages', 'url' => Url::toRoute(['/subcomponents/admissions/package'])];
    if ($recordid == true)
        $this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['/subcomponents/admissions/package/initiate-package', 'recordid' => $recordid])];
    else
        $this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['/subcomponents/admissions/package'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
     <?=Html::beginForm();?>
        <div class="box-body">
             <div>
                Select the number of recepients you wish to receive this test package:
                <?= Html::dropDownList ( 'email_count',
                                        '0',
                                        [
                                            0 => '0',
                                            1 => '1',
                                            2 => '2',
                                            3 => '3'
                                        ], 
                                        [
                                            'id' => 'email_count',
                                            'class' => 'form_field',
                                            'onchange' => 'toggleEmailFields();'
                                        ] );
                ?>
            </div><br/>
                    
            <div class="form-group" id="email_1" style="display:none">
                <label>Email Address #1: </label>
                <?=Html::textInput('email-1', null, ['id' => 'email-1', 'style' => 'width:50%'] )?>
            </div><br/>

            <div  class="form-group" id="email_2" style="display:none">
                <label>Email Address #2: </label>
                 <?=Html::textInput('email-2', null, ['id' => 'email-2', 'style' => 'width:50%'] )?>
            </div><br/>

            <div  class="form-group" id="email_3" style="display:none">
                <label>Email Address #3: </label>
                 <?=Html::textInput('email-3', null, ['id' => 'email-3','style' => 'width:50%',] )?>
            </div>
        </div>
    
        <div class="box-footer">
            <span class = "pull-right" id ="submit-button" style="display:none">
                <?=Html::submitButton(' Test', ['class' => 'btn btn-success', 'style' => 'margin-right:20px;']);?>
                <?= Html::a(' Back', ['package/initiate-package', 'recordid' => $recordid], ['class' => 'btn  btn-danger']);?>   
            </span>
        </div>
    <?=Html::endForm();?>
</div>