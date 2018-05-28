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

    $this->title = 'Verify Applicant Documents';
    
    $this->params['breadcrumbs'][] = ['label' => 'Find Applicant', 'url' => Url::toRoute(['/subcomponents/admissions/admissions/find-current-applicant', 'status' => 'submitted-unlimited'])];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title;?></span>
    </div>

    <?php $form = ActiveForm::begin(); ?>
        <div class="box-body" style = "width:98%; margin: 0 auto;">
            <div>
                <p><strong>Applicant ID:</strong><?= $username; ?></p>
                <p><strong>Applicant Name:</strong><?= $applicant->title . ". " .  $applicant->firstname . " " . $applicant->middlename . " " . $applicant->lastname ;?></p>
            </div><br/>
            
            <fieldset>
                <legend>Documents Checklist</legend>
                <p>Select from the following list which documents the applicant presented.</p>
                <div class="row">
                    <div class="col-md-3">
                        <?= Html::checkboxList('documents', $selections, ArrayHelper::map(DocumentType::findAll(['isdeleted' => 0]),  'documenttypeid', 'name'));?>
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="box-footer">
            <span class = "pull-right">
                <?php if (Yii::$app->user->can('registerStudent')): ?>
                    <?= Html::submitButton(' Verify', ['class' => 'btn  btn-success']) ?>
                <?php endif; ?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>
</div>