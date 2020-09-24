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

<div role="tabpanel" class="tab-pane" id="dne-additional-details">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4>Additional Information</h4>
    </div>

    <div class="panel-body"><br/>
      <div class="panel panel-default">
        <?php
          if (Yii::$app->user->can('verifyApplicants')
          || Yii::$app->user->can('viewAdditionalDetailsData')):
        ?>
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
        <?php if (Yii::$app->user->can('verifyApplicants')  || Yii::$app->user->can('viewAdditionalDetailsData')):?>
          <h3 style='text-align:center'>Nursing Experience</h3>
          <?php if ($nursing==false):?>
            <div class='panel-heading'>
              <strong>
                Applicant has not indicated that they have prior nursing
                experience
              </strong>
            </div>
          <?php else: ?>
            <div class='panel-heading'>Experience Details</div>
            <table class='table table-hover' style='margin: 0 auto;'>
              <tr>
                <th rowspan='5' style='vertical-align:middle; text-align:center; font-size:1.2em;'>{$nursing->location}</th>
                <th>Nature of Duties</th>
                <td colspan='3'><?= $nursing->natureoftraining; ?></td>
              </tr>

              <tr>
                <th>Tenure Period</th>
                <td colspan='3'><?= $nursing->tenureperiod; ?></td>
              </tr>

              <tr>
                <th>Departure Reason (if applicable)</th>
                <td colspan='3'><?= $nursing->departreason; ?></td>
              </tr>
            </table>
          <?php endif;?>
        <?php endif;?>
      </div></br>


      <div class="panel panel-default">
        <?php if (Yii::$app->user->can('verifyApplicants')  || Yii::$app->user->can('viewAdditionalDetailsData')):?>
          <h3 style='text-align:center'>Nursing Certification</h3>

          <?php if ($nursing_certification == false):?>
            <div class='panel-heading'>
              <strong>
                Applicant has not indicated that they have prior nursing
                certificates.
              </strong>
            </div>
          <?php else:?>
            <?php foreach ($nursing_certification as $key => $record):?>
              <div class='panel-heading'><?= $key+1?></div>
              <table class='table table-hover' style='margin: 0 auto;'>
                <tr>
                  <th rowspan='3' style='vertical-align:middle; text-align:center;'>
                    <?= $record->certification;?>
                  </th>
                  <th>Institution</th>
                  <td colspan='3'><?= $record->institutionname;?></td>
                </tr>

                <tr>
                  <th>Dates of Training</th>
                  <td colspan='3'><?= $record->datesoftraining;?></td>
                </tr>

                <tr>
                  <th>Length of Training</th>
                  <td colspan='3'><?= $record->lengthoftraining;?></td>
                </tr>
              </table>
            <?php endforeach;?>
          <?php endif;?>
        <?php endif;?>
    </div><br/>

    <div class="panel panel-default">
      <?php if (Yii::$app->user->can('verifyApplicants')  || Yii::$app->user->can('viewAdditionalDetailsData')):?>
        <h3 style='text-align:center'>Nursing Additional Information</h3>
        <?php $form = yii\bootstrap\ActiveForm::begin() ?>
            <fieldset >
              <legend>Family Information</legend>
              <?=
                $form->field($nursinginfo, 'childcount')
                ->label(
                    "How many children do you have?*",
                    ['class'=> 'form-label']
                )
                ->dropDownList(
                    $relation_count,
                    [
                    'id'=>'childCount',
                    'onchange'=> 'checkChildCount();',
                    'disabled' => true
                  ]
                );
              ?>

              <?php if ($nursingApplicantHasChildren == true):?>
                <div id="ages">
                  <?=
                    $form->field($nursinginfo, 'childages')
                    ->label("Ages of children *", ['class'=> 'form-label'])
                    ->textArea([
                      'rows' => '1', 'id'=>'childAges', 'disabled' => true
                    ]);
                  ?>
                </div>
              <?php else :?>
                <div id="ages" style="display:none">
                  <?=
                    $form->field($nursinginfo, 'childages')
                    ->label("Ages of children *", ['class'=> 'form-label'])
                    ->textArea([
                      'rows' => '1', 'id'=>'childAges', 'disabled' => true
                    ]);
                  ?>
                </div>
              <?php endif ;?>

              <?=
                $form->field($nursinginfo, 'brothercount')
                ->label(
                    "How many brothers do you have?*",
                    ['class'=> 'form-label']
                )
                ->dropDownList(
                    $relation_count,
                    ['id'=>'brotherCount', 'disabled' => true]
                );
              ?>

              <?=
                $form->field($nursinginfo, 'sistercount')
                ->label(
                    "How many sisters do you have?*",
                    ['class'=> 'form-label']
                )
                ->dropDownList(
                    $relation_count,
                    ['id'=>'sisterCount', 'disabled' => true]
                );
              ?>
            </fieldset><br>

            <fieldset >
                <legend>Work Experience</legend>
                <?=
                  $form->field($nursinginfo, 'yearcompletedschool')
                  ->label(
                      'Year school was completed: *',
                      ['class'=> 'form-label']
                  )
                  ->textInput(['maxlength' => true, 'disabled' => true]);
                ?>

                <?=
                  $form->field($nursinginfo, 'hasworked')
                  ->label(
                      "Have you worked since leaving school? *",
                      ['class'=> 'form-label']
                  )
                  ->inline()
                  ->radioList(
                      $has_worked,
                      [
                      'id' => 'hasWorked',
                      'onclick' => 'processOtherApplications();showGeneralWorkExperience();',
                      "itemOptions" => ['disabled' => true]
                    ]
                  );
                ?>

                <?=
                  $form->field($nursinginfo, 'isworking')
                  ->label(
                      "Are you currently employed? *",
                      ['class'=> 'form-label']
                  )
                  ->inline()
                  ->radioList(
                      $is_working,
                      [
                      'id' => 'isWorking',
                      'onclick' => 'processOtherApplications();showGeneralWorkExperience();',
                      "itemOptions" => ['disabled' => true]
                    ]
                  );
                ?>

                <div id="has-other-applications">
                  <?=
                    $form->field($nursinginfo, 'hasotherapplications')
                    ->label(
                        "Are you currently awaiting any application responses? *",
                        ['class'=> 'form-label']
                    )
                    ->inline()
                    ->radioList(
                        $is_working,
                        [
                        'id' => 'hasOtherApplications',
                        'onclick' => 'showOtherApplicationDetails();',
                        "itemOptions" => ['disabled' => true]
                      ]
                    );
                  ?>
                </div>

                <?php if ($nursingApplicantHasOtherApplications == true):?>
                    <div id="other-applications-info">
                 <?php else :?>
                    <div id="other-applications-info" style="display:none">
                      <?=
                        $form->field($nursinginfo, 'otherapplicationsinfo')
                        ->label(
                            "Where have you applied for a job? (Apart from this application? *",
                            ['class'=> 'form-label']
                        )
                        ->textArea(
                            [
                            'rows' => '1',
                            'id' =>' otherApplicationInfo',
                            'disabled' => true
                          ]
                        );
                      ?>
                    </div>
                <?php endif ;?>

                <?=
                  $form->field($nursinginfo, 'hasnursingexperience')
                  ->label(
                      "Do you have had any previous nursing or nurse related training? *",
                      ['class'=> 'form-label']
                  )
                  ->inline()
                  ->radioList(
                      $nursing_experience,
                      ['id' => 'nurse-work', "itemOptions" => ['disabled' => true]]
                  );
                ?>
            </fieldset></br>

            <fieldset >
                <legend>Other</legend>
                <!-- Is organization member radiolist -->
                <?php if ($aplicantHasMidwiferyApplication == true):?>
                  <?=
                    $form->field($nursinginfo, 'ismember')
                    ->label(
                        "Are you a member of a professional organisation? *",
                        ['class'=> 'form-label']
                    )
                    ->inline()
                    ->radioList(
                        $is_organisational_member,
                        [
                        'class'=> 'form-field',
                        'onclick' => 'toggleOrganisationDetails();',
                        "itemOptions" => ['disabled' => true]
                      ]
                    );
                  ?>
                <?php endif;?>

                <!-- Organization details -->
                <?php if ($aplicantHasMidwiferyApplication == true  && $nursingApplicantIsMember == true):?>
                    <div id="member-organisations" style="display:block">
                      <?=
                        $form->field($nursinginfo, 'memberorganisations')
                        ->label(
                            'If yes, state which?',
                            ['class'=> 'form-label']
                        )
                        ->textInput(['maxlength' => true, 'disabled' => true]);
                       ?>
                    </div>
                <?php else:?>
                    <div id="member-organisations" style="display:none">
                      <?=
                        $form->field($nursinginfo, 'memberorganisations')
                        ->label('If yes, state which?', ['class'=> 'form-label'])
                        ->textInput(['maxlength' => true, 'disabled' => true]);
                      ?>
                    </div>
                <?php endif; ?>

                <!--Reason for not joining organization-->
                <?php if ($aplicantHasMidwiferyApplication == true  && $nursingApplicantIsMember == false):?>
                  <div id="exclusion-reason" style="display:block">
                    <?=
                      $form->field($nursinginfo, 'exclusionreason')
                      ->label('If no, give reason(s)?', ['class'=> 'form-label'])
                      ->textInput(['maxlength' => true, 'disabled' => true]);
                      ?>
                  </div>
                <?php else:?>
                  <div id="exclusion-reason" style="display:none">
                    <?=
                      $form->field($nursinginfo, 'exclusionreason')
                      ->label('If no, give reason(s)?', ['class'=> 'form-label'])
                      ->textInput(['maxlength' => true, 'disabled' => true]);
                    ?>
                  </div>
                <?php endif; ?>

                <!-- Is repeat applicant radiolist -->
                <?php if ($aplicantHasMidwiferyApplication == true):?>
                  <?=
                    $form->field($nursinginfo, 'repeatapplicant')
                      ->label(
                          "Have you applied for entry into this course previously? *",
                          ['class'=> 'form-label']
                      )
                      ->inline()
                      ->radioList(
                          $is_repeat_applicant,
                          [
                          'class'=> 'form-field',
                          'onclick' => 'togglePreviousYears();',
                          "itemOptions" => ['disabled' => true]
                        ]
                      );
                    ?>
                <?php endif;?>

                <!-- Previous years -->
                <?php if ($aplicantHasMidwiferyApplication == true  && $nursingApplicantHasPreviousApplication == true):?>
                  <div id="previous-years" style="display:block">
                <?php else:?>
                  <div id="previous-years" style="display:none">
                <?php endif; ?>
                  <?=
                    $form->field($nursinginfo, 'previousyears')
                    ->label('If yes, state when?', ['class'=> 'form-label'])
                     ->textInput(['maxlength' => true, 'disabled' => true]);
                  ?>
                </div>

                <?=
                  $form->field($nursinginfo, 'hascriminalrecord')
                  ->label(
                      "Have your every been charged by the law for any offence? *",
                      ['class'=> 'form-label']
                  )
                  ->inline()
                  ->radioList(
                      $has_criminalrecord,
                      ["itemOptions" => ['disabled' => true]]
                  );
                ?>

                </br><p>State two (2) reasons why you wish to do enroll in your programme of choice.
                <?=
                  $form->field($nursinginfo, 'applicationmotivation1')
                  ->label("Reason #1 *", ['class'=> 'form-label'])
                  ->textArea(['rows' => '5', 'disabled' => true]);
                ?>

                <?=
                  $form->field($nursinginfo, 'applicationmotivation2')
                  ->label("Reason #2 *", ['class'=> 'form-label'])
                  ->textArea(['rows' => '5', 'disabled' => true]);
                ?>

                <?=
                  $form->field($nursinginfo, 'additionalcomments')
                  ->label("Other Comments ", ['class'=> 'form-label'])
                  ->textArea(['rows' => '5', 'disabled' => true]);
                ?>
            </fieldset>
        <?php yii\bootstrap\ActiveForm::end(); ?>
      <?php endif;?>
    </div>


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
