<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Hold Details";

$this->params["breadcrumbs"][] =
  ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
  ["label" => "Find Account", "url" => ["profiles/search"]];

$this->params["breadcrumbs"][] =
  [
    "label" => "{$userFullname}",
    "url" => ["profiles/user-profile", "username" => $username]
  ];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div class="row">
  <div class="col-sm-8">
    <div class="box box-primary">
      <div class="box-body">
        <table class="table table-responsive">
          <tr>
            <th>Hold Name</th>
            <td><?= $hold->name ?></td>
          </tr>

          <tr>
            <th>Programme</th>
            <td><?= $hold->registrationDetails ?></td>
          </tr>

          <tr>
            <th>Notes</th>
            <td><?= $hold->details ?></td>
          </tr>

          <tr>
            <th>Applied</th>
            <td><?= $hold->appliedDetails ?></td>
          </tr>

          <tr>
            <th>Hold Status</th>
            <td><?= $hold->status ?></td>
          </tr>

          <tr>
            <th>Notification Status</th>
            <td><?= $hold->notificationStatus ?></td>
          </tr>

          <tr>
            <th>Resolved</th>
            <td><?= $hold->resolvedDetails ?></td>
          </tr>
        </table>
      </div>
    </div>

  </div>

  <div class="col-sm-4">
    <div class="box box-primary">
      <div class="box-header text-center">
        <span style="font-weight: bold; font-size:1.5em">
          Options
        </span>
      </div>
      <div class="box-body">
        <ul style="list-style-type:none; margin-left: 0; padding-left: 0;">
          <?php if ($hold->notificationStatus == "Not Sent") : ?>
            <li>
              <?=
                Html::a(
                  "Notify Student",
                  Url::toRoute(["publish-hold-notification", "id" => $hold->id]),
                  [
                    "id" => "publish-hold-notification-button",
                    "style" => "margin: 0 auto; margin-bottom:20px",
                    "class" => "btn btn-md btn-block btn-primary"
                  ]
                );
              ?>
            </li>
          <?php endif; ?>

          <?php if ($hold->status == "Resolved") : ?>
            <li>
              <?=
                Html::a(
                  "Reactivate",
                  Url::toRoute(["reactivate-hold", "id" => $hold->id]),
                  [
                    "id" => "reactivate-hold-button",
                    "style" => "margin: 0 auto; margin-bottom:20px",
                    "class" => "btn btn-md btn-block btn-success"
                  ]
                );
              ?>
            </li>
          <?php endif; ?>

          <?php if ($hold->status == "Active") : ?>
            <li>
              <?=
                Html::a(
                  "Resolve",
                  Url::toRoute(["resolve-hold", "id" => $hold->id]),
                  [
                    "id" => "resolve-button",
                    "style" => "margin: 0 auto; margin-bottom:20px",
                    "class" => "btn btn-md btn-block btn-warning"
                  ]
                );
              ?>
            </li>
          <?php endif; ?>

          <?php if (true) : ?>
            <li>
              <?=
                Html::a(
                  "Delete",
                  Url::toRoute(["delete-hold", "id" => $hold->id]),
                  [
                    "id" => "resolve-hold-button",
                    "style" => "margin: 0 auto; margin-bottom:20px",
                    "class" => "btn btn-md btn-block btn-danger"
                  ]
                );
              ?>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</div>