<?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveField;
    use dosamigos\datepicker\DatePicker;
    use yii\helpers\ArrayHelper;
    
    use common\models\User;
    use frontend\models\ApplicationPeriod;
    use frontend\models\Package;
    use frontend\models\PackageType;
    use frontend\models\PackageProgress;
    
    $document_count = [
        0 => 'No attachments',
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
    
    $this->title = 'Edit Package';
    
    $this->params['breadcrumbs'][] = ['label' => 'Packages', 'url' => Url::toRoute(['/subcomponents/admissions/package'])];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/admissions/package']);?>" title="Manage Packages">
        <h1>Welcome to the Admissions Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
    </div>
     
     <div class="box-body">
        <fieldset id="config-package">
            <legend><strong>Configure Package</strong></legend>
            <?php
                $form = ActiveForm::begin(['action' => Url::to(['package/initialize-package', 'recordid' => $recordid, 'action' => 'edit']),]);

                    echo "<br/>";
                    echo "<table class='table table-hover' style='width:100%; margin: 0 auto;'>";   
                        echo "<tr>";
                            echo "<th style='width:25%; vertical-align:middle'> Package Name</th>";
                            if ($name_changeable == true)
                                echo "<td>{$form->field($package, 'name')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                            else
                                echo "<td>{$form->field($package, 'name')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true, 'readonly' => true])}</td>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th style='width:25%; vertical-align:middle'>Application Period</th>";
                            echo "<td>{$form->field($package, 'applicationperiodid')->label('', ['class'=> 'form-label'])->dropDownList(ArrayHelper::map(ApplicationPeriod::periodIncomplete(), 'applicationperiodid', 'name'), ['prompt'=>'Select Application Period'])}</td>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th style='width:25%; vertical-align:middle'>Package Type</th>";
                            echo "<td>{$form->field($package, 'packagetypeid')->label('', ['class'=> 'form-label'])->dropDownList(ArrayHelper::map(PackageType::find()->all(), 'packagetypeid', 'description'), ['onchange' => 'showCommencementDate();', 'prompt'=>'Select Package Type'])}</td>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th style='width:25%; vertical-align:middle'>Commencement Date (if package is for full offers)</th>";
                            echo "<td>{$form->field($package, 'commencementdate')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true,])}</td>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th style='width:25%; vertical-align:middle'>Number of Attachments</th>";
                            echo "<td>{$form->field($package, 'documentcount')->label('', ['class'=> 'form-label'])->dropDownList($document_count)}</td>";
                        echo "</tr>"; 

                        echo "<tr>";
                            echo "<th style='width:25%; vertical-align:middle'>Email Title</th>";
                            echo "<td>{$form->field($package, 'emailtitle')->label('', ['class'=> 'form-label'])->textInput(['maxlength' => true])}</td>";
                        echo "</tr>"; 

                        echo "<tr>";
                            echo "<th style='width:25%; vertical-align:middle'>Email Introductory Statements</th>";
                            if ($package->packageid  && ($package->packagetypeid==1 || $package->packagetypeid==2))
                            {
                                $text= date("l F j, Y") . "<br/>" . "Dear [firstname] [lastname]";
                                echo "<td>";
                                    echo Html::textarea('email-intro', $text, ['rows' => 10, 'maxlength' => true, 'style' => 'font-size:14px; width:100%']);
                                echo "</td>";
                            }
                            elseif ($package->packageid  && $package->packagetypeid==3)
                            {
                                $text= date("l F j, Y") . "                                                                                                 "
                                        . "                                                                                     Dear [firstname] [lastname],"
                                        . "                                                                                                                 "
                                        . "                                                                                      "  
                                        . "We are pleased to inform you that you have been invited to interview for a place in the  "
                                        . "[programme name] at the [division_name] commencing on " . $package->commencementdate .  ".";
                                echo "<td>";
                                    echo Html::textarea('email-intro', $text, ['rows' => 10, 'maxlength' => true, 'style' => 'font-size:14px; width:100%']);
                                echo "</td>";
                            }        
                            elseif ($package->packageid  && $package->packagetypeid==4)
                            {
                                $text= date("l F j, Y") . "                                                                                                  "                                                                                                  
                                        . "                                                                                     Dear [firstname] [lastname],"
                                        . "                                                                                                                 "
                                        . "                                                                                      "  
                                        . "We are pleased to inform you that your application to the St. Vincent and the Grenadines Community College has been successful." 
                                        . "  You are offered a place in the [programme name] at the [division_name] commencing on " . $package->commencementdate .  ".      "
                                        . "Your Student Number is: [student number]";
                                echo "<td>";
                                    echo Html::textarea('email-intro', $text, ['rows' => 10, 'maxlength' => true, 'style' => 'font-size:14px;  width:100%']);
                                echo "</td>";
                            }
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th style='width:25%; vertical-align:middle'>Email Content</th>";
                            echo "<td>{$form->field($package, 'emailcontent')->label('', ['class'=> 'form-label'])->textArea(['rows' => 50, 'maxlength' => true, 'style' => 'font-size:14px;'])}</td>";
                        echo "</tr>"; 
                    echo "</table>"; 

                    echo "<br/>";

                    if ($recordid == true)
                        echo Html::a(' Cancel',['package/index'], ['class' => 'btn btn-danger pull-right', 'style' => 'width:10%; margin-left:5%;']);
                    else      
                        echo Html::a(' Cancel',['package/index'], ['class' => 'btn btn-danger  pull-right', 'style' => 'width:10%; margin-left:5%;']);
                    echo Html::submitButton('Save', ['class' => 'btn btn-success pull-right', 'style' => 'width:10%;']);        
                ActiveForm::end();    
            ?>
        </fieldset><br/><br/><br/>

        <fieldset>
            <legend><strong>Document Upload</strong></legend>
            <?php if ($mandatory_delete == true):?>
                <p>You are reach your stipulated number of documents, you must either change the limit or 
                    delete a file.
                </p><br/>

                <table class='table table-hover' style='margin: 0 auto;'>
                    <tr>
                        <th>Current Files</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach($saved_documents as $index=>$doc):?>
                        <tr>
                            <td><?=substr($doc,24)?></td>
                            <td>
                                <?=Html::a(' Delete', 
                                            ['package/delete-attachment', 'recordid' => $recordid, 'count' => $count, 'index' => $index], 
                                            ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                'data' => [
                                                    'confirm' => 'Are you sure you want to delete this item?',
                                                    'method' => 'post',
                                                ],
                                                'style' => 'margin-right:20px',
                                            ]);
                                ?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </table><br/><br/>
            <?php else:?>
                <table class='table table-hover' style='margin: 0 auto;'>
                    <tr>
                        <th>Current Files</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach($saved_documents as $index=>$doc):?>
                        <tr>
                            <td><?=substr($doc,24)?></td>
                            <td>
                                <?=Html::a(' Delete', 
                                            ['package/delete-attachment', 'recordid' => $recordid, 'count' => $count, 'index' => $index], 
                                            ['class' => 'btn btn-danger glyphicon glyphicon-remove',
                                                'data' => [
                                                    'confirm' => 'Are you sure you want to delete this item?',
                                                    'method' => 'post',
                                                ],
                                                'style' => 'margin-right:20px',
                                            ]);
                                ?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </table><br/><br/>

                <?php if (Package::needsToUpload($recordid)==true):?>
                    <?php 
                        $form = ActiveForm::begin([
                            'action' => Url::to(['package/upload-attachments', 'recordid' => $recordid, 'count' => $count, 'action' => 'edit']),
                            'id' => 'upload-attachments',
                            'options' => [
                                'enctype' => 'multipart/form-data'
                            ]
                        ]) 
                    ?>

                        <?= $form->field($model, 'files[]')
                                ->label('Select documents you would like to attach to package:', 
                                        [
                                            'class'=> 'form-label',
                                        ])
                                ->fileInput(
                                        [
                                            'multiple' => true,
                                            'style' => 'text-align: center; font: bold 25px Arial, Helvetica, Geneva, sans-serif; color: #4B4B55;text-shadow: #fffeff 0 1px 0; padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #e4e4e4;'
                                        ]); ?>

                        <br/>
                        <?= Html::a(' Cancel',['package/index'], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-right', 'style' => 'width:20%; margin-left:5%;']);?>
                        <?= Html::submitButton('Upload', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:20%;']);?>
                     <?php ActiveForm::end() ?>
                <?php endif;?>
            <?php endif;?>
        </fieldset>
     </div>
</div>