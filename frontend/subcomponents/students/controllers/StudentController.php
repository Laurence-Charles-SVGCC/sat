<?php

namespace app\subcomponents\students\controllers;

use yii\web\Controller;

use Yii;
use frontend\models\Division;
use frontend\models\Student;
use common\models\User;
use yii\data\ArrayDataProvider;
use frontend\models\StudentRegistration;
use frontend\models\Application;
use frontend\models\ApplicationCapesubject;
use yii\helpers\Url;
use frontend\models\PersonInstitution;
use frontend\models\Phone;
use frontend\models\Email;
use frontend\models\Relation;
use frontend\models\ProgrammeCatalog;
use frontend\models\CapeGroup;
use frontend\models\CapeSubjectGroup;
use frontend\models\AcademicOffering;
use frontend\models\ApplicationStatus;
use frontend\models\RegistrationType;
use frontend\models\Offer;
use frontend\models\Address;

class StudentController extends Controller
{
    
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionManageStudents()
    {
        $dasgs = Division::findOne(['abbreviation' => 'DASGS']);
        $dtve = Division::findOne(['abbreviation' => 'DTVE']);
        $dasgsid = $dasgs ? $dasgs->divisionid : Null;
        $dtveid = $dtve ? $dtve->divisionid : Null;
        
        return $this->render('manage-students',
                [
                    'dasgsid' => $dasgsid,
                    'dtveid' => $dtveid,
                ]);
    }
    
