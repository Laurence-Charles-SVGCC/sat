<?php

$this->title = $userFullname;

$this->params["breadcrumbs"][] =
  ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
  ["label" => "Find Account", "url" => ["profiles/search"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<span class="label label-info pull-right">
  <h5><?= $status ?></h5>
</span>
<div class="box box-primary table-responsive no-padding">
  <div class="box-body">
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation">
        <a href="#successful-applicant-personal" aria-controls="successful-applicant-personal" role="tab" data-toggle="tab">
          Profile
        </a>
      </li>
      <li role="presentation" class="active">
        <a href="#successful-applicant-payments" aria-controls="successful-applicant-payments" role="tab" data-toggle="tab">
          Payments
        </a>
      </li>
    </ul>

    <div class="tab-content">
      <?=
        $this->render(
          "successful-applicant-profile-tab-personal",
          [
            "username" => $username,
            "programme" => $programme,
            "applicant" => $applicant,
            "displayPicture" => $displayPicture,
            "phone" => $phone,
            "personalEmail" => $personalEmail,
            "institutionalEmail" => $institutionalEmail,
            "beneficiaryDetails" => $beneficiaryDetails,
          ]
        );
      ?>

      <?=
        $this->render(
          "successful-applicant-profile-tab-payments",
          [
            "applicant" => $applicant,
            "username" => $username,
            "dataProvider" => $dataProvider
          ]
        );
      ?>
    </div>
  </div>
</div>