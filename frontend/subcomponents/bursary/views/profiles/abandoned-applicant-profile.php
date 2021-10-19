<?php

$this->title = $userFullname;

$this->params["breadcrumbs"][] =
  ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
  ["label" => "Find Account", "url" => ["profiles/search"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div class="box box-primary table-responsive no-padding">
  <div class="box-body">
    <div class="jumbotron">
      <h2><?= $status ?></h2>
      <p>
        If applicant did not intend for their application to be removed
        ask them to report the issue to the Registry.
      </p>
    </div>
  </div>
</div>