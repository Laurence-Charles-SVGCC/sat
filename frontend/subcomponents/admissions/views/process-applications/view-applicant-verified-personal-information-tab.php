<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>

<div role="tabpanel" class="tab-pane" id="personal-information">
    <div class="panel panel-default">
      <?php if (Yii::$app->user->can('ViewProfileData') || Yii::$app->user->can('viewGeneral')):?>
          <div class="panel-heading">
            <h4>General</h4>
          </div>

          <!-- Table -->
          <table class="table table-hover">
              <tr>
                  <td rowspan="4">
                      <?php if ($applicant->photopath == null || strcmp($applicant->photopath, "") ==0): ?>
                          <?php if (strcasecmp($applicant->gender, "male") == 0): ?>
                              <img src="<?=Url::to('css/dist/img/avatar_male(150_150).png');?>" alt="avatar_male" class="img-rounded">
                          <?php elseif (strcasecmp($applicant->gender, "female") == 0): ?>
                              <img src="<?=Url::to('css/dist/img/avatar_female(150_150).png');?>" alt="avatar_female" class="img-rounded">
                          <?php endif;?>
                      <?php else: ?>
                              <img src="<?=$applicant->photopath;?>" alt="student_picture" class="img-rounded">
                      <?php endif;?>
                  </td>
                  <th>Gender</th>
                  <td><?=$applicant->gender;?></td>
                  <th>Date Of Birth</th>
                  <td><?=$applicant->dateofbirth;?></td>
              </tr>

              <tr>
                  <th>Marital Status</th>
                  <td><?=$applicant->maritalstatus;?></td>
                  <th>Nationality</th>
                  <td><?=$applicant->nationality;?></td>
              </tr>

              <tr>
                  <th>Place Of Birth</th>
                  <td><?=$applicant->placeofbirth;?></td>
                  <th>Religion</th>
                  <td><?=$applicant->religion;?></td>
              </tr>

              <tr>
                  <th>Sponsor's Name</th>
                  <td><?=$applicant->sponsorname;?></td>
                  <td></td>
                  <td></td>
              </tr>
          </table>
      <?php endif;?>

      <?php if (Yii::$app->user->can('ViewProfileData') || Yii::$app->user->can('viewContactDetails')):?>
           <div class="panel-heading">
             <h4>
               <span>Contact Details</span>
               <?php if (Yii::$app->user->can('System Administrator') == true):?>
                 <?=
                  Html::a(
                      'Edit',
                      [
                        'edit-contact-details',
                        'personid' => $applicant->personid,
                        'programme' => $programme,
                        'application_status' => $application_status,
                        'programme_id' => $programme_id
                      ],
                      [
                       'style' => 'margin-bottom: 10px',
                       'class' => 'btn btn-info pull-right',
                    ]
                  );
                ?>
              <?php endif;?>
             </h4>
          </div>
          <!-- Table -->
          <table class="table table-hover">
              <tr>
                  <td></td>
                  <th>Home Phone</th>
                  <td><?=$phone->homephone;?></td>
                  <th>Cell Phone</th>
                  <td><?=$phone->cellphone;?></td>
              </tr>

              <tr>
                  <td></td>
                  <th>Work Phone</th>
                  <td><?=$phone->workphone;?></td>
                  <th>Personal Email</th>
                  <td><?=$email->email;?></td>
              </tr>
          </table>
      <?php endif;?>

      <?php if (Yii::$app->user->can('ViewProfileData') || Yii::$app->user->can('viewAddresses')):?>
          <div class="panel-heading">
            <h4>Addresses</h4>
          </div>
          <!-- Table -->
          <table class="table table-hover">
              <tr>
                  <th rowspan='2' style='vertical-align:middle; text-align:center;'>Permanent Address</th>
                  <th>Country</th>
                  <td><?=$permanentaddress->country;?></td>
                  <th>Town</th>
                  <td><?=$permanentaddress->town;?></td>
              </tr>
              <tr>
                  <th>Address Line</th>
                  <td><?=$permanentaddress->addressline;?></td>
              </tr>

              <tr>
                  <th rowspan='2' style='vertical-align:middle; text-align:center;'>Residential Address</th>
                  <th>Country</th>
                  <td><?=$residentaladdress->country;?></td>
                  <th>Town</th>
                  <td><?=$residentaladdress->town;?></td>
              </tr>
              <tr>
                  <th>Address Line</th>
                  <td><?=$residentaladdress->addressline;?></td>
              </tr>

              <tr>
                  <th rowspan='2' style='vertical-align:middle; text-align:center;'>Postal Address</th>
                  <th>Country</th>
                  <td><?=$postaladdress->country;?></td>
                  <th>Town</th>
                  <td><?=$postaladdress->town;?></td>
              </tr>
              <tr>
                  <th>Address Line</th>
                  <td><?=$postaladdress->addressline;?></td>
              </tr>
          </table>
      <?php endif;?>

      <?php if (Yii::$app->user->can('ViewProfileData') || Yii::$app->user->can('viewRelatives')):?>
          <div class="panel-heading">
            <h4>Relatives</h4>
          </div>

          <!-- Table -->
          <table class="table table-hover">
              <?php if ($old_beneficiary!= false):?>
                  <tr>
                      <th rowspan="4" style="vertical-align:top; text-align:center;">
                        Beneficiary
                      </th>
                      <th>Full Name</th>
                      <td><?=$old_beneficiary->title . " " . $old_beneficiary->firstname . " " . $old_beneficiary->lastname;?></td>
                      <th>Occupation</th>
                      <td><?=$old_beneficiary->occupation?></td>
                  </tr>
                  <tr>
                      <th>Home Phone</th>
                      <td><?=$old_beneficiary->homephone?></td>
                      <th>Cell Phone</th>
                      <td><?=$old_beneficiary->cellphone?></td>
                  </tr>
                  <tr>
                      <th>Work Phone</th>
                      <td><?=$old_beneficiary->workphone?></td>
                      <th>Email</th>
                      <td><?=$old_beneficiary->email?></td>
                  </tr>
                  <tr>
                      <th>Address</th>
                      <?php if ($old_beneficiary->address != null && strcmp($old_beneficiary->address, "")!=0):?>
                          <td><?= $old_beneficiary->address;?></td>
                      <?php elseif ($old_beneficiary->town == null || strcmp($old_beneficiary->town, "") == 0  || strcmp($old_beneficiary->town, "other") == 0  || strcmp($old_beneficiary->country, "st. vincent and the grenadines") != 0): ?>
                          <td><?=$old_beneficiary->addressline . "," . "<br/>" . $old_beneficiary->country ;?></td>
                      <?php elseif ($old_beneficiary->addressline == null || strcmp($old_beneficiary->addressline, "") == 0): ?>
                          <td><?=$old_beneficiary->town . "," .  "<br/>" . $old_beneficiary->country ;?></td>
                      <?php endif; ?>
                  </tr>
              <?php endif;?>

              <?php if ($new_beneficiary!= false):?>
                  <tr>
                      <th rowspan="4" style="vertical-align:middle; text-align:center;">
                        Beneficiary
                      </th>
                      <th>Full Name</th>
                      <td><?=$new_beneficiary->title . " " . $new_beneficiary->firstname . " " . $new_beneficiary->lastname;?></td>
                      <th>Occupation</th>
                      <td><?=$new_beneficiary->occupation?></td>
                  </tr>
                  <tr>
                      <th>Home Phone</th>
                      <td><?=$new_beneficiary->homephone?></td>
                      <th>Cell Phone</th>
                      <td><?=$new_beneficiary->cellphone?></td>
                  </tr>
                  <tr>
                      <th>Work Phone</th>
                      <td><?=$new_beneficiary->workphone?></td>
                      <th>Email</th>
                      <td><?=$new_beneficiary->email?></td>
                  </tr>
                  <tr>
                      <th>Address</th>
                      <td><?=$new_beneficiary->address?></td>
                      <th>Relation</th>
                      <td><?=$new_beneficiary->relationdetail?></td>
                  </tr>
              <?php endif;?>

              <?php if ($old_emergencycontact!= false):?>
                  <tr>
                      <th rowspan="4" style="vertical-align:middle; text-align:center;">
                        Emergency Contact
                      </th>
                      <th>Full Name</th>
                      <td><?=$old_emergencycontact->title . " " . $old_emergencycontact->firstname . " " . $old_emergencycontact->lastname;?></td>
                      <th>Occupation</th>
                      <td><?=$old_emergencycontact->occupation?></td>
                  </tr>
                  <tr>
                      <th>Home Phone</th>
                      <td><?=$old_emergencycontact->homephone?></td>
                      <th>Cell Phone</th>
                      <td><?=$old_emergencycontact->cellphone?></td>
                  </tr>
                  <tr>
                      <th>Work Phone</th>
                      <td><?=$old_emergencycontact->workphone?></td>
                      <th>Email</th>
                      <td><?=$old_emergencycontact->email?></td>
                  </tr>
                  <tr>
                      <th>Address</th>
                      <?php if ($old_emergencycontact->address != null && strcmp($old_emergencycontact->address, "")!=0):?>
                          <td><?= $old_emergencycontact->address;?></td>
                      <?php elseif ($old_emergencycontact->town == null || strcmp($old_emergencycontact->town, "") == 0  || strcmp($old_emergencycontact->town, "other") == 0  || strcmp($old_emergencycontact->country, "st. vincent and the grenadines") != 0): ?>
                          <td><?=$old_emergencycontact->addressline . "," . "<br/>" . $old_emergencycontact->country ;?></td>
                      <?php elseif ($old_emergencycontact->addressline == null || strcmp($old_emergencycontact->addressline, "") == 0): ?>
                          <td><?=$old_emergencycontact->town . "," .  "<br/>" . $old_emergencycontact->country ;?></td>
                      <?php endif; ?>
                  </tr>
              <?php endif;?>

              <?php if ($new_emergencycontact!= false):?>
                  <tr>
                      <th rowspan="4" style="vertical-align:middle; text-align:center;">
                        Emergency Contact
                      </th>
                      <th>Full Name</th>
                      <td><?=$new_emergencycontact->title . " " . $new_emergencycontact->firstname . " " . $new_emergencycontact->lastname;?></td>
                      <th>Occupation</th>
                      <td><?=$new_emergencycontact->occupation?></td>
                  </tr>
                  <tr>
                      <th>Home Phone</th>
                      <td><?=$new_emergencycontact->homephone?></td>
                      <th>Cell Phone</th>
                      <td><?=$new_emergencycontact->cellphone?></td>
                  </tr>
                  <tr>
                      <th>Work Phone</th>
                      <td><?=$new_emergencycontact->workphone?></td>
                      <th>Email</th>
                      <td><?=$new_emergencycontact->email?></td>
                  </tr>
                  <tr>
                      <th>Address</th>
                      <td><?=$new_emergencycontact->address?></td>
                      <th>Relation</th>
                      <td><?=$new_emergencycontact->relationdetail?></td>
                  </tr>
              <?php endif;?>


              <?php if ($spouse!= false):?>
                  <tr>
                      <th rowspan="4" style="vertical-align:middle; text-align:center;">
                          Spouse
                      </th>
                      <th>Full Name</th>
                      <td><?=$spouse->title . " " . $spouse->firstname . " " . $spouse->lastname;?></td>
                      <th>Occupation</th>
                      <td><?=$spouse->occupation?></td>
                  </tr>
                  <tr>
                      <th>Home Phone</th>
                      <td><?=$spouse->homephone?></td>
                      <th>Cell Phone</th>
                      <td><?=$spouse->cellphone?></td>
                  </tr>
                  <tr>
                      <th>Work Phone</th>
                      <td><?=$spouse->workphone?></td>
                      <th>Email</th>
                      <td><?=$spouse->email?></td>
                  </tr>
                  <tr>
                      <th>Address</th>
                      <?php if ($spouse->town == null || strcmp($spouse->town, "") == 0  || strcmp($spouse->town, "other") == 0  || strcmp($spouse->country, "st. vincent and the grenadines") != 0): ?>
                          <td><?=$spouse->addressline . "," . "<br/>" . $spouse->country ;?></td>
                      <?php elseif ($spouse->addressline == null || strcmp($spouse->addressline, "") == 0): ?>
                          <td><?=$spouse->town . "," .  "<br/>" . $spouse->country ;?></td>
                      <?php endif; ?>
                  </tr>
              <?php endif;?>



              <?php if ($mother!= false):?>
                  <tr>
                      <th rowspan="4" style="vertical-align:middle; text-align:center; font-size:1.2em;">
                        Mother
                      </th>
                      <th>Full Name</th>
                      <td><?=$mother->title . " " . $mother->firstname . " " . $mother->lastname;?></td>
                      <th>Occupation</th>
                      <td><?=$mother->occupation?></td>
                  </tr>
                  <tr>
                      <th>Home Phone</th>
                      <td><?=$mother->homephone?></td>
                      <th>Cell Phone</th>
                      <td><?=$mother->cellphone?></td>
                  </tr>
                  <tr>
                      <th>Work Phone</th>
                      <td><?=$mother->workphone?></td>
                      <th>Email</th>
                      <td><?=$mother->email?></td>
                  </tr>
                  <tr>
                      <th>Address</th>
                      <?php if ($mother->address != null && strcmp($mother->address, "")!=0):?>
                          <td><?= $mother->address;?></td>
                      <?php elseif ($mother->town == null || strcmp($mother->town, "") == 0  || strcmp($mother->town, "other") == 0  || strcmp($mother->country, "st. vincent and the grenadines") != 0): ?>
                          <td><?=$mother->addressline . "," . "<br/>" . $mother->country ;?></td>
                      <?php elseif ($mother->addressline == null || strcmp($mother->addressline, "") == 0): ?>
                          <td><?=$mother->town . "," .  "<br/>" . $mother->country ;?></td>
                      <?php endif; ?>
                  </tr>
              <?php endif;?>



              <?php if ($father!= false):?>
                  <tr>
                      <th rowspan="4"  style="vertical-align:middle; text-align:center;">
                        Father
                      </th>
                      <th>Full Name</th>
                      <td><?=$father->title . " " . $father->firstname . " " . $father->lastname;?></td>
                      <th>Occupation</th>
                      <td><?=$father->occupation?></td>
                  </tr>
                  <tr>
                      <th>Home Phone</th>
                      <td><?=$father->homephone?></td>
                      <th>Cell Phone</th>
                      <td><?=$father->cellphone?></td>
                  </tr>
                  <tr>
                      <th>Work Phone</th>
                      <td><?=$father->workphone?></td>
                      <th>Email</th>
                      <td><?=$father->email?></td>
                  </tr>
                  <tr>
                      <th>Address</th>
                      <?php if ($father->address != null && strcmp($father->address, "")!=0):?>
                          <td><?= $father->address;?></td>
                      <?php elseif ($father->town == null || strcmp($father->town, "") == 0  || strcmp($father->town, "other") == 0  || strcmp($father->country, "st. vincent and the grenadines") != 0): ?>
                          <td><?=$father->addressline . "," . "<br/>" . $father->country ;?></td>
                      <?php elseif ($father->addressline == null || strcmp($father->addressline, "") == 0): ?>
                          <td><?=$father->town . "," .  "<br/>" . $father->country ;?></td>
                      <?php endif; ?>
                  </tr>
              <?php endif;?>



              <?php if ($nextofkin!= false):?>
                  <tr>
                      <th rowspan="4"  style="vertical-align:middle; text-align:center; font-size:1.2em;">
                        Next of Kin
                      </th>
                      <th>Full Name</th>
                      <td><?=$nextofkin->title . " " . $nextofkin->firstname . " " . $nextofkin->lastname;?></td>
                      <th>Occupation</th>
                      <td><?=$nextofkin->occupation?></td>
                  </tr>
                  <tr>
                      <th>Home Phone</th>
                      <td><?=$nextofkin->homephone?></td>
                      <th>Cell Phone</th>
                      <td><?=$nextofkin->cellphone?></td>
                  </tr>
                  <tr>
                      <th>Work Phone</th>
                      <td><?=$nextofkin->workphone?></td>
                      <th>Email</th>
                      <td><?=$nextofkin->email?></td>
                  </tr>
                  <tr>
                      <th>Address</th>
                      <?php if ($nextofkin->address != null && strcmp($nextofkin->address, "")!=0):?>
                          <td><?= $nextofkin->address;?></td>
                      <?php elseif ($nextofkin->town == null || strcmp($nextofkin->town, "") == 0  || strcmp($nextofkin->town, "other") == 0  || strcmp($nextofkin->country, "st. vincent and the grenadines") != 0): ?>
                          <td><?=$nextofkin->addressline . "," . "<br/>" . $nextofkin->country ;?></td>
                      <?php elseif ($nextofkin->addressline == null || strcmp($nextofkin->addressline, "") == 0): ?>
                          <td><?=$nextofkin->town . "," .  "<br/>" . $nextofkin->country ;?></td>
                      <?php endif; ?>
                  </tr>
              <?php endif;?>

              <?php if ($guardian!= false):?>
                  <tr>
                      <th rowspan="4"  style="vertical-align:middle; text-align:center; font-size:1.2em;">Guardian
                      </th>
                      <th>Full Name</th>
                      <td><?=$guardian->title . " " . $guardian->firstname . " " . $guardian->lastname;?></td>
                      <th>Occupation</th>
                      <td><?=$guardian->occupation?></td>
                  </tr>
                  <tr>
                      <th>Home Phone</th>
                      <td><?=$guardian->homephone?></td>
                      <th>Cell Phone</th>
                      <td><?=$guardian->cellphone?></td>
                  </tr>
                  <tr>
                      <th>Work Phone</th>
                      <td><?=$guardian->workphone?></td>
                      <th>Email</th>
                      <td><?=$guardian->email?></td>
                  </tr>
                  <tr>
                      <th>Address</th>
                      <?php if ($guardian->address != null && strcmp($guardian->address, "")!=0):?>
                          <td><?= $guardian->address;?></td>
                      <?php elseif ($guardian->town == null || strcmp($guardian->town, "") == 0  || strcmp($guardian->town, "other") == 0  || strcmp($guardian->country, "st. vincent and the grenadines") != 0): ?>
                          <td><?=$guardian->addressline . "," . "<br/>" . $guardian->country ;?></td>
                      <?php elseif ($guardian->addressline == null || strcmp($guardian->addressline, "") == 0): ?>
                          <td><?=$guardian->town . "," .  "<br/>" . $guardian->country ;?></td>
                      <?php endif; ?>
                  </tr>
              <?php endif;?>
          </table>
      <?php endif;?>

      <?php if (Yii::$app->user->can('ViewProfileData') || Yii::$app->user->can('viewGeneral')):?>
          <div class="panel-heading">
            <h4>Extracurricular Activities</h4>
          </div>

          <!-- Table -->
          <table class="table table-hover" style="margin: 0 auto;">
              <tr>
                  <th style='width:30%'>National Sports</th>
                  <?php if ($applicant->nationalsports == null || $applicant->nationalsports==" "):?>
                      <td  style='width:70%'>Applicant has never been a national athlete representative</td>
                  <?php else:?>
                      <td style='width:70%'><?=$applicant->nationalsports?></td>
                  <?php endif;?>
              </tr>
              <tr>
                  <th style='width:30%'>Recreational Sports</th>
                  <?php if ($applicant->othersports == null || $applicant->othersports==" "):?>
                      <td  style='width:70%'>Applicant does not play any sports recreationally</td>
                  <?php else:?>
                      <td style='width:70%'><?=$applicant->othersports?></td>
                  <?php endif;?>
              </tr>
              <tr>
                  <th style='width:30%'>Club Memberships</th>
                  <?php if ($applicant->clubs == null || $applicant->clubs==" "):?>
                      <td  style='width:70%'>Applicant is not a member of any clubs</td>
                  <?php else:?>
                      <td style='width:70%'><?=$applicant->clubs?></td>
                  <?php endif;?>
              </tr>
              <tr>
                  <th style='width:30%'>Other Interests</th>
                  <?php if ($applicant->otherinterests == null || $applicant->otherinterests==" "):?>
                      <td  style='width:70%'>Applicant has not indicated any other extracurricular interests/activities</td>
                  <?php else:?>
                      <td style='width:70%'><?=$applicant->otherinterests?></td>
                  <?php endif;?>
              </tr>
          </table>
      <?php endif;?>
    </div>
</div>
