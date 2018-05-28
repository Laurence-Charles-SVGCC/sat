<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    $compulsory_relations = [
        '' => 'Select...',
        'mother' => 'Mother',
        'father' => 'Father',
        'next of kin' => 'Next of Kin',
        'guardian' => 'Guardian',
        'spouse' => 'Spouse'
    ];
    
    $this->title = 'Edit Relative Details';
?>


    <div class="site-index">
        <div class = "custom_wrapper" style="min-height:3400px">
            
            <div class="custom_body" style="min-height:3250px">
                <h1 class="custom_h1">Edit Relative Details</h1>

                <?php
                    $form = ActiveForm::begin([
                                //'action' => Url::to(['gradebook/index']),
                                'id' => 'edit-relatives-form',
                                'options' => [
//                                    'class' => 'form-layout form-inline'
//                                    'class' => 'form-inline',
                                ],
                            ]);

                        if ($old_beneficiary!= false)
                        {
                            echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em'>Beneficiary</div>";
                                echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                                    echo "<tr>";
                                        echo "<th>Title</th>";
                                        echo "<td>{$form->field($optional_relations[5], '[5]title')->label('')->dropDownList(Yii::$app->params['titles'])}</td>";
                                        echo "<th>First Name</th>";
                                        echo "<td>{$form->field($optional_relations[5], '[5]firstname')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Last Name</th>";
                                        echo "<td>{$form->field($optional_relations[5], '[5]lastname')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Home Phone</th>";
                                        echo "<td>{$form->field($optional_relations[5], '[5]homephone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Cell Phone</th>";
                                        echo "<td>{$form->field($optional_relations[5], '[5]cellphone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Work Phone</th>";
                                        echo "<td>{$form->field($optional_relations[5], '[5]workphone')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        if ($optional_relations[5]->address != NULL && strcmp($optional_relations[5]->address,"") != 0)
                                        {
                                            echo "<th>Address</th>";
                                            echo "<td>{$form->field($optional_relations[5], '[5]address')->label('')->textArea(['rows' => '3'])}</td>";
                                        }
                                        else
                                        {
                                            echo "<th>Country</th>";
                                            echo "<td>{$form->field($optional_relations[5], '[5]country')->label('')->dropDownList(Yii::$app->params['country'], ['id'=>'BeneficiaryCountry', 'onchange'=>'checkBeneficiaryCountry();'])}</td>";

                                            echo "<th>Town</th>";
                                            if($optional_relations[5]->checkTown() == false)
                                                echo "<td>{$form->field($optional_relations[5], '[5]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showBeneficiaryAddressLine();' , 'style'=>'display:none', 'id'=>'BeneficiaryTown'])}</td>";
                                            else
                                                echo "<td>{$form->field($optional_relations[5], '[5]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showBeneficiaryAddressLine();' , 'style'=>'display:block', 'id'=>'BeneficiaryTown'])}</td>";                         
                                            echo "<th>Address Line</th>";
                                            if($optional_relations[5]->checkAddressline() == false)
                                                echo "<td>{$form->field($optional_relations[5], '[5]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:none", 'id'=>'BeneficiaryAddressLine'])}</td>";
                                            else
                                                echo "<td>{$form->field($optional_relations[5], '[5]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:block", 'id'=>'BeneficiaryAddressLine'])}</td>";                                            
                                        }
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Occupation</th>";
                                        echo "<td>{$form->field($optional_relations[5], '[5]occupation')->label('')->textInput(['maxlength' => true])}</td>";
                                        if ($optional_relations[5]->receivemail==1 && ($optional_relations[5]->email!=NULL || strcmp($optional_relations[5]->email,'')!= 0))
                                        {
                                            echo "<th>Eamil</th>";
                                            echo "<td>{$form->field($optional_relations[5], '[5]email')->label('')->textInput(['maxlength' => true])}</td>";
                                        }
                                    echo "</tr>";
                                echo "</table>"; 
                        }


                        if ($new_beneficiary != false)
                        {
                            echo "<div class='panel-heading' style='color:green;font-weight:bnew; font-size:1.3em'>Beneficiary</div>";
                                echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                                    echo "<tr>";
                                        echo "<th>Title</th>";
                                        echo "<td>{$form->field($compulsory_relations[0], '[0]title')->label('')->dropDownList(Yii::$app->params['titles'])}</td>";
                                        echo "<th>First Name</th>";
                                        echo "<td>{$form->field($compulsory_relations[0], '[0]firstname')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Last Name</th>";
                                        echo "<td>{$form->field($compulsory_relations[0], '[0]lastname')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Home Phone</th>";
                                        echo "<td>{$form->field($compulsory_relations[0], '[0]homephone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Cell Phone</th>";
                                        echo "<td>{$form->field($compulsory_relations[0], '[0]cellphone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Work Phone</th>";
                                        echo "<td>{$form->field($compulsory_relations[0], '[0]workphone')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        if ($compulsory_relations[0]->address != NULL && strcmp($compulsory_relations[0]->address,"") != 0)
                                        {
                                            echo "<th>Address</th>";
                                            echo "<td>{$form->field($compulsory_relations[0], '[0]address')->label('')->textArea(['rows' => '3'])}</td>";
                                        }
                                        else
                                        {
                                            echo "<th>Country</th>";
                                            echo "<td>{$form->field($compulsory_relations[0], '[0]country')->label('')->dropDownList(Yii::$app->params['country'], ['id'=>'NewBeneficiaryCountry', 'onchange'=>'checkNewBeneficiaryCountry();'])}</td>";

                                            echo "<th>Town</th>";
                                            if($compulsory_relations[0]->checkTown() == false)
                                                echo "<td>{$form->field($compulsory_relations[0], '[0]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showNewBeneficiaryAddressLine();' , 'style'=>'display:none', 'id'=>'NewBeneficiaryTown'])}</td>";
                                            else
                                                echo "<td>{$form->field($compulsory_relations[0], '[0]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showNewBeneficiaryAddressLine();' , 'style'=>'display:block', 'id'=>'NewBeneficiaryTown'])}</td>";                         
                                            echo "<th>Address Line</th>";
                                            if($compulsory_relations[0]->checkAddressline() == false)
                                                echo "<td>{$form->field($compulsory_relations[0], '[0]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:none", 'id'=>'NewBeneficiaryAddressLine'])}</td>";
                                            else
                                                echo "<td>{$form->field($compulsory_relations[0], '[0]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:block", 'id'=>'NewBeneficiaryAddressLine'])}</td>";                                            
                                        }
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Occupation</th>";
                                        echo "<td>{$form->field($compulsory_relations[0], '[0]occupation')->label('')->textInput(['maxlength' => true])}</td>";
                                        if ($compulsory_relations[0]->receivemail==1 && ($compulsory_relations[0]->email!=NULL || strcmp($compulsory_relations[0]->email,'')!= 0))
                                        {
                                            echo "<th>Eamil</th>";
                                            echo "<td>{$form->field($compulsory_relations[0], '[0]email')->label('')->textInput(['maxlength' => true])}</td>";
                                        }
                                        echo "<th>Relation Type</th>";
                                        echo "<td>{$form->field($compulsory_relations[0], '[0]relationdetail')->label('')->dropDownList($compulsory_relations)}</td>";
                                    echo "</tr>";
                                echo "</table>"; 
                        }


                        if ($old_emergencycontact!= false)
                        {
                            echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em'>Emergency Contact</div>";
                                echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                                    echo "<tr>";
                                        echo "<th>Title</th>";
                                        echo "<td>{$form->field($optional_relations[3], '[3]title')->label('')->dropDownList(Yii::$app->params['titles'])}</td>";
                                        echo "<th>First Name</th>";
                                        echo "<td>{$form->field($optional_relations[3], '[3]firstname')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Last Name</th>";
                                        echo "<td>{$form->field($optional_relations[3], '[3]lastname')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Home Phone</th>";
                                        echo "<td>{$form->field($optional_relations[3], '[3]homephone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Cell Phone</th>";
                                        echo "<td>{$form->field($optional_relations[3], '[3]cellphone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Work Phone</th>";
                                        echo "<td>{$form->field($optional_relations[3], '[3]workphone')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        if ($optional_relations[3]->address != NULL && strcmp($optional_relations[3]->address,"") != 0)
                                        {
                                            echo "<th>Address</th>";
                                            echo "<td>{$form->field($optional_relations[3], '[3]address')->label('')->textArea(['rows' => '3'])}</td>";
                                        }
                                        else
                                        {
                                            echo "<th>Country</th>";
                                            echo "<td>{$form->field($optional_relations[3], '[3]country')->label('')->dropDownList(Yii::$app->params['country'], ['id'=>'OldEmergencyContactCountry', 'onchange'=>'checkOldEmergencyContactCountry();'])}</td>";

                                            echo "<th>Town</th>";
                                            if($optional_relations[3]->checkTown() == false)
                                                echo "<td>{$form->field($optional_relations[3], '[3]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showOldEmergencyContactAddressLine();' , 'style'=>'display:none', 'id'=>'OldEmergencyContactTown'])}</td>";
                                            else
                                                echo "<td>{$form->field($optional_relations[3], '[3]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showOldEmergencyContactAddressLine();' , 'style'=>'display:block', 'id'=>'OldEmergencyContactTown'])}</td>";                         
                                            echo "<th>Address Line</th>";
                                            if($optional_relations[3]->checkAddressline() == false)
                                                echo "<td>{$form->field($optional_relations[3], '[3]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:none", 'id'=>'OldEmergencyContactAddressLine'])}</td>";
                                            else
                                                echo "<td>{$form->field($optional_relations[3], '[3]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:block", 'id'=>'OldEmergencyContactAddressLine'])}</td>";                                            
                                        }
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Occupation</th>";
                                        echo "<td>{$form->field($optional_relations[3], '[3]occupation')->label('')->textInput(['maxlength' => true])}</td>";
                                        if ($optional_relations[3]->receivemail==1 && ($optional_relations[3]->email!=NULL || strcmp($optional_relations[3]->email,'')!= 0))
                                        {
                                            echo "<th>Eamil</th>";
                                            echo "<td>{$form->field($optional_relations[3], '[3]email')->label('')->textInput(['maxlength' => true])}</td>";
                                        }
                                    echo "</tr>";
                                echo "</table>"; 
                        }


                        if ($new_emergencycontact!= false)
                        {
                            echo "<div class='panel-heading' style='color:green;font-weight:bnew; font-size:1.3em'>Emergency Contact</div>";
                                echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                                    echo "<tr>";
                                        echo "<th>Title</th>";
                                        echo "<td>{$form->field($compulsory_relations[1], '[1]title')->label('')->dropDownList(Yii::$app->params['titles'])}</td>";
                                        echo "<th>First Name</th>";
                                        echo "<td>{$form->field($compulsory_relations[1], '[1]firstname')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Last Name</th>";
                                        echo "<td>{$form->field($compulsory_relations[1], '[1]lastname')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Home Phone</th>";
                                        echo "<td>{$form->field($compulsory_relations[1], '[1]homephone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Cell Phone</th>";
                                        echo "<td>{$form->field($compulsory_relations[1], '[1]cellphone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Work Phone</th>";
                                        echo "<td>{$form->field($compulsory_relations[1], '[1]workphone')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        if ($compulsory_relations[1]->address != NULL && strcmp($compulsory_relations[1]->address,"") != 0)
                                        {
                                            echo "<th>Address</th>";
                                            echo "<td>{$form->field($compulsory_relations[1], '[1]address')->label('')->textArea(['rows' => '3'])}</td>";
                                        }
                                        else
                                        {
                                            echo "<th>Country</th>";
                                            echo "<td>{$form->field($compulsory_relations[1], '[1]country')->label('')->dropDownList(Yii::$app->params['country'], ['id'=>'NewEmergencyContactCountry', 'onchange'=>'checkNewEmergencyContactCountry();'])}</td>";

                                            echo "<th>Town</th>";
                                            if($compulsory_relations[1]->checkTown() == false)
                                                echo "<td>{$form->field($compulsory_relations[1], '[1]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showNewEmergencyContactAddressLine();' , 'style'=>'display:none', 'id'=>'NewEmergencyContactTown'])}</td>";
                                            else
                                                echo "<td>{$form->field($compulsory_relations[1], '[1]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showNewEmergencyContactAddressLine();' , 'style'=>'display:block', 'id'=>'NewEmergencyContactTown'])}</td>";                         
                                            echo "<th>Address Line</th>";
                                            if($compulsory_relations[1]->checkAddressline() == false)
                                                echo "<td>{$form->field($compulsory_relations[1], '[1]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:none", 'id'=>'NewEmergencyContactAddressLine'])}</td>";
                                            else
                                                echo "<td>{$form->field($compulsory_relations[1], '[1]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:block", 'id'=>'NewEmergencyContactAddressLine'])}</td>";                                            
                                        }
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Occupation</th>";
                                        echo "<td>{$form->field($compulsory_relations[1], '[1]occupation')->label('')->textInput(['maxlength' => true])}</td>";
                                        if ($compulsory_relations[1]->receivemail==1 && ($compulsory_relations[1]->email!=NULL || strcmp($new_beneficiery->email,'')!= 0))
                                        {
                                            echo "<th>Eamil</th>";
                                            echo "<td>{$form->field($compulsory_relations[1], '[1]email')->label('')->textInput(['maxlength' => true])}</td>";
                                        }
                                        echo "<th>Relation Type</th>";
                                        echo "<td>{$form->field($compulsory_relations[1], '[1]relationdetail')->label('')->dropDownList($compulsory_relations)}</td>";
                                    echo "</tr>";
                                echo "</table>"; 
                        }


                        if ($mother!= false)
                        {
                            echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em'>Mother</div>";
                                echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                                    echo "<tr>";
                                        echo "<th>Title</th>";
                                        echo "<td>{$form->field($optional_relations[0], '[0]title')->label('')->dropDownList(Yii::$app->params['titles'])}</td>";
                                        echo "<th>First Name</th>";
                                        echo "<td>{$form->field($optional_relations[0], '[0]firstname')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Last Name</th>";
                                        echo "<td>{$form->field($optional_relations[0], '[0]lastname')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Home Phone</th>";
                                        echo "<td>{$form->field($optional_relations[0], '[0]homephone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Cell Phone</th>";
                                        echo "<td>{$form->field($optional_relations[0], '[0]cellphone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Work Phone</th>";
                                        echo "<td>{$form->field($optional_relations[0], '[0]workphone')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        if ($optional_relations[0]->address != NULL && strcmp($optional_relations[0]->address,"") != 0)
                                        {
                                            echo "<th>Address</th>";
                                            echo "<td>{$form->field($optional_relations[0], '[0]address')->label('')->textArea(['rows' => '3'])}</td>";
                                        }
                                        else
                                        {
                                            echo "<th>Country</th>";
                                            echo "<td>{$form->field($optional_relations[0], '[0]country')->label('')->dropDownList(Yii::$app->params['country'], ['id'=>'MotherCountry', 'onchange'=>'checkMotherCountry();'])}</td>";

                                            echo "<th>Town</th>";
                                            if($optional_relations[0]->checkTown() == false)
                                                echo "<td>{$form->field($optional_relations[0], '[0]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showMotherAddressLine();' , 'style'=>'display:none', 'id'=>'MotherTown'])}</td>";
                                            else
                                                echo "<td>{$form->field($optional_relations[0], '[0]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showMotherAddressLine();' , 'style'=>'display:block', 'id'=>'MotherTown'])}</td>";                         
                                            echo "<th>Address Line</th>";
                                            if($optional_relations[0]->checkAddressline() == false)
                                                echo "<td>{$form->field($optional_relations[0], '[0]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:none", 'id'=>'MotherAddressLine'])}</td>";
                                            else
                                                echo "<td>{$form->field($optional_relations[0], '[0]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:block", 'id'=>'MotherAddressLine'])}</td>";                                            
                                        }
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Occupation</th>";
                                        echo "<td>{$form->field($optional_relations[0], '[0]occupation')->label('')->textInput(['maxlength' => true])}</td>";
                                        if ($optional_relations[0]->receivemail==1 && ($optional_relations[0]->email!=NULL || strcmp($optional_relations[0]->email,'')!= 0))
                                        {
                                            echo "<th>Eamil</th>";
                                            echo "<td>{$form->field($optional_relations[0], '[0]email')->label('')->textInput(['maxlength' => true])}</td>";
                                        }
                                    echo "</tr>";
                                echo "</table>"; 
                        }


                        if ($father!= false)
                        {
                            echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em'>Father</div>";
                                echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                                    echo "<tr>";
                                        echo "<th>Title</th>";
                                        echo "<td>{$form->field($optional_relations[1], '[1]title')->label('')->dropDownList(Yii::$app->params['titles'])}</td>";
                                        echo "<th>First Name</th>";
                                        echo "<td>{$form->field($optional_relations[1], '[1]firstname')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Last Name</th>";
                                        echo "<td>{$form->field($optional_relations[1], '[1]lastname')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Home Phone</th>";
                                        echo "<td>{$form->field($optional_relations[1], '[1]homephone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Cell Phone</th>";
                                        echo "<td>{$form->field($optional_relations[1], '[1]cellphone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Work Phone</th>";
                                        echo "<td>{$form->field($optional_relations[1], '[1]workphone')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        if ($optional_relations[1]->address != NULL && strcmp($optional_relations[1]->address,"") != 0)
                                        {
                                            echo "<th>Address</th>";
                                            echo "<td>{$form->field($optional_relations[1], '[1]address')->label('')->textArea(['rows' => '3'])}</td>";
                                        }
                                        else
                                        {
                                            echo "<th>Country</th>";
                                            echo "<td>{$form->field($optional_relations[1], '[1]country')->label('')->dropDownList(Yii::$app->params['country'], ['id'=>'FatherCountry', 'onchange'=>'checkFatherCountry();'])}</td>";

                                            echo "<th>Town</th>";
                                            if($optional_relations[1]->checkTown() == false)
                                                echo "<td>{$form->field($optional_relations[1], '[1]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showFatherAddressLine();' , 'style'=>'display:none', 'id'=>'FatherTown'])}</td>";
                                            else
                                                echo "<td>{$form->field($optional_relations[1], '[1]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showFatherAddressLine();' , 'style'=>'display:block', 'id'=>'FatherTown'])}</td>";                         
                                            echo "<th>Address Line</th>";
                                            if($optional_relations[1]->checkAddressline() == false)
                                                echo "<td>{$form->field($optional_relations[1], '[1]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:none", 'id'=>'FatherAddressLine'])}</td>";
                                            else
                                                echo "<td>{$form->field($optional_relations[1], '[1]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:block", 'id'=>'FatherAddressLine'])}</td>";                                            
                                        }
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Occupation</th>";
                                        echo "<td>{$form->field($optional_relations[1], '[1]occupation')->label('')->textInput(['maxlength' => true])}</td>";
                                        if ($optional_relations[1]->receivemail==1 && ($optional_relations[1]->email!=NULL || strcmp($optional_relations[1]->email,'')!= 0))
                                        {
                                            echo "<th>Eamil</th>";
                                            echo "<td>{$form->field($optional_relations[1], '[1]email')->label('')->textInput(['maxlength' => true])}</td>";
                                        }
                                    echo "</tr>";
                                echo "</table>"; 
                        }


                        if ($spouse!= false)
                        {
                            echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em'>Spouse</div>";
                                echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                                    echo "<tr>";
                                        echo "<th>Title</th>";
                                        echo "<td>{$form->field($optional_relations[6], '[6]title')->label('')->dropDownList(Yii::$app->params['titles'])}</td>";
                                        echo "<th>First Name</th>";
                                        echo "<td>{$form->field($optional_relations[6], '[6]firstname')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Last Name</th>";
                                        echo "<td>{$form->field($optional_relations[6], '[6]lastname')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Home Phone</th>";
                                        echo "<td>{$form->field($optional_relations[6], '[6]homephone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Cell Phone</th>";
                                        echo "<td>{$form->field($optional_relations[6], '[6]cellphone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Work Phone</th>";
                                        echo "<td>{$form->field($optional_relations[6], '[6]workphone')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        if ($optional_relations[6]->address != NULL && strcmp($optional_relations[6]->address,"") != 0)
                                        {
                                            echo "<th>Address</th>";
                                            echo "<td>{$form->field($optional_relations[6], '[6]address')->label('')->textArea(['rows' => '3'])}</td>";
                                        }
                                        else
                                        {
                                            echo "<th>Country</th>";
                                            echo "<td>{$form->field($optional_relations[6], '[6]country')->label('')->dropDownList(Yii::$app->params['country'], ['id'=>'SpouseCountry', 'onchange'=>'checkSpouseCountry();'])}</td>";

                                            echo "<th>Town</th>";
                                            if($optional_relations[6]->checkTown() == false)
                                                echo "<td>{$form->field($optional_relations[6], '[6]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showSpouseAddressLine();' , 'style'=>'display:none', 'id'=>'SpouseTown'])}</td>";
                                            else
                                                echo "<td>{$form->field($optional_relations[6], '[6]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showSpouseAddressLine();' , 'style'=>'display:block', 'id'=>'SpouseTown'])}</td>";                         
                                            echo "<th>Address Line</th>";
                                            if($optional_relations[6]->checkAddressline() == false)
                                                echo "<td>{$form->field($optional_relations[6], '[6]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:none", 'id'=>'SpouseAddressLine'])}</td>";
                                            else
                                                echo "<td>{$form->field($optional_relations[6], '[6]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:block", 'id'=>'SpouseAddressLine'])}</td>";                                            
                                        }
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Occupation</th>";
                                        echo "<td>{$form->field($optional_relations[6], '[6]occupation')->label('')->textInput(['maxlength' => true])}</td>";
                                        if ($optional_relations[6]->receivemail==1 && ($optional_relations[6]->email!=NULL || strcmp($optional_relations[6]->email,'')!= 0))
                                        {
                                            echo "<th>Eamil</th>";
                                            echo "<td>{$form->field($optional_relations[6], '[6]email')->label('')->textInput(['maxlength' => true])}</td>";
                                        }
                                    echo "</tr>";
                                echo "</table>"; 
                        }


                        if ($nextofkin!= false)
                        {
                            echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em'>Next Of Kin</div>";
                                echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                                    echo "<tr>";
                                        echo "<th>Title</th>";
                                        echo "<td>{$form->field($optional_relations[2], '[2]title')->label('')->dropDownList(Yii::$app->params['titles'])}</td>";
                                        echo "<th>First Name</th>";
                                        echo "<td>{$form->field($optional_relations[2], '[2]firstname')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Last Name</th>";
                                        echo "<td>{$form->field($optional_relations[2], '[2]lastname')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Home Phone</th>";
                                        echo "<td>{$form->field($optional_relations[2], '[2]homephone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Cell Phone</th>";
                                        echo "<td>{$form->field($optional_relations[2], '[2]cellphone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Work Phone</th>";
                                        echo "<td>{$form->field($optional_relations[2], '[2]workphone')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        if ($optional_relations[2]->address != NULL && strcmp($optional_relations[2]->address,"") != 0)
                                        {
                                            echo "<th>Address</th>";
                                            echo "<td>{$form->field($optional_relations[2], '[2]address')->label('')->textArea(['rows' => '3'])}</td>";
                                        }
                                        else
                                        {
                                            echo "<th>Country</th>";
                                            echo "<td>{$form->field($optional_relations[2], '[2]country')->label('')->dropDownList(Yii::$app->params['country'], ['id'=>'NextOfKinCountry', 'onchange'=>'checkNextOfKinCountry();'])}</td>";

                                            echo "<th>Town</th>";
                                            if($optional_relations[2]->checkTown() == false)
                                                echo "<td>{$form->field($optional_relations[2], '[2]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showNextOfKinAddressLine();' , 'style'=>'display:none', 'id'=>'NextOfKinTown'])}</td>";
                                            else
                                                echo "<td>{$form->field($optional_relations[2], '[2]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showNextOfKinAddressLine();' , 'style'=>'display:block', 'id'=>'NextOfKinTown'])}</td>";                         
                                            echo "<th>Address Line</th>";
                                            if($optional_relations[2]->checkAddressline() == false)
                                                echo "<td>{$form->field($optional_relations[2], '[2]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:none", 'id'=>'NextOfKinAddressLine'])}</td>";
                                            else
                                                echo "<td>{$form->field($optional_relations[2], '[2]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:block", 'id'=>'NextOfKinAddressLine'])}</td>";                                            
                                        }
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Occupation</th>";
                                        echo "<td>{$form->field($optional_relations[2], '[2]occupation')->label('')->textInput(['maxlength' => true])}</td>";
                                        if ($optional_relations[2]->receivemail==1 && ($optional_relations[2]->email!=NULL || strcmp($optional_relations[2]->email,'')!= 0))
                                        {
                                            echo "<th>Eamil</th>";
                                            echo "<td>{$form->field($optional_relations[2], '[2]email')->label('')->textInput(['maxlength' => true])}</td>";
                                        }
                                    echo "</tr>";
                                echo "</table>"; 
                        }


                        if ($guardian!= false)
                        {
                            echo "<div class='panel-heading' style='color:green;font-weight:bold; font-size:1.3em'>Guardian</div>";
                                echo "<table class='table table-hover' style='margin: 0 auto;'>";                                        
                                    echo "<tr>";
                                        echo "<th>Title</th>";
                                        echo "<td>{$form->field($optional_relations[4], '[4]title')->label('')->dropDownList(Yii::$app->params['titles'])}</td>";
                                        echo "<th>First Name</th>";
                                        echo "<td>{$form->field($optional_relations[4], '[4]firstname')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Last Name</th>";
                                        echo "<td>{$form->field($optional_relations[4], '[4]lastname')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Home Phone</th>";
                                        echo "<td>{$form->field($optional_relations[4], '[4]homephone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Cell Phone</th>";
                                        echo "<td>{$form->field($optional_relations[4], '[4]cellphone')->label('')->textInput(['maxlength' => true])}</td>";
                                        echo "<th>Work Phone</th>";
                                        echo "<td>{$form->field($optional_relations[4], '[4]workphone')->label('')->textInput(['maxlength' => true])}</td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                        if ($optional_relations[4]->address != NULL && strcmp($optional_relations[4]->address,"") != 0)
                                        {
                                            echo "<th>Address</th>";
                                            echo "<td>{$form->field($optional_relations[4], '[4]address')->label('')->textArea(['rows' => '3'])}</td>";
                                        }
                                        else
                                        {
                                            echo "<th>Country</th>";
                                            echo "<td>{$form->field($optional_relations[4], '[4]country')->label('')->dropDownList(Yii::$app->params['country'], ['id'=>'GuardianCountry', 'onchange'=>'checkGuardianCountry();'])}</td>";

                                            echo "<th>Town</th>";
                                            if($optional_relations[4]->checkTown() == false)
                                                echo "<td>{$form->field($optional_relations[4], '[4]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showGuardianAddressLine();' , 'style'=>'display:none', 'id'=>'GuardianTown'])}</td>";
                                            else
                                                echo "<td>{$form->field($optional_relations[4], '[4]town')->label('')->dropDownList(Yii::$app->params['towns'], ['onchange'=> 'showGuardianAddressLine();' , 'style'=>'display:block', 'id'=>'GuardianTown'])}</td>";                         
                                            echo "<th>Address Line</th>";
                                            if($optional_relations[4]->checkAddressline() == false)
                                                echo "<td>{$form->field($optional_relations[4], '[4]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:none", 'id'=>'GuardianAddressLine'])}</td>";
                                            else
                                                echo "<td>{$form->field($optional_relations[4], '[4]addressline')->label('')->textInput(['maxlength' => true, 'style'=>"display:block", 'id'=>'GuardianAddressLine'])}</td>";                                            
                                        }
                                    echo "</tr>";

                                    echo "<tr>";
                                        echo "<th>Occupation</th>";
                                        echo "<td>{$form->field($optional_relations[4], '[4]occupation')->label('')->textInput(['maxlength' => true])}</td>";
                                        if ($optional_relations[4]->receivemail==1 && ($optional_relations[4]->email!=NULL || strcmp($optional_relations[4]->email,'')!= 0))
                                        {
                                            echo "<th>Eamil</th>";
                                            echo "<td>{$form->field($optional_relations[4], '[4]email')->label('')->textInput(['maxlength' => true])}</td>";
                                        }
                                    echo "</tr>";
                                echo "</table>"; 
                        }

                        echo Html::a(' Cancel',['profile/student-profile', 'personid' => $personid, 'studentregistrationid' => $studentregistrationid], ['class' => 'btn btn-block btn-lg btn-danger glyphicon glyphicon-remove-circle pull-left', 'style' => 'width:25%; margin-left:15%;']);
                        echo Html::submitButton('Save', ['class' => 'btn btn-block btn-lg btn-success pull-right', 'style' => 'width:25%; margin-right:15%;']);

                        ActiveForm::end();    
                ?>
            </div>
        </div>
    </div>

