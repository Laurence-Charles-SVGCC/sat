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


    $this->title = 'Verify Document Submission';
    $this->params['breadcrumbs'][] = ['label' => 'Applicant:' . $applicant->firstname . " " . $applicant->lastname, 
        'url' => ['view-applicant-qualifications', 'applicantid' => $applicantid,  'centrename' => $centrename, 'cseccentreid' => $centreid, 'type' => $type]];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/verify-applicants']);?>" title="Process Applications">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/>

<h2 class="text-center"><?= $this->title;?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.1em; width:98%; margin:0 auto">
    <div class="box-body">
        <div>
            <p><strong>Applicant ID:</strong><?= $username; ?></p>
            <p><strong>Applicant Name:</strong><?= $applicant->title . ". " .  $applicant->firstname . " " . $applicant->middlename . " " . $applicant->lastname ;?></p><br/>
        </div>
            
        <fieldset>
            <legend><strong>Verify Document</strong></legend>
                <?php 
                    $form = ActiveForm::begin(
                        [
                            'action' => Url::to(['verify-applicants/verify-documents',  'applicantid' => $applicantid,  'centrename' => $centrename, 'cseccentreid' => $centreid, 'type' => $type, 'personid' => $applicant->personid]),
                        ]); 
                ?>

                    <p>Select from the following list which documents the applicant presented during application.</p>
                    <div class="row">
                        <div class="col-lg-3">
                            <?= Html::checkboxList('documents', 
                                                    $selections, 
                                                    ArrayHelper::map(DocumentType::findAll(['isdeleted' => 0]),
                                                    'documenttypeid', 
                                                    'name'));
                            ?>
                        </div>
                    </div>

                    <div class="box-footer pull-right"><br/>
                         <?= Html::submitButton(' Verify Selection', ['class' => 'btn btn-success', 'style' => 'margin-right:20px']) ?>
                            <?=Html::a(' Back', 
                                                ['verify-applicants/view-applicant-qualifications', 'applicantid' => $applicantid,  'centrename' => $centrename, 'cseccentreid' => $centreid, 'type' => $type], 
                                                ['class' => 'btn btn-danger']);
                            ?> 
                    </div>
                <?php ActiveForm::end(); ?>
        </fieldset>
    </div>
</div>