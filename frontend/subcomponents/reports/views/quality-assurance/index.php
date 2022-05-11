<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Reports Listing';

$this->params["breadcrumbs"][] =
    ["label" => "Report Dashboard", "url" => ["site/index"]];

$this->params["breadcrumbs"][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Fields</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>
                    <?= Html::a(
                        "Course Pass Rates",
                        Url::toRoute(["course-pass-rate/index"])
                    ); ?>
                </td>
                <td>Generates pass and fail statistics on a course by course basis.</td>
                <td>
                    <ul>
                        <li>Academic Year</li>
                        <li>Semester</li>
                        <li>Programme</li>
                        <li>Course</li>
                        <li>Lecturer</li>
                        <li>No. of Students</li>
                        <li>No. of Passes</li>
                        <li>No. of Fails</li>
                        <li>Pass Rate</li>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
</div>