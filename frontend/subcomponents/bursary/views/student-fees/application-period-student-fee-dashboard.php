<?php
$this->title = $periodName;

$this->params["breadcrumbs"][] =
    ["label" => "Bursary Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Fee Catalog", "url" => ["fees/index"]];

$this->params["breadcrumbs"][] =
    [
        "label" => "Student Fee Application Period Catalog",
        "url" => ["student-fees/view-fee-listing"]
    ];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<?= $this->render(
    "application-period-student-fee-dashboard-form",
    [
        "displayForm" => $displayForm,
        "forms" => $forms,
        "billingTypes" => $billingTypes,
        "programmes" => $programmes,
    ]
);
?>

<?= $this->render(
    "application-period-student-fee-dashboard-catalog",
    [
        "periodName" => $periodName,
        "dataProvider" => $dataProvider,
        "displayExistingFees" => $displayExistingFees,
    ]
);
?>

<script>
    function showFeeForm() {
        let feeForm = document.getElementById("add-student-fees-form");
        feeForm.style.display = "block";
    }
</script>