    public function actionViewStudents($divisionid)
    {
        $stu_cond = array('student.isdeleted' => 0, 'student.isactive' => 1, 'student_registration.isdeleted' => 0, 
            'student_registration.isactive' => 1);
        if ($divisionid && $divisionid != 1)
        {
            $stu_cond['application_period.divisionid'] = $divisionid;
        }
        
        $students = Student::find()
                ->innerJoin('student_registration', '`student`.`personid` = `student_registration`.`personid`')
                ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `student_registration`.`academicofferingid`')
                ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where($stu_cond)
                ->all();
        
        $data = array();
        foreach ($students as $student)
        {
            $user = User::findOne(['personid' => $student->personid]);
            $student_data = array();
            $student_data['title'] = $student->title;
            $student_data['firstname'] = $student->firstname;
            $student_data['middlename'] = $student->middlename;
            $student_data['lastname'] = $student->lastname;
            $student_data['studentno'] = $user ? $user->username : NULL;
            $student_data['applicantno'] = $student->applicantname;
            $student_data['gender'] = ucwords($student->gender);
            $student_data['admissiondate'] = $student->admissiondate;
            $student_data['dob'] = $student->dateofbirth;
            $student_data['studentmail'] = $student->email;
            $student_data['studentid'] = $student->studentid;
            $data[] = $student_data;
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                'attributes' => ['firstname', 'lastname', 'studentno'],
              ]
        ]);

        return $this->render('view-students', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    
    /*
    * Purpose: Collect search parameters and display results of an applicant search.
    * Created: 1/08/2015 by Gamal Crichton
    * Last Modified: 22/09/2015 by Gamal Crichton
    */
    public function actionSearchStudent()
    {
        $dataProvider = NULL;
        $info_string = "";
        if (Yii::$app->request->post() || !empty(Yii::$app->session->get('stu_id')) || !empty(Yii::$app->session->get('firstname'))
                || !empty(Yii::$app->session->get('lastname')) || !empty(Yii::$app->session->get('email')))
        {
            if (Yii::$app->request->post())
            {
                $request = Yii::$app->request;
                $stu_id = $request->post('id');
                $firstname = $request->post('firstname');
                $lastname = $request->post('lastname');
                $email = $request->post('email');

                Yii::$app->session->set('stu_id', $stu_id);
                Yii::$app->session->set('firstname', $firstname);
                Yii::$app->session->set('lastname', $lastname);
                Yii::$app->session->set('email', $email);
            }
            else if (!empty(Yii::$app->session->get('stu_id')) || !empty(Yii::$app->session->get('firstname'))
                || !empty(Yii::$app->session->get('lastname')) || !empty(Yii::$app->session->get('email')))
            {
                $stu_id = Yii::$app->session->get('stu_id');
                $firstname = Yii::$app->session->get('firstname');
                $lastname = Yii::$app->session->get('lastname');
                $email = Yii::$app->session->get('email');
            }
            
            if ($stu_id)
            {
                $user = User::findOne(['username' => $stu_id, 'isdeleted' => 0]);
                 $cond_arr['personid'] = $user ? $user->personid : NULL;
                 $info_string = $info_string .  " Student ID: " . $stu_id;
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
            
                $students = Student::find()->where($cond_arr)->all();
                if (empty($students))
                {
                    Yii::$app->getSession()->setFlash('error', 'No user found matching this criteria.');
                }
                else
                {
                    $data = array();
                    foreach ($students as $student)
                    {
                        $stu = array();
                        $user = $student->getPerson()->one();
                        
                        $stu['studentno'] = $user ? $user->username : '';
                        $stu['studentid'] = $student->studentid;
                        $stu['firstname'] = $student->firstname;
                        $stu['middlename'] = $student->middlename;
                        $stu['lastname'] = $student->lastname;
                        $stu['gender'] = $student->gender;
                        $stu['dateofbirth'] = $student->dateofbirth;
                        $data[] = $stu;
                    }
                    $dataProvider = new ArrayDataProvider([
                        'allModels' => $data,
                        'pagination' => [
                            'pageSize' => 20,
                        ],
                        'sort' => [
                            'attributes' => ['studentid', 'firstname', 'lastname'],
                            ]
                    ]);
                    if (!$user)
                    {
                        Yii::$app->session->setFlash('error', 'User not found');
                    }
                }
        }
    }
    return $this->render('search-screen', 
        [
            'results' => $dataProvider,
            'info_string' => $info_string,
        ]);
  }
  
  /*
    * Purpose: Retrieve information necessary to display results of a student search.
    * Created: 1/08/2015 by Gamal Crichton
    * Last Modified: 14/09/2015 by Gamal Crichton
    */
  public function actionViewStudent($studentid, $username = '')
  {
      $student = Student::findOne(['studentid' => $studentid]);
      $personid = $student->getPerson()->one() ? $student->getPerson()->one()->personid : NULL;
      if (empty($username))
      {
          $user = User::findOne(['personid' => $personid]);
          $username = $user ? $user->username : '';
      }
      $registrations = StudentRegistration::findAll(['personid' => $personid, 'isdeleted' => 0]);
      $data = array();
      $acads_done = array();
        foreach($registrations as $registration)
        {
            $reg_details = array();
            $acad_off = $registration->getAcademicoffering()->one();
            //To deal with cases of multiple registartions to same academic offering which is allowed esp with CAPE
            if (in_array($acad_off->academicofferingid, $acads_done))
            {
                continue;
            }
            $reg_details['active'] = $registration->isactive;
            array_push($acads_done, $acad_off->academicofferingid);
            $cape_subjects_names = array();
            $programme = $acad_off ? $acad_off->getProgrammeCatalog()->one() : NULL;
            $applications = Application::find()
                    ->innerJoin('offer', '`offer`.`applicationid` = `application`.`applicationid`')
                    ->where(['personid' => $personid, 'application.academicofferingid' => $acad_off->academicofferingid])
                    ->all();
            $latest = NULL;
            
            if (count($applications) > 1)
            {
               
                $latest = Application::find()
                    ->innerJoin('offer', '`offer`.`applicationid` = `application`.`applicationid`')
                    ->where(['personid' => $personid, 'application.academicofferingid' => $acad_off->academicofferingid])
                    ->orderby('applicationid desc', 'DESC')
                    ->one();
                $reg_details['active'] = False;
                 
            }
            $active_reg = StudentRegistration::findAll(['academicofferingid' => $registration->academicofferingid, 'personid' => $personid,
                    'isactive' => 1]);
            foreach($applications as $application)
            {
                $cape_subjects_names = array();
                $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
                foreach ($cape_subjects as $cs) { $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; }

                $reg_details['order'] = $application->ordering;
                $reg_details['applicationid'] = $application->applicationid;
                $reg_details['programme_name'] = $cape_subjects ? "CAPE: " . implode(' ,', $cape_subjects_names) : $programme->getFullName();
                if ($application == $latest && $active_reg){ $reg_details['active'] = True; }
                $reg_details['divisionid'] = $application->divisionid;

                $data[] = $reg_details;
            }
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
      
      return $this->render('view-student', 
              [
                  'student' => $student,
                  'dataProvider' => $dataProvider,
                  'username' => $username,
              ]);
  }
  
  /*
    * Purpose: Junction for various actions to be done to an student after an applicant search.
    * Created: 7/09/2015 by Gamal Crichton
    * Last Modified: 7/09/2015 by Gamal Crichton
    */
  public function actionStudentActions()
  {
      if (Yii::$app->request->post())
      {
          $request = Yii::$app->request;
          $username = $request->post('username');
          if ($request->post('view_personal') === '')
          {
              return $this->redirect(Url::to(['student/view-personal', 'username' => $username]));
          }
          if ($request->post('edit_personal') === '')
          {
              return $this->redirect(Url::to(['student/edit-personal', 'username' => $username]));
          }
          if ($request->post('add_registration') === '')
          {
              return $this->redirect(Url::to(['student/add-registration', 'username' => $username]));
          }
      }
  }
  
  /*
    * Purpose: Prepares student personal information for viewing only
    * Created: 7/09/2015 by Gamal Crichton
    * Last Modified: 7/09/2015 by Gamal Crichton
    */
  public function actionViewPersonal($username)
  {
      $user = User::findOne(['username' =>$username]);
      $student = $user ? Student::findOne(['personid' =>$user->personid]) : Null;
      $institutions = $student ? PersonInstitution::findAll(['personid' => $student->personid, 'isdeleted' => 0]) : array();
      $phone = $user ? Phone::findOne(['personid' =>$user->personid]) : NULL;
      $email = $user ? Email::findOne(['personid' =>$user->personid]) : NULL;
      $relations = $user ? Relation::findAll(['personid' =>$user->personid]) : NULL;
      $addresses = $user ? Address::findAll(['personid' =>$user->personid]) : NULL;
      
      if (!$student)
      {
          Yii::$app->session->setFlash('error', 'No details found for this student.');
      }
      
      return $this->render('view-student-details',
              [
                  'username' => $user ? $user->username : '',
                  'student' => $student,
                  'institutions' => $institutions,
                  'phone' => $phone,
                  'email' => $email,
                  'relations' => $relations,
                  'addresses' => $addresses,
              ]);
  }
  
  /*
    * Purpose: Prepares student personal information for editing
    * Created: 7/09/2015 by Gamal Crichton
    * Last Modified: 14/09/2015 by Gamal Crichton
    */
  public function actionEditPersonal($username)
  {
      if (Yii::$app->request->post())
      {
          $request = Yii::$app->request;
          
          $student = Student::findOne(['studentid' => $request->post('studentid')]);
          $institutions = $student ? PersonInstitution::findAll(['personid' => $student->personid, 'isdeleted' => 0]) : array();
          $phone = $student ? Phone::findOne(['personid' =>$student->personid]) : NULL;
          $email = $student ? Email::findOne(['personid' =>$student->personid]) : NULL;
          $relations = $student ? Relation::findAll(['personid' =>$student->personid]) : NULL;
          if ($student->load(Yii::$app->request->post()) && $phone->load(Yii::$app->request->post()) &&
                  $email->load(Yii::$app->request->post()))
          { 
              if (!$student->save() && $phone->save() && $email->save())
              {
                  Yii::$app->session->setFlash('error', 'Student could not be saved');
              }
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
          $this->redirect(Url::to(['student/view-personal', 'username' =>$request->post('username')]));
      }
      $user = User::findOne(['username' =>$username]);
      $student = $user ? Student::findOne(['personid' =>$user->personid]) : Null;
      $institutions = $student ? PersonInstitution::findAll(['personid' => $student->personid, 'isdeleted' => 0]) : array();
      $phone = $user ? Phone::findOne(['personid' =>$user->personid]) : NULL;
      $email = $user ? Email::findOne(['personid' =>$user->personid]) : NULL;
      $relations = $user ? Relation::findAll(['personid' =>$user->personid]) : NULL;
      
      if (!$student)
      {
          Yii::$app->session->setFlash('error', 'No details found for this student.');
      }
      
      return $this->render('edit-student-details',
              [
                  'username' => $user ? $user->username : '',
                  'student' => $student,
                  'institutions' => $institutions,
                  'phone' => $phone,
                  'email' => $email,
                  'relations' => $relations,
              ]);
  }
  
  /*
    * Purpose: Prepares student personal information for editing
    * Created: 7/09/2015 by Gamal Crichton
    * Last Modified: 14/09/2015 by Gamal Crichton
    */
  public function actionEditRegistration($username)
  {
      
  }
  
  /*
    * Purpose: Adds a regsitration to a student
    * Created: 14/09/2015 by Gamal Crichton
    * Last Modified: 14/09/2015 by Gamal Crichton
    */
  public function actionAddRegistration($username = '')
  {
      //Get user's division_id from session
      $division_id = Yii::$app->session->get('divisionid');
            
      if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            $username = $request->post('username');
            $person = User::findOne(['username' => $username]);
            $student = Student::findOne(['personid' => $person->personid]);

            $student_personid =  $student ? $student->personid : Yii::$app->session->setFlash('error', 'Student not found');
            $app_count = Application::find()->where(['personid' => $student_personid])->count();

            $programme = ProgrammeCatalog::findOne(['programmecatalogid' => $request->post('programme')]);
            $prog_name = $programme ? $programme->name : Yii::$app->session->setFlash('error', 'Programme not found');   
            $application = new Application();
            $application->personid =  $student_personid;
            $ac_off = AcademicOffering::findOne(['programmecatalogid' => $request->post('programme'), 'isactive' =>1]);
            $application->academicofferingid = $ac_off ? $ac_off->academicofferingid : Null;
            $application->divisionid = $ac_off ? $ac_off->getApplicationperiod()->one()->divisionid : Null;
            $application->applicationstatusid = ApplicationStatus::findOne(['name' => 'offer'])->applicationstatusid;
            $application->applicationtimestamp = date("Y-m-d H:i:s");
            $application->ordering =  $app_count >= 3 ? $app_count + 1 : 4;
            $application->ipaddress = $request->getUserIP() ;
            $application->browseragent = $request->getUserAgent();
            if ($application->save())
            {
                $cape_success = True;
                if (strcasecmp($prog_name, "cape") == 0)
                {
                    //Deal with Cape Subjects
                    $groups_used = array();
                    foreach($request->post('cape_subject') as $key=>$value)
                    {
                        $groupid = CapeSubjectGroup::findOne(['capesubjectid' => $key])->capegroupid;
                        if (!in_array($groupid, $groups_used))
                        {
                            array_push($groups_used, $groupid);
                            $application_cape = new ApplicationCapesubject();
                            $application_cape->applicationid = $application->applicationid;
                            $application_cape->capesubjectid = $key;
                            if (!$application_cape->save())
                            {
                                Yii::$app->session->setFlash('error', 'Cape Subject could not be added');
                                $cape_success = False;
                                break;
                            }
                        }
                        else
                        {
                            Yii::$app->session->setFlash('error', 'Subjects from the same group selected');
                            $cape_success = False;
                            break;
                        }
                    }
                }
                if ($cape_success)
                {
                    $offer = new Offer();
                    $offer->applicationid = $application->applicationid;
                    $offer->issuedby = Yii::$app->user->getId();
                    $offer->issuedate = date("Y-m-d");
                    if ($offer->save())
                    {
                        $app_status = ApplicationStatus::findOne(['name' => 'offer']);
                        $application->applicationstatusid = $app_status->applicationstatusid;
                        if ($application->save())
                        {
                           $registrations = StudentRegistration::findAll(['personid' => $student_personid, 'isdeleted' => 0]);
                        
                           $reg = new StudentRegistration();
                           $reg_type = RegistrationType::findOne(['name' => 'fulltime', 'isdeleted' => 0]);

                           $reg->personid = $student->personid;
                           $reg->academicofferingid = $application->academicofferingid;
                           $reg->registrationtypeid = $reg_type->registrationtypeid;
                           $reg->currentlevel = 1;
                           $reg->registrationdate = date('Y-m-d');

                           if ($reg->save())
                           {
                               //Inactivate previous registrations and their offers
                               foreach ($registrations as $prev_reg)
                               {
                                   $prev_reg->isactive = 0;
                                   $prev_reg->save();
                                   $offers = Offer::find()
                                           ->innerJoin('application', '`application`.`applicationid` = `offer`.`applicationid`')
                                           ->innerJoin('student_registration', '`student_registration`.`academicofferingid` = `application`.`academicofferingid`')
                                           ->where(['student_registration.studentregistrationid' => $prev_reg->studentregistrationid, 
                                                   'student_registration.isdeleted' => 0])
                                           ->all();
                                   foreach ($offers as $offer)
                                   {
                                       $offer->isactive = 0;
                                       $offer->save();
                                   }
                               }
                               Yii::$app->session->setFlash('success', 'New registration added.');
                           }
                        }
                    }
                }
            }
            return $this->redirect(Url::to(['student/view-student', 'studentid' => $student->studentid, 'username' => $username]));
        }
        else
        {
            $person = User::findOne(['username' => $username]);
            $student = Student::findOne(['personid' => $person->personid]);
            $personid = $person ? $person->personid : NULL;
            $applications = $personid ? Application::findAll(['personid' => $personid, 'isdeleted' => 0]) : array();
            $data = array();
            foreach($applications as $application)
            {
                $app_details = array();
                $cape_subjects_names = array();
                $programme = ProgrammeCatalog::find()
                    ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                    ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->where(['application.applicationid' => $application->applicationid])->one();
                $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
                foreach ($cape_subjects as $cs) { $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; }

                $programme_division = $programme->getDepartment()->one()->divisionid;

                $app_details['order'] = $application->ordering;
                $app_details['applicationid'] = $application->applicationid;
                $app_details['programme_name'] = empty($cape_subjects) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names);
                $app_details['offerable'] = ($programme_division == $division_id || $division_id == 1);
                $data[] = $app_details;
            }
            $dataProvider = new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => [
                    'pageSize' => 5,
                ],
            ]);
            $prog_cond = array('application_period.divisionid' => $division_id, 'application_period.isactive' => 1);
            if ($division_id && $division_id == 1)
            {
                $prog_cond = array('application_period.isactive' => 1);
            }
            $progs = ProgrammeCatalog::find()
                ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                ->where($prog_cond)
                ->all();
            
            $programmes = array();
            foreach($progs as $prog)
            {
               $programmes[$prog->programmecatalogid] = $prog->getFullName();
            }

            //Cape group information
            $cape_data = array();
            $cape_grps = CapeGroup::findall(['cape_group.isactive' => 1]);
            foreach ($cape_grps as $grp)
            {
                $cape_data[$grp->name] = CapeSubjectGroup::findAll(['capegroupid' => $grp->capegroupid]);
            }
            return $this->render('add-registration', 
               [        
                    'dataProvider' => $dataProvider, 
                    'programmes' => $programmes,
                   'cape_data' => $cape_data,
                   'division_id' => $division_id,
                   'firstname' => $student ? $student->firstname : '',
                   'middlename' => $student ? $student->middlename : '',
                   'lastname' => $student ? $student->lastname : '',
                   'username' => $username
               ]
            );
       }
  }
}
