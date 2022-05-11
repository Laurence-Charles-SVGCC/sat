<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Course Pass Rates';

$this->params["breadcrumbs"][] =
    ["label" => "Report Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] =
    ["label" => "Quality Assurance", "url" => ["quality-assurance/index"]];

$this->params["breadcrumbs"][] = $this->title;

?>

<h1><?= $this->title ?></h1>

<div class="course-pass-rate" style="min-height:2000px">
    <div id="divisions">
        <span class="dropdown">
            <button class="btn btn-default dropdown-toggle btn-block" type="button" data-toggle="dropdown">
                <?php if ($divisionId == null) : ?>
                    Select division...
                <?php else : ?>
                    Change from <?= "{$divisionName}..." ?>
                <?php endif; ?>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <?php foreach ($divisions as $division) : ?>
                    <li>
                        <?=
                        Html::a(
                            $division->name,
                            ["index", "divisionId" => $division->divisionid]
                        );
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </span><br />
    </div>

    <?php if ($divisionId != null && !empty($academicYears)) : ?>
        <div id="academic-years">
            <span class="dropdown">
                <button class="btn btn-default dropdown-toggle btn-block" type="button" data-toggle="dropdown">
                    <?php if ($academicYearId == null) : ?>
                        Select academic year...
                    <?php else : ?>
                        Change from <?= "{$academicYearName}..." ?>
                    <?php endif; ?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <?php foreach ($academicYears as $academicYear) : ?>
                        <li>
                            <?=
                            Html::a(
                                $academicYear->title,
                                [
                                    "index",
                                    "divisionId" => $divisionId,
                                    "academicYearId" => $academicYear->academicyearid
                                ]
                            );
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </span><br />
        </div>
    <?php endif; ?>

    <?php if ($academicYearId != null && !empty($semesters)) : ?>
        <div id="semesters">
            <span class="dropdown">
                <button class="btn btn-default dropdown-toggle btn-block" type="button" data-toggle="dropdown">
                    Select semester...
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <?php foreach ($semesters as $semester) : ?>
                        <li>
                            <?=
                            Html::a(
                                $semester->title,
                                [
                                    "generate-report",
                                    "divisionId" => $divisionId,
                                    "academicYearId" => $academicYearId,
                                    "semesterId" => $semester->semesterid
                                ]
                            );
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </span><br />
        </div>
    <?php endif; ?>
</div>