<?php
  use yii\helpers\Url;

  $this->title = "Cumulative Report Generator";

  $this->params['breadcrumbs'][] =
  ['label' => 'Cohort Report Dashboard', 'url' => ['index']];

  $this->params['breadcrumbs'][] = $this->title;
?>

<h2 class="text-center"><?= $this->title ?></h2>

<div class="box box-primary table-responsive no-padding" style = "font-size:1.2em;">
  <div class="box-header with-border">
    <h3 class="box-title"><?= $this->title ?></h3>
  </div>

  <div class="box-body" style="min-height:1000px">
    <h4>
      Select from the list below which report you wish to generate:
    </h4>

    <ol>
      <li>
        <div class='dropdown'>
          <button class='btn btn-default dropdown-toggle' type='button'
            id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true'
            aria-expanded='true'>
          <?php if( $division_id == NULL ) : ?>
           <span>Select division...</span>
         <?php else: ?>
           <span>Change division from <?= $selected_division ?> to...</span>
         <?php endif;?>
           <span class='caret'></span>
         </button>
           <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
             <?php foreach ($divisions as $division):?>
               <li>
                 <a href="<?= Url::toRoute(['/subcomponents/programmes/cohort-report-generator/cumulative-gpa-report',
                 'division_id' => $division->divisionid]);?>"
                 >
                   <?= $division->abbreviation ;?>
                 </a>
               </li>
             <?php endforeach; ?>
           </ul>
       </div>
     </li><br/>


     <?php if ($division_id != NULL) :?>
      <li>
        <div class='dropdown'>
          <button class='btn btn-default dropdown-toggle' type='button'
            id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true'
            aria-expanded='true'>
            <?php if( $cohort_id == NULL ) : ?>
             <span>Select cohort...</span>
           <?php else: ?>
             <span>Change cohort from <?= $selected_cohort ?> to...</span>
           <?php endif;?>
           <span class='caret'></span>
         </button>
           <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
             <?php foreach ($cohorts as $cohort):?>
               <li>
                 <a href="<?= Url::toRoute(['/subcomponents/programmes/cohort-report-generator/cumulative-gpa-report',
                 'division_id' => $division_id,
                 'cohort_id' => $cohort->academicyearid]);?>"
                 >
                   <?= $cohort->title ;?>
                 </a>
               </li>
             <?php endforeach; ?>
           </ul>
       </div>
     </li><br/>
    <?php endif; ?>

    <?php if ($division_id != NULL  && $cohort_id != NULL) :?>
     <li>
       <div class='dropdown'>
         <button class='btn btn-default dropdown-toggle' type='button'
           id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true'
           aria-expanded='true'>
          <span>Select Minimum GPA</span>
          <span class='caret'></span>
        </button>
          <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
            <li>
              <a href="<?= Url::toRoute(['/subcomponents/programmes/cohort-report-generator/cumulative-gpa-report',
              'division_id' => $division_id,
              'cohort_id' => $cohort_id,
              'minimum_gpa' => -1]);?>"
              >
                No restriction
              </a>
            </li>
            <?php for($i = 0; $i<$gpas_size ; $i++):?>
              <li>
                <a href="<?= Url::toRoute(['/subcomponents/programmes/cohort-report-generator/cumulative-gpa-report',
                'division_id' => $division_id,
                'cohort_id' => $cohort_id,
                'minimum_gpa' => $gpas[$i]['quality_points']]);?>"
                >
                  <?= $gpas[$i]['grade'] . " " .$gpas[$i]['quality_points'] ;?>
                </a>
              </li>
            <?php endfor; ?>
          </ul>
      </div>
    </li>
    <?php endif; ?>
    </ol>
  </div>
</div>
