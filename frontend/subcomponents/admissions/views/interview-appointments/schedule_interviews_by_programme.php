<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use common\models\User;
     use frontend\models\Application;
    use frontend\models\ProgrammeCatalog;
     use frontend\models\Applicant;
    
    $this->title = $programme_name . " Interview Schedule";
    $this->params['breadcrumbs'][] = ['label' => 'Control Panel', 'url' => Url::toRoute(['/subcomponents/admissions/interview-appointments'])];
    $this->params['breadcrumbs'][] =  $this->title;
?>


<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/>

<h2 class="text-center"><?= $this->title?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
         <strong>
            <span class="pull-left"><?= "Programme: " . $programme_name;?></span>
            <span class="pull-right"><?= "Number of Offers: " . count($offers) ;?></span>
        </strong>
    </div>
    
    <div class="box-body">
        <div class="alert alert-info">
            Please ensure you enter date and time into the appointment field. What you enter will be copied verbatim into the applicant's
            interview invitation email. <br/>
            Suggest Format : [Day] the [Date] of [Month] at [Time]<br/>
            Appointment Sample: <strong> "Monday the 18th of May at 9:00 am"</strong>
        </div>
        
        <div class="container-items">
            <?php  $form = ActiveForm::begin();  ?>
                <table id="certificate_table" class="table table-bordered table-striped" style="width:100%; margin: 0 auto">
                     <thead>
                        <tr>
                          <th></th>
                          <th>Applicant ID</th>
                          <th>Firstname</th>
                          <th>Lastname</th>
                          <th>Programme</th>
                          <th>Appointment</th>
                        </tr>
                    </thead>
                     
                    <tbody>
                        <?php for ($i = 0 ; $i <count($offers)  ; $i++):?>
                            <?php
                                $application = Application::find()->where(['applicationid' => $offers[$i]->applicationid])->one();
                                $applicant =  Applicant::find()->where(['personid' =>  $application->personid])->one();
                                $user = User::find()->where(['personid' => $application->personid])->one();
                                $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid])->getFullName() ;
                            ?>
                            <tr>
                                <?= Html::activeHiddenInput($offers[$i], "[{$i}]offerid"); ?>
                                <td><?= $i + 1;?></td>
                                <td><?= $user->username;?></td>
                                <td><?= $applicant->firstname;?></td>
                                <td><?= $applicant->lastname;?></td>
                                <td><?= $programme;?></td>
                                <td><?= $form->field($offers[$i], "[{$i}]appointment")->label("")->textInput(['maxlength' => true, 'style'=> 'font-size:14px;']) ?></td>
                             </tr>
                        <?php endfor;?>
                    </tbody>
                </table>
            
                <?php if ($offers == true):?>
                    <div>
                        <span class="pull-right">
                            <?= Html::submitButton(' Update', ['class' => 'btn btn-success']);?>
                            <?=Html::a(' Cancel',  ['admissions/interview-appointments'], ['class' => 'btn btn-danger', 'style' => 'margin-left: 30px;']);?>
                        </span>
                    </div>
            <?php endif;?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>