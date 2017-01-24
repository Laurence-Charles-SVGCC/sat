<?php
    use yii\widgets\Breadcrumbs;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\helpers\ArrayHelper;

    use frontend\models\ExaminationBody;
    use frontend\models\ExaminationProficiencyType;
    use frontend\models\Subject;
    use frontend\models\ExaminationGrade;
    use frontend\models\ApplicationStatus;
    use frontend\models\EmployeeDepartment;
    use frontend\models\DocumentType;

    $this->title = 'Update Documents';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find Applicant', 'url' => Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => $search_status])];
    $this->params['breadcrumbs'][] = ['label' => 'Applicant Profile', 'url' => Url::toRoute(['/subcomponents/admissions/view-applicant/applicant-profile', 'search_status' => $search_status, 'applicantusername' => $user->username])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><strong><?= $this->title?></strong>: Select from the following list which documents the applicant has presented.</span>
     </div>
    
    <?php $form = ActiveForm::begin();?>
        <div class="box-body">
            <h3><strong>Enrollment Documents Checklist</strong></h3>
            
            <div class="form-group ">
<!--            <div class="row">-->
                 <!--<div class="col-lg-3">-->
                 <div class = "no-padding col-xs-3 col-sm-3 col-md-3 col-lg-2">
                    <?= Html::checkboxList('documents', 
                                            $selections, 
                                            ArrayHelper::map(DocumentType::findAll(['isactive' => 1, 'isdeleted' => 0]),
                                            'documenttypeid', 
                                            'name'));
                    ?>
                </div>
            </div>
        </div>

         <div class="box-footer">
            <span class = "pull-right">
                <?= Html::submitButton(' Submit', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']);?>
                <?= Html::a(' Cancel', ['view-applicant/applicant-profile',  'search_status' => $search_status,  'applicantusername' => $user->username], ['class' => 'btn  btn-danger']);?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>