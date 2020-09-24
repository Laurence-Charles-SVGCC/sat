<?php

  use yii\helpers\Html;
  use yii\helpers\Url;
  use yii\grid\GridView;

  $relation_count = [
    0 => '0',
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
    7 => '7',
    8 => '8',
    9 => '9',
    10 => '10',
  ];

  $has_worked = [1 => 'Yes',0 => 'No'];

  $is_working = [1 => 'Yes', 0 => 'No'];

  $nursing_experience = [1 => 'Yes', 0 => 'No'];

  $teaching_experience = [1 => 'Yes', 0 => 'No'];

  $financing_options = [
    'Father' => 'Father',
    'Mother' => 'Mother',
    'Self' => 'Self',
    'Other' => 'Other'
  ];

  $student_loan = [1 => 'Yes', 0 => 'No'];

  $sponsorship_request = [
    'Grant' => 'Grant',
    'Sponsohip' => 'Sponsoship',
    'Both' => 'Both',
    'Neither' => 'Neither'
  ];

  $titles = ['' => 'Title', 'Mr' => 'Mr', 'Ms' => 'Ms', 'Mrs' => 'Mrs'];

  $has_criminalrecord = [1 => 'Yes', 0 => 'No'];

?>

<div role="tabpanel" class="tab-pane" id="dte-additional-details">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4>Additional Information</h4>
    </div>

    <div class="panel-body"></br>
      <div class="panel panel-default">
        <?php if (Yii::$app->user->can('verifyApplicants')  || Yii::$app->user->can('viewAdditionalDetailsData')):?>
          <h3 style='text-align:center'>General Work Experience</h3>
          <?php if ($general_work_experience == false): ?>
            <div class='panel-heading'>
              <strong>
                Applicant has not indicated that they have prior
                general work experience
              </strong>
            </div>
          <?php else:?>
            <?php for ($i = 0 ; $i < count($general_work_experience) ; $i++): ?>
              <div class='panel-heading'>
                <h4><?= $general_work_experience[$i]->role; ?></h4>
              </div>

              <table class='table table-hover' style='margin: 0 auto;'>
                <tr>
                  <th>Employer</th>
                  <td><?= $general_work_experience[$i]->employer; ?></td>
                  <th>Employer Address</th>
                  <td><?= $general_work_experience[$i]->employeraddress; ?></td>
                </tr>

                <tr>
                  <th>Nature of Duties</th>
                  <td><?= $general_work_experience[$i]->natureofduties; ?></td>
                  <th>Salary</th>
                  <td><?= $general_work_experience[$i]->salary; ?></td>
                </tr>

                <tr>
                  <th>Start Date</th>
                  <td><?= $general_work_experience[$i]->startdate; ?></td>
                  <th>End Date</th>
                  <td style='height:65px'>
                    <?= $general_work_experience[$i]->enddate; ?>
                  </td>
                </tr>
              </table>
            <?php endfor; ?>
          <?php endif; ?>
        <?php endif; ?>
      </div></br>

      <div class="panel panel-default">
        <?php if (Yii::$app->user->can('verifyApplicants')  || Yii::$app->user->can('viewAdditionalDetailsData')):?>
          <h3 style='text-align:center'>References</h3>
          <?php if ($references == false): ?>
            <div class='panel-heading'>
              <strong>
                Applicant has not entered any references
              </strong>
            </div>";
          <?php else: ?>
            <?php for ($i = 0 ; $i < count($references) ; $i++): ?>
              <div class='panel-heading'>
                <h4>
                  <?= "{$references[$i]->title} {$references[$i]->firstname} {$references[$i]->lastname}";?>
                </h4>
              </div>

              <table class='table table-hover'>
                <tr>
                  <th>Address</th>
                  <td><?= $references[$i]->address; ?></td>
                  <th>Occupation</th>
                  <td><?= $references[$i]->occupation; ?></td>
                </tr>

                <tr>
                  <th>Contact Number</th>
                  <td><?= $references[$i]->contactnumber; ?></td>
                </tr>
              </table>
            <?php endfor; ?>
          <?php endif; ?>
        <?php endif; ?>
      </div></br>

      <div class="panel panel-default">
        <?php if (Yii::$app->user->can('verifyApplicants')  || Yii::$app->user->can('viewAdditionalDetailsData')): ?>
          <h3 style='text-align:center'>Teaching Experience</h3>
          <?php if ($teaching == false): ?>
            <div class='panel-heading'>
              <strong>
                Applicant has not indicated that they have prior teaching
                experience
              </strong>
            </div>
          <?php else: ?>
            <?php for ($i = 0 ; $i < count($teaching) ; $i++): ?>
              <div class='panel-heading'>
                <?= $teaching[$i]->institutionname?>
              </div>

              <table class='table table-hover'>
                <tr>
                  <th>Address</th>
                  <td><?= $teaching[$i]->address; ?></td>
                  <th>Start Date</th>
                  <td><?= $teaching[$i]->startdate; ?></td>
                </tr>

                <tr>
                  <th>Date of Appointment</th>
                  <td><?= $teaching[$i]->dateofappointment; ?></td>
                  <th>End Date</th>
                  <td><?= $teaching[$i]->enddate; ?></td>
                </tr>

                <tr>
                  <th>Class/Form</th>
                  <td><?= $teaching[$i]->classtaught; ?></td>
                  <th>Subject(s)</th>
                  <td style='height:65px'>
                    <?= $teaching[$i]->subject; ?></td>
                </tr>
              </table>
            <?php endfor; ?>
          <?php endif; ?>
        <?php endif; ?>
      </div></br>

      <?php if (Yii::$app->user->can('verifyApplicants')  || Yii::$app->user->can('viewAdditionalDetailsData')):?>
        <?php $form = yii\bootstrap\ActiveForm::begin() ?>
          <fieldset >
            <legend>Family Information</legend>
            <?=
                $form->field($teachinginfo, 'childcount')
                ->label("Number of children", ['class'=> 'form-label'])
                ->dropDownList($relation_count, ["disabled" => true]);
            ?>

            <?php if ($teachingApplicantHasChildren == true):?>
              <div id="ages">
                <?=
                  $form->field($teachinginfo, 'childages')
                  ->label("Ages of children", ['class'=> 'form-label'])
                  ->textArea(['rows' => '1', "disabled" => true])
                ?>
              </div>
            <?php endif ;?>

            <?=
              $form->field($teachinginfo, 'brothercount')
              ->label("Number of brothers", ['class'=> 'form-label'])
              ->dropDownList(
                  $relation_count,
                  ['id'=>'brotherCount', "disabled" => true]
              );
            ?>

            <?=
              $form->field($teachinginfo, 'sistercount')
              ->label("Number of sisters", ['class'=> 'form-label'])
              ->dropDownList(
                  $relation_count,
                  ['id'=>'sisterCount', "disabled" => true]
              );
            ?>
          </fieldset></br>

          <fieldset >
            <legend>Work Experience</legend>
            <?=
              $form->field($teachinginfo, 'yearcompletedschool')
              ->label('Year school was completed: *', ['class'=> 'form-label'])
              ->textInput(['maxlength' => true, "disabled" => true])
            ?>

            <?=
              $form->field($teachinginfo, 'hasworked')
              ->label(
                  "Have you worked since leaving school? *",
                  ['class'=> 'form-label']
              )
              ->inline()
              ->radioList($has_worked, ["itemOptions" => ["disabled" => true]]);
            ?>

            <?=
              $form->field($teachinginfo, 'isworking')
              ->label("Are you currently employed?", ['class'=> 'form-label'])
              ->inline()
              ->radioList($is_working, ["itemOptions" => ["disabled" => true]]);
            ?>

            <?=
                $form->field($teachinginfo, 'hasteachingexperience')
                ->label(
                    "Do you have had any previous teaching experience? *",
                    ['class'=> 'form-label']
                )
                ->inline()
                ->radioList(
                    $teaching_experience,
                    [
                        "teacher-experience",
                        "itemOptions" => ["disabled" => true]
                    ]
                );
            ?>
          </fieldset></br>

          <fieldset >
              <legend>Other</legend>
              <?=
                  $form->field($teachinginfo, 'hascriminalrecord')
                  ->label(
                      "Have your every been charged by the law for any offence? *",
                      ['class'=> 'form-label']
                  )
                  ->inline()
                  ->radioList(
                      $has_criminalrecord,
                      ["itemOptions" => ["disabled" => true]]
                  );
              ?>

              <?=
                  $form->field($teachinginfo, 'applicationmotivation')
                  ->label(
                      "Why do you want to enroll in this programme? *",
                      ['class'=> 'form-label']
                  )
                  ->textArea(["disabled" => true, "rows" => 7]);
              ?>

              <?=
                  $form->field($teachinginfo, 'additionalcomments')
                  ->label("Other Comments ", ['class'=> 'form-label'])
                  ->textArea(["disabled" => true, "rows" => 7]);
              ?>
          </fieldset></br>

          <fieldset >
              <legend>Financial Information</legend>
              <?=
                  $form->field($teachinginfo, 'benefactor')
                  ->label(
                      "How will your studies be financed? *",
                      ['class'=> 'form-label']
                  )
                  ->inline()
                  ->radioList(
                      $financing_options,
                      ["itemOptions" => ["disabled" => true]]
                  );
              ?>

              <?php if ($teachinginfo->benefactor == "Other"):?>
                  <?=
                      $form->field($teachinginfo, 'benefactordetails')
                      ->label('Specify Financer', ['class'=> 'form-label'])
                      ->textInput(['maxlength' => true]);
                  ?>
              <?php endif;?>

              <?=
                  $form->field($teachinginfo, 'appliedforloan')
                  ->label(
                      "Have you applied for Student Loan? *",
                      ['class'=> 'form-label']
                  )
                  ->inline()
                  ->radioList(
                      $student_loan,
                      ["itemOptions" => ["disabled" => true]]
                  );
              ?>

              <?=
                  $form->field($teachinginfo, 'sponsorship')
                  ->label(
                      "Have you requested? *",
                      ['class'=> 'form-label']
                  )
                  ->inline()
                  ->radioList(
                      $sponsorship_request,
                      ["itemOptions" => ["disabled" => true]]
                  );
              ?>

              <?php if ($teachinginfo->sponsorname):?>
                <div id="sponsor-names" style="display:none;">
                    <?=
                        $form->field($teachinginfo, 'sponsorname')
                        ->label(
                            "If you are sponsored please state the organization(s).",
                            ['class'=> 'form-label']
                        )
                        ->textArea(['rows' => '2']);
                    ?>
                </div>
              <?php endif;?>
          </fieldset>
        <?php yii\bootstrap\ActiveForm::end(); ?>
      <?php endif;?>

      <?php if ($criminalrecord==true): ?>
        </br><div class="panel panel-default">
            <h3 style='text-align:center'>Criminal Record</h3>

            <table class='table table-hover'>
              <tr>
                <th>Nature of Charge</th>
                <td colspan='3'>
                  <?= $criminalrecord->natureofcharge; ?></td>
              </tr>

              <tr>
                <th>Outcome</th>
                <td colspan='3'><?= $criminalrecord->outcome; ?></td>
              </tr>

              <tr>
                <th>Date of Conviction (if applicable)</th>
                <td colspan='3'>
                  <?= $criminalrecord->dateofconviction; ?>
                </td>
              </tr>
            </table>
        </div>
      <?php endif;?>

    </div>
  </div>
</div>
