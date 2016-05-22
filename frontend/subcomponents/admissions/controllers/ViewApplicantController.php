<?php

    namespace app\subcomponents\admissions\controllers;

    use Yii;
    use yii\helpers\Url;
    use yii\base\Model;
    use yii\data\ArrayDataProvider;
    
    use common\models\User;
    use frontend\models\Applicant;
    use frontend\models\Application;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\ApplicationCapesubject;
    use frontend\models\Offer;
    use frontend\models\PersonInstitution;
    use frontend\models\Institution;
    use frontend\models\Phone;
    use frontend\models\Email;
    use frontend\models\Relation;
    use frontend\models\ApplicationHistory;
    use frontend\models\Address;
    use frontend\models\MedicalCondition;
    use frontend\models\Division;
    use frontend\models\CsecQualification;
    use frontend\models\CsecCentre;
    use frontend\models\ExaminationBody;
    use frontend\models\Subject;
    use frontend\models\ExaminationProficiencyType;
    use frontend\models\ExaminationGrade;
    use frontend\models\CompulsoryRelation;
    use frontend\models\RelationType;
    use frontend\models\GeneralWorkExperience;
    use frontend\models\Reference;
    use frontend\models\TeachingAdditionalInfo;
    use frontend\models\TeachingExperience;
    use frontend\models\NursingAdditionalInfo;
    use frontend\models\NurseWorkExperience;
    use frontend\models\NursePriorCertification;
    use frontend\models\CriminalRecord;
    use frontend\models\ApplicationStatus;
    use frontend\models\PostSecondaryQualification;
    use frontend\models\ExternalQualification;
    use frontend\models\DocumentIntent;
    use frontend\models\DocumentSubmitted;
    use frontend\models\DocumentType;
    use frontend\models\Employee;



class ViewApplicantController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index',
            [
                'results' => Null,
                'result_users' => Null,
                'info_string' => '',
            ]);
    }
    
    /*
    * Purpose: Collect search parameters and display results of an applicant search.
    * Created: 1/08/2015 by Gamal Crichton
    * Last Modified: 1/08/2015 by Gamal Crichton
    */
    public function actionSearchApplicant()
    {
        $dataProvider = $app_ids = NULL;
        $info_string = "";
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            $app_id = $request->post('id');
            $firstname = $request->post('firstname');
            $lastname = $request->post('lastname');
            $email = $request->post('email');
            
            if ($app_id)
            {
                $user = User::findOne(['username' => $app_id, 'isdeleted' => 0]);
                 $cond_arr['personid'] = $user ? $user->personid : NULL;
                 $info_string = $info_string .  " Applicant ID: " . $app_id;
            }
            if ($firstname)
            {
                $cond_arr['firstname'] = $firstname;
                $info_string = $info_string .  " First Name: " . $firstname; 
            }
            if ($lastname)
            {
                $cond_arr['lastname'] = $lastname;
                $info_string = $info_string .  " Last Name: " . $lastname;
            }
            if ($email)
            {
                $email_add = Email::findOne(['email' => $email, 'isdeleted' => 0]);
                 $cond_arr['personid'] = $email_add ? $email_add->personid : NULL;
                 $info_string = $info_string .  " Email: " . $email;
            }
            
            if (empty($cond_arr))
            {
                Yii::$app->getSession()->setFlash('error', 'A search criteria must be entered.');
            }
            else
            {
                $cond_arr['isdeleted'] = 0;  
            
                $applicants = Applicant::find()->where($cond_arr)->all();
                if (empty($applicants))
                {
                    Yii::$app->getSession()->setFlash('error', 'No user found matching this criteria.');
                }
                else
                {
                    $data = array();
                    foreach ($applicants as $applicant)
                    {
                        $app = array();
                        $user = $applicant->getPerson()->one();
                        
                        $app['username'] = $user ? $user->username : '';
                        $app['applicantid'] = $applicant->applicantid;
                        $app['firstname'] = $applicant->firstname;
                        $app['middlename'] = $applicant->middlename;
                        $app['lastname'] = $applicant->lastname;
                        $app['gender'] = $applicant->gender;
                        $app['dateofbirth'] = $applicant->dateofbirth;
                        $data[] = $app;
                    }
                    $dataProvider = new ArrayDataProvider([
                        'allModels' => $data,
                        'pagination' => [
                            'pageSize' => 100,
                        ],
                        'sort' => [
                            'attributes' => ['applicantid', 'firstname', 'lastname'],
                            ]
                    ]);
                    if (!$user)
                    {
                        Yii::$app->session->setFlash('error', 'User not found');
                    }
                }
        }
    }
    return $this->render('index', 
        [
            'results' => $dataProvider,
            'result_users' => $app_ids,
            'info_string' => $info_string,
        ]);
  }
  
  /*
    * Purpose: Retrieve information necessary to display results of an applicant search.
    * Created: 1/08/2015 by Gamal Crichton
    * Last Modified: 1/08/2015 by Gamal Crichton
    */
  public function actionViewApplicant($applicantid, $username = '')
  {
      $applicant = Applicant::findOne(['applicantid' => $applicantid]);
      $personid = $applicant->getPerson()->one() ? $applicant->getPerson()->one()->personid : NULL;
      $applications = $personid ? Application::findAll(['personid' => $personid, 'isdeleted' => 0]) : array();
      $data = array();
        foreach($applications as $application)
        {
            $app_details = array();
            $app_his = ApplicationHistory::find()->where(['applicationid' => $application->applicationid,
                'isdeleted' => 0])->orderBy('applicationhistoryid DESC', 'desc')->one();
            $cape_subjects_names = array();
            $programme = ProgrammeCatalog::find()
                ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->where(['application.applicationid' => $application->applicationid])->one();
            $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
            foreach ($cape_subjects as $cs) { $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; }
            $offers = Offer::findAll(['applicationid' => $application->applicationid, 'isdeleted' => 0]);
            $off = '';
            foreach($offers as $offer) {$off = $off . $offer->offerid . ', ';}
            
             $app_status = $application->getApplicationStatus() ? $application->getApplicationStatus()->one() : Null;
             $status = NULL;
             if ($app_status && $app_status->applicationstatusid == 1)
             {
                 if ($app_his && $app_his->applicationstatusid > 1)
                 {
                     $status = "Unverified";
                 }
             }
            
            $app_details['order'] = $application->ordering;
            $app_details['applicationid'] = $application->applicationid;
            $app_details['programme_name'] = $programme->getFullName();
            $app_details['subjects'] = implode(' ,', $cape_subjects_names);
            $app_details['offerid'] = $offers ? $off : Null;
            $app_details['divisionid'] = $application->divisionid;
            $app_details['application_status'] = $status ? $status : ($app_status ? $app_status->name : NULL); 

            $data[] = $app_details;
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
      
      return $this->render('view-applicant', 
              [
                  'applicant' => $applicant,
                  'dataProvider' => $dataProvider,
                  'username' => $username,
              ]);
  }
  
  /*
    * Purpose: Junction for various action to eb done to an applicant after an applicant search.
    * Created: 3/08/2015 by Gamal Crichton
    * Last Modified: 6/08/2015 by Gamal Crichton
    */
  public function actionApplicantActions()
  {
      if (Yii::$app->request->post())
      {
          $request = Yii::$app->request;
          $applicantusername = $request->post('applicantusername');
          if ($request->post('register') === '')
          {
              return $this->redirect(Url::to(['register-student/register-applicant', 'applicantusername' => $applicantusername]));
          }
          if ($request->post('view_personal') === '')
          {
              return $this->redirect(Url::to(['view-applicant/view-personal', 'applicantusername' => $applicantusername]));
          }
          if ($request->post('edit_personal') === '')
          {
              return $this->redirect(Url::to(['view-applicant/edit-personal', 'applicantusername' => $applicantusername]));
          }
          if ($request->post('view_review') === '')
          {
              return $this->redirect(Url::to(['view-applicant/review', 'applicantusername' => $applicantusername]));
          }
          if ($request->post('publish_decision') === '')
          {
              return $this->redirect(Url::to(['view-applicant/publish-decision', 'applicantusername' => $applicantusername]));
          }
          
          //Laurence Charles implementation
          if ($request->post('applicant_profile') === '')
          {
              return $this->redirect(Url::to(['view-applicant/applicant-profile', 'applicantusername' => $applicantusername]));
          }
          
      }
  }
  
  /*
    * Purpose: Prepares applicant personal information for viewing only
    * Created: 6/08/2015 by Gamal Crichton
    * Last Modified: 12/08/2015 by Gamal Crichton
    */
  public function actionViewPersonal($applicantusername)
  {
      $user = User::findOne(['username' =>$applicantusername]);
      $applicant = $user ? Applicant::findOne(['personid' =>$user->personid]) : Null;
      $institutions = $applicant ? PersonInstitution::findAll(['personid' => $applicant->personid, 'isdeleted' => 0]) : array();
      $phone = $user ? Phone::findOne(['personid' =>$user->personid]) : NULL;
      $email = $user ? Email::findOne(['personid' =>$user->personid]) : NULL;
      $relations = $user ? Relation::findAll(['personid' =>$user->personid]) : NULL;
      
      if (!$applicant)
      {
          Yii::$app->session->setFlash('error', 'No details found for this applicant.');
      }
      
      return $this->render('view-applicant-details',
              [
                  'username' => $user ? $user->username : '',
                  'applicant' => $applicant,
                  'institutions' => $institutions,
                  'phone' => $phone,
                  'email' => $email,
                  'relations' => $relations,
              ]);
  }
  
  /*
    * Purpose: Prepares applicant personal information for editing
    * Created: 6/08/2015 by Gamal Crichton
    * Last Modified: 6/08/2015 by Gamal Crichton
    */
  public function actionEditPersonal($applicantusername)
  {
      if (Yii::$app->request->post())
      {
          $request = Yii::$app->request;
          $applicant = Applicant::findOne(['applicantid' => $request->post('applicantid')]);
          $institutions = $applicant ? PersonInstitution::findAll(['personid' => $applicant->personid, 'isdeleted' => 0]) : array();
          $phone = $applicant ? Phone::findOne(['personid' =>$applicant->personid]) : NULL;
          $email = $applicant ? Email::findOne(['personid' =>$applicant->personid]) : NULL;
          $relations = $applicant ? Relation::findAll(['personid' =>$applicant->personid]) : NULL;
          if ($applicant->load(Yii::$app->request->post()) && $phone->load(Yii::$app->request->post()) &&
                  $email->load(Yii::$app->request->post()))
          { 
              if (!$applicant->save() && $phone->save() && $email->save())
              {
                  Yii::$app->session->setFlash('error', 'Applicant could not be updated');
                  //$this->redirect(Url::to(['view-applicant/view-personal', 'applicantusername' =>$request->post('username')]));
              }
              Yii::$app->session->setFlash('error', 'Applicant could not be saved');
          }
          foreach($request->post('Relation') as $key =>$rel)
          { 
              $relation = Relation::findOne(['relationid' =>$key]);
              if ($relation)
              {
                  $relation->firstname = $rel['firstname'];
                  $relation->lastname = $rel['lastname'];
                  $relation->homephone = $rel['homephone'];
                  $relation->cellphone = $rel['cellphone'];
                  $relation->workphone = $rel['workphone'];
                  if (!$relation->save())
                  {
                      Yii::$app->session->setFlash('error', 'Relation could not be saved');
                  } 
              }
          }
          
          foreach($request->post('PersonInstitution') as $key =>$pins)
          { 
              $pi = PersonInstitution::findOne(['personinstitutionid' =>$key]);
              if ($pi)
              {
                  $ins = $request->post('Institution');
                          
                  $pi->institutionid = $ins ? $ins[$key]['institutionid'] : NULL;
                  $pi->startdate = $pins['startdate'];
                  $pi->enddate = $pins['enddate'];
                  $pi->hasgraduated = $pins['hasgraduated'];
                  if (!$pi->save())
                  {
                      Yii::$app->session->setFlash('error', 'Attendance could not be saved');
                  }
              }
          }
          $this->redirect(Url::to(['view-applicant/view-personal', 'applicantusername' =>$request->post('username')]));
          /*else
          {
              Yii::$app->session->setFlash('error', 'Applicant not found');
          }*/ 
      }
      $user = User::findOne(['username' =>$applicantusername]);
      $applicant = $user ? Applicant::findOne(['personid' =>$user->personid]) : Null;
      $institutions = $applicant ? PersonInstitution::findAll(['personid' => $applicant->personid, 'isdeleted' => 0]) : array();
      $phone = $user ? Phone::findOne(['personid' =>$user->personid]) : NULL;
      $email = $user ? Email::findOne(['personid' =>$user->personid]) : NULL;
      $relations = $user ? Relation::findAll(['personid' =>$user->personid]) : NULL;
      
      if (!$applicant)
      {
          Yii::$app->session->setFlash('error', 'No details found for this applicant.');
      }
      
      return $this->render('edit-applicant-details',
              [
                  'username' => $user ? $user->username : '',
                  'applicant' => $applicant,
                  'institutions' => $institutions,
                  'phone' => $phone,
                  'email' => $email,
                  'relations' => $relations,
              ]);
  }
  
  private function getApplicantDetails($applicantusername)
  {
      $user = User::findOne(['username' =>$applicantusername]);
      $applicant = $user ? Applicant::findOne(['personid' =>$user->personid]) : Null;
      if ($applicant)
      {
          $institutions = PersonInstitution::findAll(['personid' => $applicant->personid, 'isdeleted' => 0]);
          
          $app['applicantid'] = $applicant->applicantid;
          $app['username'] = $user->username;
          $app['title'] = $applicant->title;
          $app['firstname'] = $applicant->firstname;
          $app['middlename'] = $applicant->middlename;
          $app['lastname'] = $applicant->lastname;
          $app['gender'] = $applicant->gender;
          $app['dateofbirth'] = $applicant->dateofbirth;
          $app['nationality'] = $applicant->nationality;
          $app['placeofbirth'] = $applicant->placeofbirth;
          $app['religion'] = $applicant->religion;
          $app['sponsor'] = $applicant->sponsorname;
          $app['clubs'] = $applicant->clubs;
          $app['otherinterests'] = $applicant->otherinterests;
          $app['maritalstatus'] = $applicant->maritalstatus;
          $app['institution'] = array();
          foreach ($institutions as $key => $institution)
          {
              $in = Institution::findone(['institutionid' => $institution->institutionid, 'isdeleted' => 0]);
              $app['institution'][$key]['name'] = $in ? $in->name : '';
              $app['institution'][$key]['formername'] = $in ? $in->formername : '';
              $app['institution'][$key]['startdate'] = $institution->startdate;
              $app['institution'][$key]['enddate'] = $institution->enddate;
              $app['institution'][$key]['hasgraduated'] = $institution->hasgraduated;
          }
          return $app;
      }
      return Null;
  }
  
  /*
    * Purpose: Allows applicant to review entire application [no submission functionality]
    * Created: ?/2015 by Laurence Charles (For Apply)
    * Last Modified: 21/08/2015 by Gamal Crichton
    */
    public function actionReview($applicantusername)
    {
        $user = User::findOne(['username' =>$applicantusername]);
        $applicant = $user ? Applicant::findOne(['personid' => $user->personid]) : NULL;
        $personid = $applicant ? $applicant->personid : Null;
        
        $permanentaddress = Address::findOne(['personid' => $personid, 'addresstypeid' => 1]);            
        $residentaladdress = Address::findOne(['personid' => $personid, 'addresstypeid' => 2]);
        $postaladdress = Address::findOne(['personid' => $personid, 'addresstypeid' => 3]);
        $addresses = [$permanentaddress, $residentaladdress, $postaladdress];
        
        $phone = Phone::findOne(['personid' => $applicant->personid, 'isdeleted' => 0]);
        
        $relatives = Relation::findAll(['personid' => $personid, 'isdeleted' => 0]);
        $mother = false;
        $father = false;
        $nextofkin = false;
        $emergencycontact = false;
        $guardian = false;
        $beneficiary = false;
        $spouse = false;
        
        foreach($relatives as $relative){
            if ($relative->relationtypeid == 1){
                $mother = $relative;
            }
            else if ($relative->relationtypeid == 2){
                $father = $relative;
            }
            else if ($relative->relationtypeid == 3){
                $nextofkin = $relative;
            }
            else if ($relative->relationtypeid == 4){
                $emergencycontact = $relative;
            }
            else if ($relative->relationtypeid == 5){
                $guardian = $relative;
            }
            else if ($relative->relationtypeid == 6){
                $beneficiary = $relative;
            }
            else if ($relative->relationtypeid == 7){
                $spouse = $relative;
            }          
        }
        
        $medicalConditions = MedicalCondition::findAll(['personid' => $personid, 'isdeleted' => 0]);

        $applications = Application::findAll(['personid' => $personid, 'isdeleted' => 0]);
        $first = array();
        $firstDetails = array();
        $second = array();
        $secondDetails = array();
        $third = array();
        $thirdDetails = array();
        
        foreach($applications as $application)
        {
            $capeSubjects = NULL;
            $isCape = NULL;
            $division = NULL;
            $programme = NULL;
            $d = NULL;
            $p = NULL;
            if ($application->ordering == 1){
                array_push($first, $application);
                $isCape = Application::isCapeApplication($application->academicofferingid);
                if ($isCape == true){
                  $capeSubjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]); //getRecords($application->applicationid);
                  array_push($first, $capeSubjects);
                }
                $d = Division::find()
                        ->where(['divisionid' => $application->divisionid])
                        ->one();
                $division = $d->name;
                array_push($firstDetails, $division);
                
                $programme = ProgrammeCatalog::find()
                        ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                        ->where(['academic_offering.academicofferingid' => $application->academicofferingid])
                        ->one();
                array_push($firstDetails, $programme->getFullName());
            }
            
            else if ($application->ordering == 2){
                array_push($second, $application);
                $isCape = Application::isCapeApplication($application->academicofferingid);
                if ($isCape == true){
                  $capeSubjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]); //getRecords($application->applicationid);
                  array_push($second, $capeSubjects);
                }
                 $d = Division::find()
                        ->where(['divisionid' => $application->divisionid])
                        ->one();
                $division = $d->name;
                array_push($secondDetails, $division);
                
                $programme = ProgrammeCatalog::find()
                        ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                        ->where(['academic_offering.academicofferingid' => $application->academicofferingid])
                        ->one();
                array_push($secondDetails, $programme->getFullName());
            }
            else if ($application->ordering == 3){
                array_push($third, $application);
                $isCape = Application::isCapeApplication($application->academicofferingid);
                if ($isCape == true){
                  $capeSubjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]); //getRecords($application->applicationid);
                  array_push($third, $capeSubjects);
                }
                 $d = Division::find()
                        ->where(['divisionid' => $application->divisionid])
                        ->one();
                $division = $d->name;
                array_push($thirdDetails, $division);
                
                $programme = ProgrammeCatalog::find()
                        ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                        ->where(['academic_offering.academicofferingid' => $application->academicofferingid])
                        ->one();
                array_push($thirdDetails, $programme->getFullName());
            }
        }
        
        $preschools = PersonInstitution::find()
                ->innerJoin('institution', '`institution`.`institutionid` = `person_institution`.`institutionid`')
                ->where(['person_institution.personid' => $personid, 'levelid' => 1, 'person_institution.isdeleted' => 0])
                ->all();
        $preschoolNames = array();
        if ($preschools!=false){
            foreach ($preschools as $preschool){
                $name = NULL;
                $record = NULL;
                $record = Institution::find()
                        ->where(['institutionid' => $preschool->institutionid])
                        ->one();     
                $name = $record->name;
                array_push($preschoolNames, $name);          
            }
        }
        
        $primaryschools = PersonInstitution::find()
                ->innerJoin('institution', '`institution`.`institutionid` = `person_institution`.`institutionid`')
                ->where(['person_institution.personid' => $personid, 'levelid' => 2, 'person_institution.isdeleted' => 0])
                ->all();
        $primaryschoolNames = array();
        if ($primaryschools!=false){
            foreach ($primaryschools as $primaryschool){
                $name = NULL;
                $record = NULL;
                $record = Institution::find()
                        ->where(['institutionid' => $primaryschool->institutionid])
                        ->one();     
                $name = $record->name;
                array_push($primaryschoolNames, $name); 
            }
        }
        
        $secondaryschools = PersonInstitution::find()
                ->innerJoin('institution', '`institution`.`institutionid` = `person_institution`.`institutionid`')
                ->where(['person_institution.personid' => $personid, 'levelid' => 3, 'person_institution.isdeleted' => 0])
                ->all();
        $secondaryschoolNames = array();
        if ($secondaryschools!=false){
            foreach ($secondaryschools as $secondaryschool){
                $name = NULL;
                $record = NULL;
                $record = Institution::find()
                        ->where(['institutionid' => $secondaryschool->institutionid])
                        ->one();       
                $name = $record->name;
                array_push($secondaryschoolNames, $name); 
            }
        }
        
        $tertieryschools = PersonInstitution::find()
                ->innerJoin('institution', '`institution`.`institutionid` = `person_institution`.`institutionid`')
                ->where(['person_institution.personid' => $personid, 'levelid' => 4, 'person_institution.isdeleted' => 0])
                ->all();
        $tertieryschoolNames = array();
        if ($tertieryschools!=false){
            foreach ($tertieryschools as $tertieryschool){
                $name = NULL;
                $record = NULL;
                $record = Institution::find()
                        ->where(['institutionid' => $tertieryschool->institutionid])
                        ->one();  
                $name = $record->name;
                array_push($tertieryschoolNames, $name); 
            }
        }
         
        
        $qualifications = CsecQualification::findAll(['personid' => $personid, 'isdeleted' => 0]); //getQualifications($id);
        $qualificationDetails = array();
        
        if ($qualifications != false)
        {
            $keys = ['centrename', 'examinationbody', 'subject', 'proficiency', 'grade'];
            foreach ($qualifications as $qualification){
                $values = array();
                $combined = array();
                $centre = CsecCentre::find()
                        ->where(['cseccentreid' => $qualification->cseccentreid])
                        ->one();
                array_push($values, $centre->name);
                $examinationbody = ExaminationBody::find()
                        ->where(['examinationbodyid' => $qualification->examinationbodyid])
                        ->one();
                array_push($values, $examinationbody->abbreviation);
                $subject = Subject::find()
                        ->where(['subjectid' => $qualification->subjectid])
                        ->one();
                array_push($values, $subject->name);
                $proficiency = ExaminationProficiencyType::find()
                        ->where(['examinationproficiencytypeid' => $qualification->examinationproficiencytypeid])
                        ->one();
                array_push($values, $proficiency->name);
                $grade = ExaminationGrade::find()
                        ->where(['examinationgradeid' => $qualification->examinationgradeid])
                        ->one();
                array_push($values, $grade->name);
                $combined = array_combine($keys,$values);
                array_push($qualificationDetails, $combined);
                $values = NULL;
                $combined = NULL;
            }
        }
        
              
        return $this->render('review', [
            'applicant' => $applicant,
            'addresses' => $addresses,
            'phone'=> $phone,
            'mother' => $mother,
            'father' => $father,
            'nextofkin' => $nextofkin,
            'emergencycontact' => $emergencycontact,
            'guardian' =>  $guardian,
            'beneficiary' => $beneficiary,
            'spouse' => $spouse,
            'medicalConditions' => $medicalConditions,
            'qualifications' => $qualifications,
            'qualificationDetails' => $qualificationDetails,
            'first' => $first,
            'firstDetails' =>$firstDetails,
            'second' => $second,
            'secondDetails' =>$secondDetails,
            'third' => $third,
            'thirdDetails' =>$thirdDetails,
            'preschools' => $preschools,
            'preschoolNames' => $preschoolNames,
            'primaryschools' => $primaryschools,
            'primaryschoolNames' => $primaryschoolNames,
            'secondaryschools' => $secondaryschools,
            'secondaryschoolNames' => $secondaryschoolNames,
            'tertieryschools' => $tertieryschools,
            'tertieryschoolNames' => $tertieryschoolNames,  
        ]);
    }
    
    public function actionPublishDecision($applicantusername)
      {
          $user = User::findOne(['username' =>$applicantusername]);
          $applicant = $user ? Applicant::findOne(['personid' =>$user->personid]) : Null;
          
          if ($applicant)
          {
              $email = $user ? Email::findOne(['personid' =>$applicant->personid,  'isdeleted' => 0]) : NULL;
              $firstname = $applicant->firstname;
              $lastname = $applicant->lastname;
              $offer_cond = array( 'application_period.isactive' => 1, 'offer.isdeleted' => 0, 
                'application.isdeleted' => 0, 'application.personid' => $applicant->personid);

               $offers = Offer::find()
                    ->joinWith('application')
                    ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where($offer_cond)
                    ->all();
               if (count($offers) == 1) 
               {
                   $offer = $offers[0];
                   $cape_subjects_names = array();
                    $application = $offer->getApplication()->one();
                    $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
                    $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
                    foreach ($cape_subjects as $cs) { $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; }
                    $division = Division::findOne(['divisionid' => $application->divisionid]);

                    $divisionabbr = strtolower($division->abbreviation);
                    $viewfile = 'publish-offer-' . $divisionabbr;
                    if (count($cape_subjects) > 0)
                    {
                        $viewfile = $viewfile . '-cape';
                    }
                    $divisioname = $division->name;
                    
                    $studentno = $applicant->potentialstudentid;
                    $programme_name = empty($cape_subjects) ? $programme->getFullName() : $programme->name . ": " . implode(', ', $cape_subjects_names);

                    $attachments = array('../files/Library_Pre-Registration_Forms.PDF', '../files/Ecollege_services.pdf', '../files/Internet_and_Multimedia_Services_Policies.PDF',
                        '../files/Uniform_Requirements_2015.pdf', '../files/Library_Information_Brochure.PDF');

                    if ($division->divisionid == 5)
                    {
                        $attachments = array_merge($attachments, array('../files/Additional_requirements_for_Hospitality_and_Agricultural_Science_and_Entrepreneurship.pdf',
                            '../files/DTVE_PROGRAMME_FEES.pdf', '../files/Terms_of_Agreement_for_Discipline_DTVE.pdf',
                            '../files/DTVE_Orientation_ Schedule_August_2015.pdf'));
                    }
                    if ($division->divisionid == 4)
                    {
                        $attachments = array_merge($attachments, array('../files/Terms_of_Agreement_for_Discipline_DASGS.pdf',
                            '../files/Orientation_Groups_DASGS.pdf', '../files/Timetable_for_Orientation_2015-2016_DASGS.pdf'));
                    }

                    if (($email && $email->email))
                    {
                        
                        $attach = implode('::', $attachments);
                        if(OfferController::actionPublishOffer($firstname, $lastname, $studentno, $programme_name, $divisioname, 
                            $email->email, 'Your SVGCC Application', $viewfile, $attach))
                        {
                            $offer->ispublished = 1;
                            $offer->save();
                            //return $this->redirect(Url::to(['index']));
                        }
                        else
                        {
                            Yii::$app->session->setFlash('error', 'There was a mail error.');
                        }
                    }
               }
               else if (count($offers) == 0)
               {
                   $rejected = False;
                   $app_status = \frontend\models\ApplicationStatus::findOne(['name' => 'rejected']);
                   $applications = Application::findAll(['personid' => $applicant->personid, 'isdeleted' => 0]);
                   foreach ($applications as $application)
                   {
                       if ($app_status && $application->applicationstatusid == $app_status->applicationstatusid)
                       {
                           $rejected = True;
                       }
                   }
                   if ($rejected && $email && $email->email)
                   {
                       OfferController::actionPublishReject($firstname, $lastname, $email->email, 'Your SVGCC Application');
                   }
                   else
                   {
                       Yii::$app->session->setFlash('error', 'Applicant is still under consideration. No decision can be published.');
                   }
               }
               else if (count($offers) > 1)
               {
                   Yii::$app->session->setFlash('error', 'Applicant has multiple offers. A decision cannot be published.');
               }
          }

          return $this->redirect(Yii::$app->request->referrer);
      } 
      
      
      
      
    /**
     * Prepares and renders 'applicant_profile'
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 20/12/2015
     * Date Last Modified: 28/02/2016
     */
    public function actionApplicantProfile($applicantusername, $unrestricted = false)
    {
        $user = User::findOne(['username' =>$applicantusername]);
        $personid = $user->personid;
        $applicant= Applicant::findByPersonID($personid);
        $user = User::getUser($personid);

        $phone = Phone::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();

        $email = Email::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();

        $permanentaddress = Address::findAddress($personid, 1);
        $residentaladdress = Address::findAddress($personid, 2);
        $postaladdress = Address::findAddress($personid, 3);

        /************************* Relations ************************************/
        $old_beneficiary = false;       //old apply implementation
        $new_beneficiary = false;       //new apply implementation
        $spouse = false;
        $mother = false;
        $father = false;
        $nextofkin = false;
        $old_emergencycontact = false;  //old apply implementation
        $new_emergencycontact = false;  //new apply implementation
        $guardian = false;

        $old_beneficiary = Relation::getRelationRecord($personid, 6);
        $new_beneficiary = CompulsoryRelation::getRelationRecord($personid, 6);
        $old_emergencycontact = Relation::getRelationRecord($personid, 4);
        $new_emergencycontact = CompulsoryRelation::getRelationRecord($personid, 4);
        $spouse = Relation::getRelationRecord($personid, 7);
        $mother = Relation::getRelationRecord($personid, 1);
        $father = Relation::getRelationRecord($personid, 2);
        $nextofkin = Relation::getRelationRecord($personid, 3);
        $guardian = Relation::getRelationRecord($personid, 5);

        /************************ Medical Conditions *****************************/
        $medicalConditions = MedicalCondition::getMedicalConditions($personid);
        
        /************************ Additional Details *****************************/
        $genral_work_experience = GeneralWorkExperience::getGeneralWorkExperiences($personid);
        $references = Reference::getReferences($personid);
        $teaching = TeachingExperience::getTeachingExperiences($personid);
        $nursing = NurseWorkExperience::getNurseWorkExperience($personid);
        $nursing_certification = NursePriorCertification::getCertifications($personid);
        $nursinginfo = NursingAdditionalInfo::getNursingInfo($personid);
        $teachinginfo = TeachingAdditionalInfo::getTeachingInfo($personid);
        $criminalrecord =  CriminalRecord::getCriminalRecord($personid);
        
        if ($unrestricted)
            $info = Applicant::getApplicantInformation($personid, true);
        else
            $info = Applicant::getApplicantInformation($personid);
        
        $status_id = $info['status'];
        $status = ApplicationStatus::find()
                ->where(['applicationstatusid' => $status_id])
                ->one();
        $applicant_status = $status->name;

        /************************* Institutions **********************************/
        $preschools = PersonInstitution::getPersonInsitutionRecords($personid, 1);
        $preschoolNames = array();
        if ($preschools!=false)
        {
            foreach ($preschools as $preschool)
            {
                $name = NULL;
                $record = NULL;
                $record = Institution::find()
                        ->where(['institutionid' => $preschool->institutionid])
                        ->one();     
                $name = $record->name;
                array_push($preschoolNames, $name);          
            }
        }

        $primaryschools = PersonInstitution::getPersonInsitutionRecords($personid, 2);
        $primaryschoolNames = array();
        if ($primaryschools!=false)
        {
            foreach ($primaryschools as $primaryschool)
            {
                $name = NULL;
                $record = NULL;
                $record = Institution::find()
                        ->where(['institutionid' => $primaryschool->institutionid])
                        ->one();     
                $name = $record->name;
                array_push($primaryschoolNames, $name); 
            }
        }

        $secondaryschools = PersonInstitution::getPersonInsitutionRecords($personid, 3);
        $secondaryschoolNames = array();
        if ($secondaryschools!=false)
        {
            foreach ($secondaryschools as $secondaryschool)
            {
                $name = NULL;
                $record = NULL;
                $record = Institution::find()
                        ->where(['institutionid' => $secondaryschool->institutionid])
                        ->one();       
                $name = $record->name;
                array_push($secondaryschoolNames, $name); 
            }
        }

        $tertiaryschools = PersonInstitution::getPersonInsitutionRecords($personid, 4);
        $tertiaryschoolNames = array();
        if ($tertiaryschools!=false)
        {
            foreach ($tertiaryschools as $tertiaryschool)
            {
                $name = NULL;
                $record = NULL;
                $record = Institution::find()
                        ->where(['institutionid' => $tertiaryschool->institutionid])
                        ->one();  
                $name = $record->name;
                array_push($tertiaryschoolNames, $name); 
            }
        }

        /****************************** Qualifications ***************************/
        $qualifications = CsecQualification::getQualifications($personid);
        $qualificationDetails = array();

        if ($qualifications != false)
        {
            $keys = ['centrename', 'examinationbody', 'subject', 'proficiency', 'grade'];
            foreach ($qualifications as $qualification)
            {
                $values = array();
                $combined = array();
                $centre = CsecCentre::find()
                        ->where(['cseccentreid' => $qualification->cseccentreid])
                        ->one();
                array_push($values, $centre->name);
                $examinationbody = ExaminationBody::find()
                        ->where(['examinationbodyid' => $qualification->examinationbodyid])
                        ->one();
                array_push($values, $examinationbody->abbreviation);
                $subject = Subject::find()
                        ->where(['subjectid' => $qualification->subjectid])
                        ->one();
                array_push($values, $subject->name);
                $proficiency = ExaminationProficiencyType::find()
                        ->where(['examinationproficiencytypeid' => $qualification->examinationproficiencytypeid])
                        ->one();
                array_push($values, $proficiency->name);
                $grade = ExaminationGrade::find()
                        ->where(['examinationgradeid' => $qualification->examinationgradeid])
                        ->one();
                array_push($values, $grade->name);
                $combined = array_combine($keys,$values);
                array_push($qualificationDetails, $combined);
                $values = NULL;
                $combined = NULL;
            }
        }
        
        $post_qualification = PostSecondaryQualification::getPostSecondaryQualifications($personid);
        $external_qualification = ExternalQualification::getExternalQualifications($personid);

        /****************************** Applications ***************************/
        $applications = Application::getApplications($personid);
        $first = array();
        $firstDetails = array();
        $second = array();
        $secondDetails = array();
        $third = array();
        $thirdDetails = array();

        $db = Yii::$app->db;
        foreach($applications as $application)
        {
            $capeSubjects = NULL;
            $isCape = NULL;
            $division = NULL;
            $programme = NULL;
            $d = NULL;
            $p = NULL;
            if ($application->ordering == 1)
            {
                array_push($first, $application);
                $isCape = Application::isCape($application->academicofferingid);
                if ($isCape == true)
                {
                  $capeSubjects = ApplicationCapesubject::getRecords($application->applicationid);
                  array_push($first, $capeSubjects);
                }
                $d = Division::find()
                        ->where(['divisionid' => $application->divisionid])
                        ->one();
//                    $division = $d->name;
                $division = $d->abbreviation;
                array_push($firstDetails, $division);

                $p = $db->createCommand(
                    "SELECT academic_offering.academicofferingid, programme_catalog.name, programme_catalog.specialisation, qualification_type.abbreviation"
                    . " FROM  academic_offering "
                    . " JOIN programme_catalog"
                    . " ON programme_catalog.programmecatalogid = academic_offering.programmecatalogid"
                    . " JOIN qualification_type"
                    . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                    . " WHERE academic_offering.academicofferingid = " . $application->academicofferingid . " ;"
                    )
                    ->queryAll();

                $specialization = $p[0]["specialisation"];
                $qualification = $p[0]["abbreviation"];
                $programme = $p[0]["name"];
                $fullname = $qualification . " " . $programme . " " . $specialization;
                array_push($firstDetails, $fullname);

                $academic_year = $db->createCommand(
                    "SELECT academic_offering.academicofferingid AS 'academicofferingid',"
                        . " academic_year.title AS 'title'"
                        . " FROM  academic_offering"
                        . " JOIN academic_year"
                        . " ON academic_offering.academicyearid = academic_year.academicyearid"
                        . " WHERE academic_offering.academicofferingid = " . $application->academicofferingid . " ;"
                    )
                    ->queryOne();
                $year = $academic_year["title"];
                array_push($firstDetails, $year);

            }

            else if ($application->ordering == 2)
            {
                array_push($second, $application);
                $isCape = Application::isCapeApplication($application->academicofferingid);
                if ($isCape == true)
                {
                    $capeSubjects = ApplicationCapesubject::getRecords($application->applicationid);
                    array_push($second, $capeSubjects);
                }
                $d = Division::find()
                    ->where(['divisionid' => $application->divisionid])
                    ->one();
//                    $division = $d->name;
                $division = $d->abbreviation;
                array_push($secondDetails, $division);

                $p = $db->createCommand(
                    "SELECT academic_offering.academicofferingid, programme_catalog.name, programme_catalog.specialisation, qualification_type.abbreviation"
                    . " FROM  academic_offering "
                    . " JOIN programme_catalog"
                    . " ON programme_catalog.programmecatalogid = academic_offering.programmecatalogid"
                    . " JOIN qualification_type"
                    . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                    . " WHERE academic_offering.academicofferingid = " . $application->academicofferingid . " ;"
                    )
                    ->queryAll();

                $specialization = $p[0]["specialisation"];
                $qualification = $p[0]["abbreviation"];
                $programme = $p[0]["name"];
                $fullname = $qualification . " " . $programme . " " . $specialization;
                array_push($secondDetails, $fullname);

                $academic_year = $db->createCommand(
                    "SELECT academic_offering.academicofferingid AS 'academicofferingid',"
                        . " academic_year.title AS 'title'"
                        . " FROM  academic_offering"
                        . " JOIN academic_year"
                        . " ON academic_offering.academicyearid = academic_year.academicyearid"
                        . " WHERE academic_offering.academicofferingid = " . $application->academicofferingid . " ;"
                    )
                    ->queryOne();
                $year = $academic_year["title"];
                array_push($secondDetails, $year);
            }
            elseif ($application->ordering == 3)
            {
                array_push($third, $application);
                $isCape = Application::isCapeApplication($application->academicofferingid);
                if ($isCape == true)
                {
                    $capeSubjects = ApplicationCapesubject::getRecords($application->applicationid);
                    array_push($third, $capeSubjects);
                }
                $d = Division::find()
                    ->where(['divisionid' => $application->divisionid])
                    ->one();
//                    $division = $d->name;
                $division = $d->abbreviation;
                array_push($thirdDetails, $division);

                $p = $db->createCommand(
                    "SELECT academic_offering.academicofferingid, programme_catalog.name, programme_catalog.specialisation, qualification_type.abbreviation"
                    . " FROM  academic_offering "
                    . " JOIN programme_catalog"
                    . " ON programme_catalog.programmecatalogid = academic_offering.programmecatalogid"
                    . " JOIN qualification_type"
                    . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                    . " WHERE academic_offering.academicofferingid = " . $application->academicofferingid . " ;"
                    )
                    ->queryAll();

                $specialization = $p[0]["specialisation"];
                $qualification = $p[0]["abbreviation"];
                $programme = $p[0]["name"];
                $fullname = $qualification . " " . $programme . " " . $specialization;
                array_push($thirdDetails, $fullname);

                $academic_year = $db->createCommand(
                    "SELECT academic_offering.academicofferingid AS 'academicofferingid',"
                        . " academic_year.title AS 'title'"
                        . " FROM  academic_offering"
                        . " JOIN academic_year"
                        . " ON academic_offering.academicyearid = academic_year.academicyearid"
                        . " WHERE academic_offering.academicofferingid = " . $application->academicofferingid . " ;"
                    )
                    ->queryOne();
                $year = $academic_year["title"];
                array_push($thirdDetails, $year);

            }
        }

        /********************************* Offers ******************************/
        $offers = Offer::getOffers($personid);

        /*************************** Documents/Submitted ***********************/
        $document_details = array();
        $documents = DocumentSubmitted::findAll(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0]);
        
        $keys = array();
        array_push($keys, 'id');
        array_push($keys, 'intent');
        array_push($keys, 'name');
        array_push($keys, 'verifier');
        
        foreach($documents as $doc)
        {
            $values = array();
            $combined = array();
            
            $id = $doc->documentsubmittedid;
            $intent = DocumentIntent::find()
                    ->where(['documentintentid' => $doc->documentintentid])
                    ->one()
                    ->description;
            $name = DocumentType::find()
                    ->where(['documenttypeid' => $doc->documenttypeid])
                    ->one()
                    ->name;
            $verifier = Employee::getEmployeeName($doc->recepientid);
            array_push($values, $id);
            array_push($values, $intent);
            array_push($values, $name);
            array_push($values, $verifier);
            
            $combined = array_combine($keys, $values);
            array_push($document_details, $combined);
            $values = NULL;
            $combined = NULL;
        }

        /***********************************************************************/
        return $this->render('applicant_profile',[
            //models for profile tab
            'user' =>  $user,
            'applicant' => $applicant,
            'phone' => $phone,
            'email' => $email,
            'permanentaddress' => $permanentaddress,
            'residentaladdress' => $residentaladdress,
            'postaladdress' => $postaladdress,
            'old_beneficiary' => $old_beneficiary,
            'new_beneficiary' => $new_beneficiary,
            'mother' => $mother,
            'father' => $father,
            'nextofkin' => $nextofkin,
            'old_emergencycontact' => $old_emergencycontact,
            'new_emergencycontact' => $new_emergencycontact,
            'guardian' =>  $guardian,                   
            'spouse' => $spouse,

            //models for addtional information tab
            'medicalConditions' => $medicalConditions,
            'general_work_experience' => $genral_work_experience,
            'references' => $references,
            'teaching' => $teaching,
            'nursing' => $nursing,
            'nursing_certification' => $nursing_certification,
            'nursinginfo' => $nursinginfo,
            'teachinginfo' => $teachinginfo,
            'applicant_status' => $applicant_status,
            'criminalrecord' => $criminalrecord,

            //models for academic institutions tab
            'preschools' => $preschools,
            'preschoolNames' => $preschoolNames,
            'primaryschools' => $primaryschools,
            'primaryschoolNames' => $primaryschoolNames,
            'secondaryschools' => $secondaryschools,
            'secondaryschoolNames' => $secondaryschoolNames,
            'tertiaryschools' => $tertiaryschools,
            'tertiaryschoolNames' => $tertiaryschoolNames,

            //models for qualifications tab
            'qualifications' => $qualifications,
            'qualificationDetails' => $qualificationDetails,
            'post_qualification' => $post_qualification,
            'external_qualification' => $external_qualification,

            //models for appplications and offers tab
            'first' => $first,
            'firstDetails' =>$firstDetails,
            'second' => $second,
            'secondDetails' =>$secondDetails,
            'third' => $third,
            'thirdDetails' =>$thirdDetails,
            'offers' => $offers,
            'document_details' => $document_details,
        ]);
    }
    
    
    /**
     * Updates 'General' section of Applicant Profile
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 28/12/2015
     * Date Last Modified: 28/02/2016
     */
    public function actionEditGeneral($personid)
    {
        $applicant = Applicant::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();

        if ($post_data = Yii::$app->request->post())
        {
            $applicant_load_flag = false;
            $applicant_save_flag = false;

            $applicant_load_flag = $applicant->load($post_data); 
            if ($applicant_load_flag == true)
            {
                $applicant_save_flag = $applicant->save();
                if ($applicant_save_flag == true)
                {
                    return self::actionApplicantProfile($user->username);
                }
                else
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save applicant model. Please try again.');
            }
            else
            Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load applicant model. Please try again.');    
        }


        return $this->render('edit_general', [
            'user' => $user,
            'applicant' => $applicant
        ]);
    }
    
    
    /**
     * Updates 'Contact Details' section of Applicant Profile
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 28/12/2015
     * Date Last Modified: 28/02/2016
     */
    public function actionEditContactDetails($personid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $phone = Phone::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        $email = Email::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        
        if ($post_data = Yii::$app->request->post())
        {
            if ($phone == true && $email == true  && $user == true)
            {
                //load flags
                $phone_load_flag = false;
                $email_load_flag = false;

                //validation flags
                $phone_valid_flag = false;
                $email_valid_flag = false;
                
                //save flags
                $phone_save_flag = false;
                $email_save_flag = false;
                
                $phone_load_flag = $phone->load($post_data);
                $email_load_flag = $email->load($post_data);
                
                if ($phone_load_flag == true && $email_load_flag == true)
                {
                    $phone_valid_flag = $phone->validate();
                    $email_valid_flag = $email->validate();

                    if ($phone_valid_flag == true && $email_valid_flag == true)
                    {
                        $transaction = \Yii::$app->db->beginTransaction();
                        try 
                        {
                            $phone_save_flag = $phone->save();
                            $email_save_flag = $email->save();

                            if ($phone_save_flag == true && $email_save_flag == true)
                            {
                                $transaction->commit();
                                return self::actionApplicantProfile($user->username);
                            }
                            else
                            {
                                $transaction->rollBack();
                                 Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update information. Please try again.');
                            }

                        }catch (Exception $e) 
                        {
                            $transaction->rollBack();
                        }
                    }
                    else
                    {
                         Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update information. Please try again.');
                    }                       
                }
                else
                {
                     Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update information. Please try again.');
                }    
            }
            else
            {
                 Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update information. Please try again.');
            }
        }

        return $this->render('edit_contact_details', [
            'user' => $user,
            'phone' => $phone,
            'email' => $email,
        ]);
    }
    
    
    /**
     * Updates 'Addresses' section of Applicant Profile
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 28/12/2015
     * Date Last Modified: 28/02/2016
     */
    public function actionEditAddresses($personid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $permanentaddress = Address::findAddress($personid, 1);
        $residentaladdress = Address::findAddress($personid, 2);
        $postaladdress = Address::findAddress($personid, 3);
        $addresses = [$permanentaddress, $residentaladdress, $postaladdress];

        if ($post_data = Yii::$app->request->post())
        {
            if ($permanentaddress == true && $residentaladdress == true  && $postaladdress == true)
            {
                $addresses_load_flag = false;       //load flags                                      
                $addresses_valid_flag = false;      //validation flags                                   
                $addresses_save_flag = false;       //save flags

                $addresses_load_flag = Model::loadMultiple($addresses, $post_data);

                if ($addresses_load_flag == true)
                {
                    $addresses_valid_flag = Model::validateMultiple($addresses);

                    if ($addresses_valid_flag == true)
                    {
                        $transaction = \Yii::$app->db->beginTransaction();
                        try 
                        {
                            foreach ($addresses as $address)
                            {
                                $addresses_save_flag = $address->save();
                                if ($addresses_save_flag == false)          //if Address model save operation failed 
                                {
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update information. Please try again.');
                                    return $this->render('edit_addresses', [
                                                'addresses' => $addresses,
                                    ]);                                       
                                }
                            }
                            if ($addresses_save_flag == true)
                            {
                                $transaction->commit();
                                return self::actionApplicantProfile($user->username);
                            }                               
                        }catch (Exception $e) 
                        {
                            $transaction->rollBack();
                        }
                    }
                    else
                    {
                         Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update information. Please try again.');
                    }                       
                }
                else
                {
                     Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update information. Please try again.');
                }    
            }
            else
            {
                 Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update information. Please try again.');
            }
        }

        return $this->render('edit_addresses', [
            'user' => $user,
            'addresses' => $addresses,
        ]);        
    }
    
    
    /**
     * Updates an optional relative
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 28/12/2015
     * Date Last Modified: 28/02/2016
     */
    public function actionEditOptionalRelative($personid, $recordid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $relative = Relation::find()
                    ->where(['relationid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        if ($relative == false)
        {
            Yii::$app->getSession()->setFlash('error', 'Error occured when trying to locate record. Please try again.');
            return self::actionApplicantProfile($user->username);
        }

        $relative_type = RelationType::find()
                    ->where(['relationtypeid' => $relative->relationtypeid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        $relation_name = ucwords($relative_type->name);

        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $relative->load($post_data);
            if($load_flag == true)
            {
                $validation_flag = $relative->validate();
                if($validation_flag == true)
                {
                    $save_flag = $relative->save();
                    if($save_flag == true)
                    {
                        return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load record. Please try again.');              
        }

        return $this->render('edit_optional_relative', [
            'user' => $user,
            'personid' => $personid,
            'relative' => $relative,
            'relation_name' => $relation_name,
        ]); 
    }
    
    
    /**
     * Deletes an optional relative 
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 03/01/2016
     * Date Last Modified: 28/02/2016
     */
    public function actionDeleteOptionalRelative($personid, $recordid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $relative = Relation::find()
                    ->where(['relationid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        if ($relative == true)
        {
            $save_flag = false;
            $relative->isdeleted = 1;
            $relative->isactive = 0;
            $save_flag = $relative->save();
            if($save_flag == true)
            {
                return self::actionApplicantProfile($user->username);
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Error occured deleting record. Please try again.');             
        }
        else
            Yii::$app->getSession()->setFlash('error', 'Error occured locating record. Please try again.');
        
        
        return self::actionApplicantProfile($user->username);
    }
    
    
    /**
     * Updates a compulsory relative 
     * 
     * @param type $personid
     * @param type $studentregistrationid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 03/01/2016
     * Date Last Modified: 28/02/2016
     */
    public function actionEditCompulsoryRelative($personid, $recordid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $relative = CompulsoryRelation::find()
                    ->where(['compulsoryrelationid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        if ($relative == false)
        {
            Yii::$app->getSession()->setFlash('error', 'Error occured when trying to locate record. Please try again.');
            return self::actionApplicantProfile($user->username);
        }

        $relative_type = RelationType::find()
                    ->where(['relationtypeid' => $relative->relationtypeid,   'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        $relation_name = ucwords($relative_type->name);

        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $relative->load($post_data);
            if($load_flag == true)
            {
                $validation_flag = $relative->validate();
                if($validation_flag == true)
                {
                    $save_flag = $relative->save();
                    if($save_flag == true)
                    {
                        return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load record. Please try again.');              
        }

        return $this->render('edit_compulsory_relative', [
            'user' => $user,
            'personid' => $personid,
            'relative' => $relative,
            'relation_name' => $relation_name,
        ]); 
    }
    
    
    /**
     * Creates an optional relation 
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 03/01/2016
     * Date Last Modified: 03/01/2016
     */
    public function actionAddOptionalRelative($personid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $relative = new Relation();
        $relative->personid = $personid;

        $beneficiary = false;       
        $spouse = false;
        $mother = false;
        $father = false;
        $nextofkin = false;
        $emergencycontact = false;
        $guardian = false;

        $mother = Relation::getRelationRecord($personid, 1);
        $father = Relation::getRelationRecord($personid, 2);
        $nextofkin = Relation::getRelationRecord($personid, 3);
        $emergencycontact = Relation::getRelationRecord($personid, 4);
        $guardian = Relation::getRelationRecord($personid, 5);
        $beneficiary = Relation::getRelationRecord($personid, 6);
        $spouse = Relation::getRelationRecord($personid, 7);

        //customizes the realtion arrays
        $optional_relations = array();  
        $keys = array();
        $values = array();
        array_push($keys, "");
        array_push($values, "Select Relation Type");

        if ($mother == false)
        {
            array_push($keys, 1);
            array_push($values, "Mother");
        } 
        if ($father == false)
        {
            array_push($keys, 2);
            array_push($values, "Father");
        }

        if ($nextofkin == false)
        {
            array_push($keys, 3);
            array_push($values, "Next Of Kin");
        }

        if ($emergencycontact == false)
        {
            array_push($keys, 4);
            array_push($values, "Emergency Contact");
        }

        if ($guardian == false)
        {
            array_push($keys, 5);
            array_push($values, "Guardian");
        }

        if ($beneficiary == false)
        {
            array_push($keys, 6);
            array_push($values, "Beneficiary");
        }

        if ($spouse == false)
        {
            array_push($keys, 7);
            array_push($values, "Spouse");
        }

        $optional_relations = array_combine($keys, $values);

        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $relative->load($post_data);
            if($load_flag == true)
            {
                $validation_flag = $relative->validate();

                if($validation_flag == true)
                {
                    $save_flag = $relative->save();
                    if($save_flag == true)
                    {
                        return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load record. Please try again.');              
        }

        return $this->render('add_optional_relative', [
            'user' => $user,
            'personid' => $personid,
            'relative' => $relative,
            'optional_relations' => $optional_relations,
        ]); 
    }
    
    
    /**
     * Updates 'Extraccurricular Activities' section of Applicant Profile
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 03/04/2016
     * Date Last Modified: 03/04/2016
     */
    public function actionEditExtracurricular($personid)
    {
        $applicant = Applicant::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();

        if ($post_data = Yii::$app->request->post())
        {
            $applicant_load_flag = false;
            $applicant_save_flag = false;

            $applicant_load_flag = $applicant->load($post_data); 
            if ($applicant_load_flag == true)
            {
                $applicant_save_flag = $applicant->save();
                if ($applicant_save_flag == true)
                {
                    return self::actionApplicantProfile($user->username);
                }
                else
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save applicant model. Please try again.');
            }
            else
            Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load applicant model. Please try again.');    
        }


        return $this->render('edit_extracurricular', [
            'user' => $user,
            'applicant' => $applicant
        ]);
    }
    
    
    /**
     * Deletes a medical condition
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 03/01/2016
     * Date Last Modified: 03/01/2016
     */
    public function actionDeleteMedicalCondition($personid, $recordid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $condition = MedicalCondition::find()
                    ->where(['medicalconditionid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        if ($condition == true)
        {
            $save_flag = false;
            $condition->isactive = 0;
            $condition->isdeleted = 1;
            $save_flag = $condition->save();
            if($save_flag == true)
            {
                return self::actionApplicantProfile($user->username);
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Error occured deleting medical condition record. Please try again.');      
        }

        else
            Yii::$app->getSession()->setFlash('error', 'Error occured retrieving medical condition record. Please try again.');
        
        return self::actionApplicantProfile($user->username);
    }
    
    
    /**
     * Updates a medical condition
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 03/01/2016
     * Date Last Modified: 28/02/2016
     */
    public function actionEditMedicalCondition($personid, $recordid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $condition = MedicalCondition::find()
                    ->where(['medicalconditionid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        if ($condition == false)
        {
            Yii::$app->getSession()->setFlash('error', 'Error occured when trying to retrieve medical condition record. Please try again.');
            return self::actionApplicantProfile($user->username);
        }


        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $condition->load($post_data);
            if($load_flag == true)
            {
                $validation_flag = $condition->validate();
                if($validation_flag == true)
                {
                    $save_flag = $condition->save();
                    if($save_flag == true)
                    {
                        return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update medical condition record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate  medical condition record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load medical condition record. Please try again.');              
        }

        return $this->render('edit_medical condition', [
            'user' => $user,
            'personid' => $personid, 
            'condition' => $condition,
        ]); 
    }
    
    
    /**
     * Creates a medical condition record 
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 03/01/2016
     * Date Last Modified: 28/02/2016
     */
    public function actionAddMedicalCondition($personid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $condition = new MedicalCondition();
        $condition->personid = $personid;

        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $condition->load($post_data);
            if($load_flag == true)
            {
                $validation_flag = $condition->validate();

                if($validation_flag == true)
                {
                    $save_flag = $condition->save();
                    if($save_flag == true)
                    {
                        return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save medical condition record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate medical condition  record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load medical condition  record. Please try again.');              
        }

        return $this->render('add_medical_condition', [
            'user' => $user,
            'personid' => $personid,
            'condition' => $condition,
        ]); 
    }
    
    
    /**
     * Creates a qualification record 
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 04/01/2016
     * Date Last Modified: 28/02/2016
     */
    public function actionAddQualification($personid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $qualification = new CsecQualification();
        $qualification->personid = $personid;

        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $qualification->load($post_data);
            if($load_flag == true)
            {
                $validation_flag = $qualification->validate();

                if($validation_flag == true)
                {
                    $save_flag = $qualification->save();
                    if($save_flag == true)
                    {
                        return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save qualification record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate qualification  record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load qualification  record. Please try again.');              
        }

        return $this->render('add_csec_qualificiation', [
            'user' => $user,
            'personid' => $personid,
            'qualification' => $qualification,
        ]); 
    }
    
    
    /**
     * Creates a qualification record from the verification screen
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 10/03/2016
     * Date Last Modified: 28/02/2016
     */
    public function actionAddQualificationFromVerify($applicantusername, $cseccentreid, $centrename, $type)
    {
        $user = User::find()
                ->where(['username' => $applicantusername, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        
        $qualification = new CsecQualification();
        $qualification->personid = $user->personid;

        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $qualification->load($post_data);
            if($load_flag == true)
            {
                $validation_flag = $qualification->validate();

                if($validation_flag == true)
                {
                    $save_flag = $qualification->save();
                    if($save_flag == true)
                    {
                        //redirect
                        if (strcasecmp($type, "pending")==0)
                        {
                            return $this->redirect(['verify-applicants/view-pending', 
                                                    'cseccentreid' => $cseccentreid, 
                                                    'centrename' => $centrename
                                                ]);
                        }
                        elseif (strcasecmp($type, "queried")==0)
                        {
                            return $this->redirect(['verify-applicants/view-queried', 
                                                    'cseccentreid' => $cseccentreid, 
                                                    'centrename' => $centrename
                                                ]);
                        }
                        elseif (strcasecmp($type, "all")==0)
                        {
                            return $this->redirect(['verify-applicants/view-all', 
                                                    'cseccentreid' => $cseccentreid, 
                                                    'centrename' => $centrename
                                                ]);
                        }
                        elseif (strcasecmp($type, "verified")==0)
                        {
                            return $this->redirect(['verify-applicants/view-verified', 
                                                    'cseccentreid' => $cseccentreid, 
                                                    'centrename' => $centrename
                                                ]);
                        }
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save qualification record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate qualification  record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load qualification  record. Please try again.');              
        }

        return $this->render('add_csec_qualificiation_from_verify', [
            'user' => $user,
            'applicantusername' => $applicantusername,
            'qualification' => $qualification,
        ]); 
    }
    
    
    
    /**
     * Updates 'Technical Qualifications' section of Applicant Profile
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 03/04/2016
     * Date Last Modified: 03/04/2016
     */
    public function actionEditTechnicalQualifications($personid)
    {
        $applicant = Applicant::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();

        if ($post_data = Yii::$app->request->post())
        {
            $applicant_load_flag = false;
            $applicant_save_flag = false;

            $applicant_load_flag = $applicant->load($post_data); 
            if ($applicant_load_flag == true)
            {
                $applicant_save_flag = $applicant->save();
                if ($applicant_save_flag == true)
                {
                    return self::actionApplicantProfile($user->username);
                }
                else
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save applicant model. Please try again.');
            }
            else
            Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load applicant model. Please try again.');    
        }


        return $this->render('edit_technical_qualifications', [
            'user' => $user,
            'applicant' => $applicant
        ]);
    }
    
    
    
    /**
     * Adds/Edits/Deletes "PostSecondaryQualification' record
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 03/04/2016
     * Date Last Modified: 03/04/2016
     */
    public function actionPostSecondaryQualification($personid, $action)
    {
        $user = User::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        
        if ($action == "delete")
        {
            $qualification = PostSecondaryQualification::getPostSecondaryQualifications($personid);
            if ($qualification == true)
            {
                $save_flag = false;
                $qualification->isdeleted = 1;
                $qualification->isactive = 0;
                $save_flag = $qualification->save();
                if($save_flag == true)
                {
                    return self::actionApplicantProfile($user->username);
                }
            }
            else
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when deleting Post Secondarty Degree. Please try again.');
                return self::actionApplicantProfile($user->username);
            }
        }
        
        elseif ($action == "add")
            $qualification = new PostSecondaryQualification();
        elseif ($action == "edit")
            $qualification = PostSecondaryQualification::getPostSecondaryQualifications($personid);
        

        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $qualification->load($post_data);
            if($load_flag == true)
            {
                $qualification->personid = $user->personid;
                $validation_flag = $qualification->validate();

                if($validation_flag == true)
                {
                    $save_flag = $qualification->save();
                    if($save_flag == true)
                    {
                      return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save qualification record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate qualification  record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load qualification  record. Please try again.');              
        }

        return $this->render('post_secondary_qualificiation', [
            'user' => $user,
            'qualification' => $qualification,
        ]); 
    }
    
    
    /**
     * Adds/Edits/Deletes "ExternalQualification' record
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 03/04/2016
     * Date Last Modified: 03/04/2016
     */
    public function actionExternalQualification($personid, $action)
    {
        $user = User::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        
        if ($action == "delete")
        {
            $qualification = ExternalQualification::getExternalQualifications($personid);
            if ($qualification == true)
            {
                $save_flag = false;
                $qualification->isdeleted = 1;
                $qualification->isactive = 0;
                $save_flag = $qualification->save();
                if($save_flag == true)
                {
                    return self::actionApplicantProfile($user->username);
                }
            }
            else
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when deleting External Qualification. Please try again.');
                return self::actionApplicantProfile($user->username);
            }
        }
        
        elseif ($action == "add")
            $qualification = new ExternalQualification();
        elseif ($action == "edit")
            $qualification = ExternalQualification::getExternalQualifications($personid);
        

        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $qualification->load($post_data);
            if($load_flag == true)
            {
                $qualification->personid = $user->personid;
                $validation_flag = $qualification->validate();

                if($validation_flag == true)
                {
                    $save_flag = $qualification->save();
                    if($save_flag == true)
                    {
                      return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save qualification record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate qualification  record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load qualification  record. Please try again.');              
        }

        return $this->render('external_qualification', [
            'user' => $user,
            'qualification' => $qualification,
        ]); 
    }
    


    /**
     * Deletes a qualification
     * 
     * @param type $personid
     * @param type $recordid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 04/01/2016
     * Date Last Modified: 28/02/2016
     */
    public function actionDeleteQualification($personid, $recordid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $qualification = CsecQualification::find()
                    ->where(['csecqualificationid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        if ($qualification == true)
        {
            $save_flag = false;
            $qualification->isdeleted = 1;
            $qualification->isactive = 0;
            $save_flag = $qualification->save();
            if($save_flag == true)
            {
                return self::actionApplicantProfile($user->username);
            }
            else
            Yii::$app->getSession()->setFlash('error', 'Error occured deleting qualification record. Please try again.');               
        }            
        else
            Yii::$app->getSession()->setFlash('error', 'Error occured retrieving qualification record. Please try again.');
            
        
        return self::actionApplicantProfile($user->username);
    }


    /**
     * Updates a qualification record
     * 
     * @param type $personid
     * @param type $recordid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 04/01/2016
     * Date Last Modified: 28/02/2016
     */
    public function actionEditQualification($personid, $recordid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $qualification = CsecQualification::find()
                    ->where(['csecqualificationid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();

        if ($qualification == false)
        {          
            Yii::$app->getSession()->setFlash('error', 'Error occured when trying to retrieve qualification record. Please try again.');
            return self::actionApplicantProfile($user->username);
        }

        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $qualification->load($post_data);
            if($load_flag == true)
            {
                $validation_flag = $qualification->validate();

                if($validation_flag == true)
                {
                    $save_flag = $qualification->save();
                    if($save_flag == true)
                    {
                        return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save qualification record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate qualification  record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load qualification  record. Please try again.');              
        }

        return $this->render('edit_csec_qualificiation', [
            'user' => $user,
            'personid' => $personid, 
            'qualification' => $qualification,
        ]); 
    }
    
    
    /**
     * Deletes personinstitutiton record
     * 
     * @param type $personid
     * @param type $recordid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 04/01/2016
     * Date Last Modified: 28/02/2016
     */
    public function actionDeleteSchool($personid, $recordid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $school = PersonInstitution::find()
                ->where(['personinstitutionid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        if ($school == true)
        {
            $save_flag = false;
            $school->isactive = 0;
            $school->isdeleted = 1;
            $save_flag = $school->save();
            if($save_flag == true)
            {
                return self::actionApplicantProfile($user->username);
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Error occured deleting school record. Please try again.');              
        }            
        else
            Yii::$app->getSession()->setFlash('error', 'Error occured retrieving school record. Please try again.');
            
        
        return self::actionApplicantProfile($user->username);
    }


    /**
     * Updates personinstitutiton record
     * 
     * @param type $personid
     * @param type $recordid
     * @param type $levelid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 05/01/2016
     * Date Last Modified: 28/02/2016
     */
    public function actionEditSchool($personid, $recordid, $levelid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $school = PersonInstitution::find()
                ->where(['personinstitutionid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();

        if ($school == false)
        {          
            Yii::$app->getSession()->setFlash('error', 'Error occured when trying to retrieve institution record. Please try again.');
            return self::actionApplicantProfile($user->username);
        }

        $institution = Institution::find()
                    ->where(['institutionid' => $school->institutionid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        $school_name = $institution->name;

        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $school->load($post_data);
            if($load_flag == true)
            {
                $validation_flag = $school->validate();

                if($validation_flag == true)
                {
                    $save_flag = $school->save();
                    if($save_flag == true)
                    {
                        return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save institution record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate institution  record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load institution  record. Please try again.');              
        }

        return $this->render('edit_school', [
            'user' => $user,
            'personid' => $personid,
            'school' => $school,
            'levelid' => $levelid,
            'school_name' => $school_name,
        ]);
    }


    /**
     * Adds new personinstitutiton record
     * 
     * @param type $personid
     * @param type $levelid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 05/01/2016
     * Date Last Modified: 28/02/2016
     */
    public function actionAddSchool($personid, $levelid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $school = new PersonInstitution();
        $school->personid = $personid;

        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $school->load($post_data);
            if($load_flag == true)
            {
                $validation_flag = $school->validate();

                if($validation_flag == true)
                {
                    $save_flag = $school->save();
                    if($save_flag == true)
                    {
                        return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save institution record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate institution  record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load institution  record. Please try again.');              
        }

        return $this->render('add_school', [
            'user' => $user,
            'personid' => $personid,
            'school' => $school,
            'levelid' => $levelid,
        ]);
    }
    
    
    /**
     * Creates or Updates 'general_work_experience' record
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 09/03/2016
     * Date Last Modified: 09/03/2016
     */
    public function actionGeneralWorkExperience($personid, $recordid = Null)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $experience = Null;
        $action = Null;
                
        if ($recordid == Null)
        {
            $experience = new GeneralWorkExperience();
            $action = "create";
        }
        else
        {
            $experience = GeneralWorkExperience::find()
                        ->where(['generalworkexperienceid' => $recordid])
                        ->one();
            $action = "update";
        }
        
        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $experience->load($post_data);
            if($load_flag == true)
            {
                $experience->personid = $personid;
                $validation_flag = $experience->validate();

                if($validation_flag == true)
                {
                    $save_flag = $experience->save();
                    if($save_flag == true)
                    {
                        return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load record. Please try again.');              
        }
       
        
        return $this->render('general_work_experience', [
            'user' => $user,
            'personid' => $personid,
            'experience' => $experience,
            'action' => $action,
        ]);
    }
    
    /**
     * Deletes 'GeneralWorkExperience' record
     * 
     * @param type $personid
     * @param type $recordid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 07/03/2016
     * Date Last Modified: 07/03/2016
     */
    public function actionDeleteGeneralWorkExperience($personid, $recordid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $experience = GeneralWorkExperience::find()
                        ->where(['generalworkexperienceid' => $recordid])
                        ->one();
        if ($experience == true)
        {
            $save_flag = false;
            $experience->isactive = 0;
            $experience->isdeleted = 1;
            $save_flag = $experience->save();
            if($save_flag == true)
            {
                return self::actionApplicantProfile($user->username);
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Error occured deleting record. Please try again.');              
        }            
        else
            Yii::$app->getSession()->setFlash('error', 'Error occured retrieving record. Please try again.');
            
        
        return self::actionApplicantProfile($user->username);
    }
    
    
    
    /**
     * Updates 'Reference' record
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 09/03/2016
     * Date Last Modified: 09/03/2016
     */
    public function actionEditReference($personid, $recordid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $reference = Reference::find()
                    ->where(['referenceid' => $recordid])
                    ->one();
        
        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $reference->load($post_data);
            if($load_flag == true)
            {
                $validation_flag = $reference->validate();

                if($validation_flag == true)
                {
                    $save_flag = $reference->save();
                    if($save_flag == true)
                    {
                        return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load record. Please try again.');              
        }
       
        
        return $this->render('edit_reference', [
            'user' => $user,
            'personid' => $personid,
            'reference' => $reference,
        ]);
    }
    
    
    /**
     * Creates of Updates 'NurseWorkExperience' record
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 09/03/2016
     * Date Last Modified: 09/03/2016
     */
    public function actionNurseWorkExperience($personid, $recordid = Null)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        if ($recordid == Null)
        {
            $nurseExperience = new NurseWorkExperience();
            $action = "create";
        }
        else
        {
            $nurseExperience = NurseWorkExperience::find()
                    ->where(['nurseworkexperienceid' => $recordid])
                    ->one();
            $action = "update";
        }
        
        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $nurseExperience->load($post_data);
            if($load_flag == true)
            {
                $validation_flag = $nurseExperience->validate();

                if($validation_flag == true)
                {
                    $nurseExperience->personid = $personid;
                    $save_flag = $nurseExperience->save();
                    if($save_flag == true)
                    {
                        return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load record. Please try again.');              
        }
       
        
        return $this->render('edit_nurse_work_experience', [
            'user' => $user,
            'personid' => $personid,
            'nurseExperience' => $nurseExperience,
            'action' => $action,
        ]);
    }
    
    
    /**
     * Deletes 'NurseWorkExperience' record
     * 
     * @param type $personid
     * @param type $recordid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 07/03/2016
     * Date Last Modified: 07/03/2016
     */
    public function actionDeleteNurseWorkExperience($personid, $recordid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $experience = NurseWorkExperience::find()
                        ->where(['nurseworkexperienceid' => $recordid])
                        ->one();
        if ($experience == true)
        {
            $save_flag = false;
            $experience->isactive = 0;
            $experience->isdeleted = 1;
            $save_flag = $experience->save();
            if($save_flag == true)
            {
                return self::actionApplicantProfile($user->username);
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Error occured deleting record. Please try again.');              
        }            
        else
            Yii::$app->getSession()->setFlash('error', 'Error occured retrieving record. Please try again.');
            
        
        return self::actionApplicantProfile($user->username);
    }
    
    
    /**
     * Creates or Updates 'NursePriorCertification' record
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 09/03/2016
     * Date Last Modified: 09/03/2016
     */
    public function actionNurseCertification($personid, $recordid = Null)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $experience = Null;
        $action = Null;
                
        if ($recordid == Null)
        {
            $experience = new NursePriorCertification();
            $action = "create";
        }
        else
        {
            $experience = NursePriorCertification::find()
                        ->where(['nursepriorcertificationid' => $recordid])
                        ->one();
            $action = "update";
        }
        
        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $experience->load($post_data);
            if($load_flag == true)
            {
                $experience->personid = $personid;
                $validation_flag = $experience->validate();

                if($validation_flag == true)
                {
                    $save_flag = $experience->save();
                    if($save_flag == true)
                    {
                        return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load record. Please try again.');              
        }
       
        return $this->render('nurse_certification', [
            'user' => $user,
            'personid' => $personid,
            'experience' => $experience,
            'action' => $action,
        ]);
    }
    
    
    /**
     * Deletes 'NursePriorCertification' record
     * 
     * @param type $personid
     * @param type $recordid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 07/03/2016
     * Date Last Modified: 07/03/2016
     */
    public function actionDeleteNurseCertification($personid, $recordid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $experience = NursePriorCertification::find()
                        ->where(['nursepriorcertificationid' => $recordid])
                        ->one();
        if ($experience == true)
        {
            $save_flag = false;
            $experience->isactive = 0;
            $experience->isdeleted = 1;
            $save_flag = $experience->save();
            if($save_flag == true)
            {
                return self::actionApplicantProfile($user->username);
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Error occured deleting record. Please try again.');              
        }            
        else
            Yii::$app->getSession()->setFlash('error', 'Error occured retrieving record. Please try again.');
            
        
        return self::actionApplicantProfile($user->username);
    }
    
    
    /**
     * Updates "NursingAdditionalInfo' record
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 10/03/2016
     * Date Last Modified: 10/03/2016
     */
    public function actionUpdateNursingInformation($personid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $nursinginfo = NursingAdditionalInfo::getNursingInfo($personid);
        
        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $nursinginfo->load($post_data);
            if($load_flag == true)
            {
                $validation_flag = $nursinginfo->validate();

                if($validation_flag == true)
                {
                    $save_flag = $nursinginfo->save();
                    if($save_flag == true)
                    {
                        return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save Additional Nursing Information record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate Additional Nursing Information record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load Additional Nursing Information record. Please try again.');              
        }
        
        return self::actionApplicantProfile($user->username);
    }
    
    
    /**
     * Creates or Updates 'TeacherExperience' record
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 09/03/2016
     * Date Last Modified: 09/03/2016
     */
    public function actionTeacherExperience($personid, $recordid = Null)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $experience = Null;
        $action = Null;
                
        if ($recordid == Null)
        {
            $experience = new TeachingExperience();
            $action = "create";
        }
        else
        {
            $experience = TeachingExperience::find()
                        ->where(['teachingexperienceid' => $recordid])
                        ->one();
            $action = "update";
        }
        
        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $experience->load($post_data);
            if($load_flag == true)
            {
                $experience->personid = $personid;
                $validation_flag = $experience->validate();

                if($validation_flag == true)
                {
                    $save_flag = $experience->save();
                    if($save_flag == true)
                    {
                        return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load record. Please try again.');              
        }
       
        
        return $this->render('teacher_experience', [
            'user' => $user,
            'personid' => $personid,
            'experience' => $experience,
            'action' => $action,
        ]);
    }
    
    
    /**
     * Deletes 'TeachingExperience' record
     * 
     * @param type $personid
     * @param type $recordid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 10/03/2016
     * Date Last Modified: 10/03/2016
     */
    public function actionDeleteTeacherExperience($personid, $recordid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $experience = TeachingExperience::find()
                        ->where(['teachingexperienceid' => $recordid])
                        ->one();
        if ($experience == true)
        {
            $save_flag = false;
            $experience->isactive = 0;
            $experience->isdeleted = 1;
            $save_flag = $experience->save();
            if($save_flag == true)
            {
                return self::actionApplicantProfile($user->username);
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Error occured deleting record. Please try again.');              
        }            
        else
            Yii::$app->getSession()->setFlash('error', 'Error occured retrieving record. Please try again.');
            
        
        return self::actionApplicantProfile($user->username);
    }
    
    
    /**
     * Updates "TeachingAdditionalInfo' record
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 10/03/2016
     * Date Last Modified: 10/03/2016
     */
    public function actionUpdateTeachingInformation($personid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $teachinginfo = TeachingAdditionalInfo::getTeachingInfo($personid);
        
        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $teachinginfo->load($post_data);
            if($load_flag == true)
            {
                $validation_flag = $teachinginfo->validate();

                if($validation_flag == true)
                {
                    $save_flag = $teachinginfo->save();
                    if($save_flag == true)
                    {
                        return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save Additional Nursing Information record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate Additional Nursing Information record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load Additional Nursing Information record. Please try again.');              
        }
        
        return self::actionApplicantProfile($user->username);
    }
    
    
    /**
     * Creates of Updates 'CriminalRecord' record
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 15/03/2016
     * Date Last Modified: 15/03/2016
     */
    public function actionCriminalRecord($personid, $recordid = Null)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        if ($recordid == Null)
        {
            $criminalrecord = new CriminalRecord();
            $action = "create";
        }
        else
        {
            $criminalrecord = CriminalRecord::find()
                    ->where(['criminalrecordid' => $recordid])
                    ->one();
            $action = "update";
        }
        
        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $validation_flag = false;
            $save_flag = false;

            $load_flag = $criminalrecord->load($post_data);
            if($load_flag == true)
            {
                $validation_flag = $criminalrecord->validate();

                if($validation_flag == true)
                {
                    $criminalrecord->personid = $personid;
                    $save_flag = $criminalrecord->save();
                    if($save_flag == true)
                    {
                        return self::actionApplicantProfile($user->username);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate record. Please try again.');
            }
            else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load record. Please try again.');              
        }
       
        
        return $this->render('edit_criminal_record', [
            'user' => $user,
            'personid' => $personid,
            'criminalrecord' => $criminalrecord,
            'action' => $action,
        ]);
    }
    
    
    /**
     * Deletes 'CriminalRecord' record
     * 
     * @param type $personid
     * @param type $recordid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 15/03/2016
     * Date Last Modified: 15/03/2016
     */
    public function actionDeleteCriminalRecord($personid, $recordid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        $criminalrecord = CriminalRecord::find()
                        ->where(['criminalrecordid' => $recordid])
                        ->one();
        if ($criminalrecord == true)
        {
            $save_flag = false;
            $criminalrecord->isactive = 0;
            $criminalrecord->isdeleted = 1;
            $save_flag = $experience->save();
            if($save_flag == true)
            {
                return self::actionApplicantProfile($user->username);
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Error occured deleting record. Please try again.');              
        }            
        else
            Yii::$app->getSession()->setFlash('error', 'Error occured retrieving record. Please try again.');
            
        
        return self::actionApplicantProfile($user->username);
    }
    
    
    /**
     * Updates applicant application/registration documents
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 22/05/2016
     * Date Last Modified: 22/05/2016
     */
    public function actionUpdateDocuments($personid)
    {
        $user = User::find()
                ->where(['personid' => $personid])
                ->one();
        
        //Get documents already submitted
        $selections = array();
        foreach (DocumentSubmitted::findAll(['personid' => $personid, 'isactive' =>1, 'isdeleted' => 0]) as $doc)
        {
            array_push($selections, $doc->documenttypeid);
        }
        
        
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            $transaction = \Yii::$app->db->beginTransaction();
            try 
            {
                //Update document submission
                $submitted = $request->post('documents');
                $docs = DocumentSubmitted::findAll(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0]);
                $docs_arr = array();
                
                /* 
                 * If applicant has documented that were record previously, there new status is checked
                 * if status is now unchecked, then they are deleted
                 */
                if ($docs)
                {
                    //creates collection
                    foreach ($docs as $doc)
                    { 
                        $docs_arr[] = $doc->documenttypeid; 
                    }

                    foreach ($docs as $doc)
                    {
                        if (!in_array($doc->documenttypeid, $submitted))
                        { 
                            //Document has been unchecked
                            $doc->isactive = 0;
                            $doc->isdeleted = 1;
                            $document_save_flag = $doc->save();
                            if ($document_save_flag == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('error', 'Error deleting document record.');
                                break;
                            }
                        }
                    }  
                }

                if($submitted)
                {
                    /**
                     * records new documents
                     */
                    foreach ($submitted as $sub)
                    {
                        if (!in_array($sub, $docs_arr))
                        { 
                           $doc = new DocumentSubmitted();
                           $doc->documenttypeid = $sub;
                           $doc->personid = $personid;
                           $doc->recepientid = Yii::$app->user->getId();
                           $doc->documentintentid = 1;
                           $document_save_flag = $doc->save(); 
                           if ($document_save_flag == false)
                           {
                               $transaction->rollBack();
                               Yii::$app->session->setFlash('error', 'Document could not be added');
                               break;
                           }
                        }
                    }
                    $transaction->commit();
                    return self::actionApplicantProfile($user->username);
                }
            }catch (Exception $e) 
            {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Error occured processing your request');
            }
        }

        
        return $this->render('update_documents', [
            'user' => $user,
            'selections' => $selections,
        ]);
    }
    
    
    
    

}
