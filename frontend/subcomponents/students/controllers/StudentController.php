<?php

namespace app\subcomponents\students\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;


use frontend\models\Division;
use frontend\models\Employee;
use frontend\models\EmployeeDepartment;
use frontend\models\Student;
use common\models\User;
use frontend\models\StudentRegistration;
use frontend\models\Application;
use frontend\models\ApplicationCapesubject;
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
use frontend\models\Department;
use frontend\models\AcademicYear;
use frontend\models\Cordinator;
use frontend\models\StudentStatus;
use frontend\models\Applicant;
use frontend\models\Assessment;
use frontend\models\AssessmentCape;
use frontend\models\AssessmentStudent;
use frontend\models\AssessmentStudentCape;
use frontend\models\BatchStudent;
use frontend\models\BatchStudentCape;
use frontend\models\Hold;
use frontend\models\StudentTransfer;
use frontend\models\StudentDeferral;

class StudentController extends Controller
{
    
    public function actionIndex()
    {
//        return $this->render('index');
        return $this->redirect(['student/find-a-student']);
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
        if (Yii::$app->request->post() || !Yii::$app->session->get('stu_id') || !Yii::$app->session->get('firstname')
                || !Yii::$app->session->get('lastname') || !Yii::$app->session->get('email'))
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
            else if (!Yii::$app->session->get('stu_id') || !Yii::$app->session->get('firstname')
                || !Yii::$app->session->get('lastname') || !Yii::$app->session->get('email'))
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
                    $offer->ispublished = 1;
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
                                           ->innerJoin('student_registration', '`student_registration`.`personid` = `application`.`personid`')
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
    
    
    
    /**
     * Renders the Student find_a_student view and process form submission
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 04/12/2015
     * Date Last Modified: 10/12/2015
     */
    public function actionFindAStudent($id = NULL)
    {
        $info_string = "";
        
        $all_student_data_container = array();
        $all_students_provider = array();
        $all_students_info = array();

        $a_f_provider = array();
        $a_f_info = array();

        $g_l_provider = array();
        $g_l_info = array();

        $m_r_provider = array();
        $m_r_info = array();

        $s_z_provider = array();
        $s_z_info = array();
        
        //need to facilitate breadcrumb navigation from 'student_listing' to 'programme_listing' of source division
        if ($id)
        {
            $request = Yii::$app->request;
            $divisionid = $id;

            if ($divisionid != NULL  && $divisionid != 0 && strcmp($divisionid, "0") != 0)
            {
                $division_name = Division::getDivisionAbbreviation($divisionid);
                $department_count = count(Department::getDepartments($divisionid));

                $data_package = array();
                $programme_collection = array();
                /*
                 * Package Collection structure is as follows
                 * [department_count, [[programme, cohort_count, [cohorts,...]]]
                 */
                array_push($data_package, $department_count);

                $programmes = ProgrammeCatalog::getProgrammes($divisionid);
                if ($programmes)
                {
                    foreach ($programmes as $programme) 
                    {
                        $temp_array = array();

                        $cohort_array = array();

                        array_push($temp_array, $programme);

                        $cohort_count = AcademicOffering::getCohortCount($programme->programmecatalogid); //yet to be created
                        array_push($temp_array, $cohort_count);

                        if ($cohort_count > 0)
                        {
                            $cohorts = AcademicOffering::getCohorts($programme->programmecatalogid); //yet to be created

                            for($i = 0 ; $i < $cohort_count ; $i++)
                            {
                                array_push($cohort_array, $cohorts[$i]);
                            }
                            array_push($temp_array, $cohort_array);
                        }

                        array_push($programme_collection, $temp_array);

                        $temp_array = NULL;
                        $cohort_array = NULL;
                        $name = NULL;
                        $cohort_count = NULL;
                        $cohorts = NULL;
                    }
                    array_push($data_package, $programme_collection);

                    return $this->render('programme_listing', [
                        'division_id' => $divisionid,
                        'division_name' => $division_name,
                        'data' => $data_package,
                    ]);
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'No programmes found.');
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Please select a divsion.');                
        }
        
        
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            $divisionid = $request->post('division');
            $studentid = $request->post('id_field');
            $firstname = $request->post('fname_field');
            $lastname = $request->post('lname_field');

            //if user initiates search based on programme
            if ($divisionid != NULL  && $divisionid != 0 && strcmp($divisionid, "0") != 0)
            {
                $division_name = Division::getDivisionAbbreviation($divisionid);
                $department_count = count(Department::getDepartments($divisionid));

                $data_package = array();
                $programme_collection = array();
                /*
                 * Package Collection structure is as follows
                 * [department_count, [[programme, cohort_count, [cohorts,...]]]
                 */
                array_push($data_package, $department_count);

                $programmes = ProgrammeCatalog::getProgrammes($divisionid);
                if ($programmes)
                {
                    foreach ($programmes as $programme) 
                    {
                        $temp_array = array();

                        $cohort_array = array();

                        array_push($temp_array, $programme);

                        $cohort_count = AcademicOffering::getCohortCount($programme->programmecatalogid); //yet to be created
                        array_push($temp_array, $cohort_count);

                        if ($cohort_count > 0)
                        {
                            $cohorts = AcademicOffering::getCohorts($programme->programmecatalogid); //yet to be created

                            for($i = 0 ; $i < $cohort_count ; $i++)
                            {
                                array_push($cohort_array, $cohorts[$i]);
                            }
                            array_push($temp_array, $cohort_array);
                        }

                        array_push($programme_collection, $temp_array);

                        $temp_array = NULL;
                        $cohort_array = NULL;
                        $name = NULL;
                        $cohort_count = NULL;
                        $cohorts = NULL;
                    }
                    array_push($data_package, $programme_collection);

                    return $this->render('programme_listing', [
                        'division_id' => $divisionid,
                        'division_name' => $division_name,
                        'data' => $data_package,
                    ]);
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'No programmes found.');
            }

            //if user initiates search based on studentID
            elseif ($studentid != NULL  && strcmp($studentid, "") != 0)
            {
                $info_string = $info_string .  " Student ID: " . $studentid;
                $user = User::findOne(['username' => $studentid, 'isdeleted' => 0]);

                if ($user)
                { 
                    //if system user is a Dean or Deputy Dean then their search is contrained by their division
                    if ((Yii::$app->user->can('Deputy Dean') || Yii::$app->user->can('Dean')  || Yii::$app->user->can('Divisional Staff'))  && !Yii::$app->user->can('System Administrator'))
                    {
//                        $divisionid = Employee::getEmployeeDivisionID(Yii::$app->user->identity->personid);
                        $divisionid = EmployeeDepartment::getUserDivision();
                        $registrations = StudentRegistration::getStudentsByDivision($divisionid, $user->personid);
                        
                        if (empty($registrations))
                        {
                            Yii::$app->getSession()->setFlash('error', 'No students found matching this criteria within your division.');
                            return $this->render('find_a_student_index',[
                                'all_students_provider' => $all_students_provider,
                                'info_string' => $info_string,
                            ]);
                        }
                    }
                    //if system user is not a Dean or Deputy Dean then their search is not contrained
                    else
                    {
                        $registrations = StudentRegistration::find()
                                    ->where(['personid' => $user->personid, 'isactive' => 1,  'isdeleted' => 0])
                                    ->all();
                    }
                    if (count($registrations) > 0)
                    {    
                        foreach ($registrations as $registration) 
                        { 
                            $student = Student::getStudent($user->personid);
                            if ($student)
                            {
                                $all_students_info['personid'] = $user->personid;
                                $all_students_info['studentregistrationid'] = $registration->studentregistrationid;
                                $all_students_info['studentno'] = $user->username;
                                $all_students_info['firstname'] = $student->firstname;
                                $all_students_info['middlename'] = $student->middlename;
                                $all_students_info['lastname'] = $student->lastname;
                                $all_students_info['gender'] = $student->gender;
                                
                                $offer_from = Offer::find()
                                        ->where(['offerid' => $registration->offerid, 'isdeleted' => 0])
                                        ->one();
                                if($offer_from == false)
                                    continue;
                                $current_cape_subjects_names = array();
                                $current_cape_subjects = array();
                                $current_application = $offer_from->getApplication()->one();
                                $current_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $current_application->getAcademicoffering()->one()->programmecatalogid]);
                                $current_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $current_application->applicationid]);
                                foreach ($current_cape_subjects as $cs)
                                { 
                                    $current_cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                                }
                                $currentprogramme = empty($current_cape_subjects) ? $current_programme->getFullName() : $current_programme->name . ": " . implode(' ,', $current_cape_subjects_names);
                                $all_students_info['current_programme'] = $currentprogramme;
                                
                                $enrollments = StudentRegistration::find()
                                        ->where(['personid' => $user->personid, 'isdeleted' => 0])
                                        ->count();
                                $all_students_info['enrollments'] = $enrollments;
                                
                                $student_status = StudentStatus::find()
                                                ->where(['studentstatusid' => $registration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
                                                ->one();
                                $all_students_info['studentstatus'] = $student_status->name;
                                $all_student_data_container[] = $all_students_info;
                            }
                            else
                            {
                                Yii::$app->session->setFlash('error', 'No user found matching this criteria.');
                            }
                        }
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'No students found matching this criteria.');
                    }                    

                    $all_students_provider = new ArrayDataProvider([
                            'allModels' => $all_student_data_container,
                            'pagination' => [
                                'pageSize' => 20,
                            ],
                            'sort' => [
                                'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                                'attributes' => ['firstname', 'lastname'],
                            ]
                    ]);    
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'No user found matching this criteria.');
                }                     
            }

            //if user initiates search based student name
            elseif( ($firstname != NULL && strcmp($firstname,"") != 0)  || ($lastname != NULL && strcmp($lastname,"") != 0) )
            {
//                    Yii::$app->getSession()->setFlash('error', 'Lets search using student name.');
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

                if (empty($cond_arr))
                {
                    Yii::$app->getSession()->setFlash('error', 'A search criteria must be entered.');
                }
                else
                {
//                    $cond_arr['isactive'] = 1;
                    $cond_arr['isdeleted'] = 0;

                    $students = Student::find()
                            ->where($cond_arr)
                            ->all();
                    
                    if (empty($students))
                    {
                        Yii::$app->getSession()->setFlash('error', 'No students found matching this criteria.');
                    }
                    else
                    {
                        //if system user is Dean or Deputy Dean then student_registration records are filtered by divisionid
                        $eligible_students_found = false; //students within correct division
                        if ((Yii::$app->user->can('Deputy Dean') || Yii::$app->user->can('Dean')  || Yii::$app->user->can('Divisional Staff')) &&  !Yii::$app->user->can('System Administrator'))
                        {
//                            $divisionid = Employee::getEmployeeDivisionID(Yii::$app->user->identity->personid);
                            $divisionid = EmployeeDepartment::getUserDivision();
                            foreach ($students as $student)
                            {
                                $registrations = StudentRegistration::getStudentsByDivision($divisionid, $student->personid);
                                if (!empty($registrations))
                                {
                                    foreach ($registrations as $registration)
                                    {
                                        $eligible_students_found = true;
                                        $user = User::findOne(['personid' => $student->personid, 'isactive' => 1, 'isdeleted' => 0]);
                                        if ($registration && $user)
                                        {
                                            $all_students_info['personid'] = $student->personid;
                                            $all_students_info['studentregistrationid'] = $registration->studentregistrationid;
                                            $all_students_info['studentno'] = $user->username;
                                            $all_students_info['firstname'] = $student->firstname;
                                            $all_students_info['middlename'] = $student->middlename;
                                            $all_students_info['lastname'] = $student->lastname;
                                            $all_students_info['gender'] = $student->gender;
                                            
                                            $offer_from = Offer::find()
                                                    ->where(['offerid' => $registration->offerid, 'isdeleted' => 0])
                                                    ->one();
                                            if($offer_from == false)
                                                continue;
                                            $current_cape_subjects_names = array();
                                            $current_cape_subjects = array();
                                            $current_application = $offer_from->getApplication()->one();
                                            $current_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $current_application->getAcademicoffering()->one()->programmecatalogid]);
                                            $current_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $current_application->applicationid]);
                                            foreach ($current_cape_subjects as $cs)
                                            { 
                                                $current_cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                                            }
                                            $currentprogramme = empty($current_cape_subjects) ? $current_programme->getFullName() : $current_programme->name . ": " . implode(' ,', $current_cape_subjects_names);
                                            $all_students_info['current_programme'] = $currentprogramme;
                                            
                                             $enrollments = StudentRegistration::find()
                                                    ->where(['personid' => $user->personid, 'isdeleted' => 0])
                                                    ->count();
                                            $all_students_info['enrollments'] = $enrollments;

                                            $student_status = StudentStatus::find()
                                                            ->where(['studentstatusid' => $registration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
                                                            ->one();
                                            $all_students_info['studentstatus'] = $student_status->name;
                                            $all_student_data_container[] = $all_students_info;
                                        }
                                    }
                                }  
                            }

                            //if among the possible matching 'student' records there are no 'student_registration' records related to the user's division
                            if ($eligible_students_found == false)
                            {
                                Yii::$app->getSession()->setFlash('error', 'No students found matching this criteria within your division.');
                                return $this->render('find_a_student_index',[
                                    'all_students_provider' => $all_students_provider,
                                    'info_string' => $info_string,
                                ]);
                            }
                        }
                        //if system user is not a Dean or Deputy Dean then their search is not contrained
                        else
                        {
                            foreach ($students as $student)
                            {   
                                $registration = StudentRegistration::find()
                                        ->where(['personid' => $student->personid, 'isactive' => 1, 'isdeleted' => 0])
                                        ->one();    
                                $user = User::findOne(['personid' => $student->personid, 'isactive' => 1, 'isdeleted' => 0]);
                                if ($registration && $user)
                                {
                                    $all_students_info['personid'] = $student->personid;
                                    $all_students_info['studentregistrationid'] = $registration->studentregistrationid;
                                    $all_students_info['studentno'] = $user->username;
                                    $all_students_info['firstname'] = $student->firstname;
                                    $all_students_info['middlename'] = $student->middlename;
                                    $all_students_info['lastname'] = $student->lastname;
                                    $all_students_info['gender'] = $student->gender;
                                    
                                    $offer_from = Offer::find()
                                            ->where(['offerid' => $registration->offerid, 'isdeleted' => 0])
                                            ->one();
                                    if($offer_from == false)
                                        continue;
                                    $current_cape_subjects_names = array();
                                    $current_cape_subjects = array();
                                    $current_application = $offer_from->getApplication()->one();
                                    $current_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $current_application->getAcademicoffering()->one()->programmecatalogid]);
                                    $current_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $current_application->applicationid]);
                                    foreach ($current_cape_subjects as $cs)
                                    { 
                                        $current_cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                                    }
                                    $currentprogramme = empty($current_cape_subjects) ? $current_programme->getFullName() : $current_programme->name . ": " . implode(' ,', $current_cape_subjects_names);
                                    $all_students_info['current_programme'] = $currentprogramme;

                                     $enrollments = StudentRegistration::find()
                                            ->where(['personid' => $user->personid, 'isdeleted' => 0])
                                            ->count();
                                      $all_students_info['enrollments'] = $enrollments;
                                
                                    $student_status = StudentStatus::find()
                                                    ->where(['studentstatusid' => $registration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
                                                    ->one();
                                    $all_students_info['studentstatus'] = $student_status->name;
                                    $all_student_data_container[] = $all_students_info;
                                }
                                else
                                {
                                    Yii::$app->session->setFlash('error', 'No user found matching this criteria.');
                                }                  
                            }
                        }

                        $all_students_provider = new ArrayDataProvider([
                                'allModels' => $all_student_data_container,
                                'pagination' => [
                                    'pageSize' => 30,
                                ],
                                'sort' => [
                                    'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                                    'attributes' => ['firstname', 'lastname'],
                                ]
                        ]);      
                    } 
                }
            }

            else    //if user clicks 'search' button without entering any search criteria
            {
                Yii::$app->getSession()->setFlash('error', 'Please select enter valid search criteria.');
            }
        }  

        return $this->render('find_a_student_index',[
            'all_students_provider' => $all_students_provider,
            'info_string' => $info_string,
        ]);
    }
    
    
    
    /**
     * Renders the 'Student Listing' view and process form submission
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 20/12/2015
     * Date Last Modified: 20/12/2015
     */
    public function actionStudents($academicyearid, $academicofferingid, $programmename, $divisionid)
    {
        $all_students_provider = NULL;
        $all_students_info = array();

        $a_f_provider = NULL;
        $a_f_info = array();

        $g_l_provider = NULL;
        $g_l_info = array();

        $m_r_provider = NULL;
        $m_r_info = array();

        $s_z_provider = NULL;
        $s_z_info = array();

        $academicyear = AcademicYear::getYear($academicyearid);
//        $cordinator = Cordinator::getCordinator($academicofferingid, 2);
        $cordinator_details = "";
        $cordinators = Cordinator::find()
               ->where(['academicofferingid' => $academicofferingid , 'isserving' => 1, 'isactive' => 1, 'isdeleted' => 0])
               ->orderBy('cordinatorid DESC')
               -> all();
       if($cordinators)
       {
           foreach($cordinators as $key => $cordinator)
           {
               $name = "";
               $name = Employee::getEmployeeName($cordinators[$key]->personid);
               if(count($cordinators) - 1 == 0)
                $cordinator_details .= $name;
                else 
                    $cordinator_details .= $name . ", ";
           }
       }
        
        $registrations = StudentRegistration::getStudentRegistration($academicofferingid);
        if ($registrations)
        {
            /************************** Prepares data for 'all_student' tab *******************************************/
            foreach ($registrations as $registration)
            {
                $user = $registration->getPerson()->one();
                if ($user)
                {                 
                    $personid = $registration->personid;
                    $student = Student::getStudent($personid);
                    if ($student)
                    {
                        $all_students_info['personid'] = $user->personid;
                        $all_students_info['studentregistrationid'] = $registration->studentregistrationid;
                        $all_students_info['studentno'] = $user->username;
                        $all_students_info['firstname'] = $student->firstname;
                        $all_students_info['middlename'] = $student->middlename;
                        $all_students_info['lastname'] = $student->lastname;
                        $all_students_info['gender'] = $student->gender;

                        $student_status = StudentStatus::find()
                                        ->where(['studentstatusid' => $registration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
                                        ->one();
                        $all_students_info['studentstatus'] = $student_status->name;
                        $all_student_data_container[] = $all_students_info;
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'Student not found');
                    }
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'User not found');
                }                    
            }
            $all_students_provider = new ArrayDataProvider([
                    'allModels' => $all_student_data_container,
                    'pagination' => [
                        'pageSize' => 40,
                    ],
                    'sort' => [
                        'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                        'attributes' => ['firstname', 'lastname'],
                    ]
            ]);
            /***************************************************************************************************************/

            /*************************************Prepares data for 'a_f' tab **********************************************/
            foreach ($registrations as $registration)
            {
                $user = $registration->getPerson()->one();
                if ($user)
                {                 
                    $personid = $registration->personid;
                    $student = Student::getStudent($personid);

                    //inspects surname for filtering
                    $surname = $student->lastname;
                    $first_character = substr($surname,0,1);

                    if ($student==true)
                    {
                        if (strcmp($first_character,"A")==0 || strcmp($first_character,"a")==0 || strcmp($first_character,"B")==0 || strcmp($first_character,"b")==0 || strcmp($first_character,"C")==0 || strcmp($first_character,"c")==0  || strcmp($first_character,"D")==0 || strcmp($first_character,"d")==0 || strcmp($first_character,"E")==0 || strcmp($first_character,"e")==0 || strcmp($first_character,"F")==0 || strcmp($first_character,"f")==0)
                        {
                            $a_f_info['personid'] = $user->personid;
                            $a_f_info['studentregistrationid'] = $registration->studentregistrationid;
                            $a_f_info['studentno'] = $user->username;
                            $a_f_info['firstname'] = $student->firstname;
                            $a_f_info['middlename'] = $student->middlename;
                            $a_f_info['lastname'] = $student->lastname;
                            $a_f_info['gender'] = $student->gender;

                            $student_status = StudentStatus::find()
                                            ->where(['studentstatusid' => $registration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
                                            ->one();
                            $a_f_info['studentstatus'] = $student_status->name;
                            $a_f_data_container[] = $a_f_info;
                        }
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'Student not found');
                    }
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'User not found');
                }                    
            } 
            $a_f_provider = new ArrayDataProvider([
                    'allModels' => $a_f_data_container,
                    'pagination' => [
                        'pageSize' => 40,
                    ],
                    'sort' => [
                        'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                        'attributes' => ['firstname', 'lastname'],
                    ]
            ]);
            /***************************************************************************************************************/

            /*************************************Prepares data for 'g_l' tab **********************************************/
            foreach ($registrations as $registration)
            {
                $user = $registration->getPerson()->one();
                if ($user)
                {                 
                    $personid = $registration->personid;
                    $student = Student::getStudent($personid);

                    //inspects surname for filtering
                    $surname = $student->lastname;
                    $first_character = substr($surname,0,1);

                    if ($student==true)
                    {
                        if (strcmp($first_character,"G")==0 || strcmp($first_character,"g")==0 || strcmp($first_character,"H")==0 || strcmp($first_character,"h")==0 || strcmp($first_character,"I")==0 || strcmp($first_character,"i")==0  || strcmp($first_character,"J")==0 || strcmp($first_character,"j")==0 || strcmp($first_character,"K")==0 || strcmp($first_character,"k")==0 || strcmp($first_character,"L")==0 || strcmp($first_character,"l")==0)
                        {
                            $g_l_info['personid'] = $user->personid;
                            $g_l_info['studentregistrationid'] = $registration->studentregistrationid;
                            $g_l_info['studentno'] = $user->username;
                            $g_l_info['firstname'] = $student->firstname;
                            $g_l_info['middlename'] = $student->middlename;
                            $g_l_info['lastname'] = $student->lastname;
                            $g_l_info['gender'] = $student->gender;

                            $student_status = StudentStatus::find()
                                            ->where(['studentstatusid' => $registration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
                                            ->one();
                            $g_l_info['studentstatus'] = $student_status->name;
                            $g_l_data_container[] = $g_l_info;
                        }
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'Student not found');
                    }
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'User not found');
                }                    
            } 
            $g_l_provider = new ArrayDataProvider([
                    'allModels' => $g_l_data_container,
                    'pagination' => [
                        'pageSize' => 25,
                    ],
                    'sort' => [
                        'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                        'attributes' => ['firstname', 'lastname'],
                    ]
            ]);
            /***************************************************************************************************************/ 

            /*************************************Prepares data for 'm_r' tab **********************************************/
            foreach ($registrations as $registration)
            {
                $user = $registration->getPerson()->one();
                if ($user)
                {                 
                    $personid = $registration->personid;
                    $student = Student::getStudent($personid);

                    //inspects surname for filtering
                    $surname = $student->lastname;
                    $first_character = substr($surname,0,1);

                    if ($student==true)
                    {
                        if (strcmp($first_character,"M")==0 || strcmp($first_character,"m")==0 || strcmp($first_character,"N")==0 || strcmp($first_character,"n")==0 || strcmp($first_character,"O")==0 || strcmp($first_character,"o")==0  || strcmp($first_character,"P")==0 || strcmp($first_character,"p")==0 || strcmp($first_character,"Q")==0 || strcmp($first_character,"q")==0 || strcmp($first_character,"R")==0 || strcmp($first_character,"r")==0)
                        {
                            $m_r_info['personid'] = $user->personid;
                            $m_r_info['studentregistrationid'] = $registration->studentregistrationid;
                            $m_r_info['studentno'] = $user->username;
                            $m_r_info['firstname'] = $student->firstname;
                            $m_r_info['middlename'] = $student->middlename;
                            $m_r_info['lastname'] = $student->lastname;
                            $m_r_info['gender'] = $student->gender;

                            $student_status = StudentStatus::find()
                                            ->where(['studentstatusid' => $registration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
                                            ->one();
                            $m_r_info['studentstatus'] = $student_status->name;
                            $m_r_data_container[] = $m_r_info;                       
                        }
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'Student not found');
                    }
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'User not found');
                }                    
            } 
            $m_r_provider = new ArrayDataProvider([
                    'allModels' => $m_r_data_container,
                    'pagination' => [
                        'pageSize' => 25,
                    ],
                    'sort' => [
                        'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                        'attributes' => ['firstname', 'lastname'],
                    ]
            ]);
            /***************************************************************************************************************/

            /*************************************Prepares data for 's_z' tab **********************************************/
            foreach ($registrations as $registration)
            {
                $user = $registration->getPerson()->one();
                if ($user)
                {                 
                    $personid = $registration->personid;
                    $student = Student::getStudent($personid);

                    //inspects surname for filtering
                    $surname = $student->lastname;
                    $first_character = substr($surname,0,1);

                    if ($student==true)
                    {
                        if (strcmp($first_character,"S")==0 || strcmp($first_character,"s")==0 || strcmp($first_character,"T")==0 || strcmp($first_character,"t")==0  || strcmp($first_character,"U")==0 || strcmp($first_character,"u")==0 || strcmp($first_character,"V")==0 || strcmp($first_character,"v")==0 || strcmp($first_character,"W")==0 || strcmp($first_character,"w")==0 || strcmp($first_character,"X")==0 || strcmp($first_character,"x")==0 || strcmp($first_character,"Y")==0 || strcmp($first_character,"y")==0 || strcmp($first_character,"Z")==0 || strcmp($first_character,"z")==0)
                        {
                            $s_z_info['personid'] = $user->personid;
                            $s_z_info['studentregistrationid'] = $registration->studentregistrationid;
                            $s_z_info['studentno'] = $user->username;
                            $s_z_info['firstname'] = $student->firstname;
                            $s_z_info['middlename'] = $student->middlename;
                            $s_z_info['lastname'] = $student->lastname;
                            $s_z_info['gender'] = $student->gender;

                            $student_status = StudentStatus::find()
                                            ->where(['studentstatusid' => $registration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
                                            ->one();
                            $s_z_info['studentstatus'] = $student_status->name;
                            $s_z_data_container[] = $s_z_info;
                        }
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'Student5 not found');
                    }
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'User not found');
                }                    
            } 
            $s_z_provider = new ArrayDataProvider([
                    'allModels' => $s_z_data_container,
                    'pagination' => [
                        'pageSize' => 25,
                    ],
                    'sort' => [
                        'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                        'attributes' => ['firstname', 'lastname'],
                    ]
            ]); 
            /***************************************************************************************************************/
        }
        else
        {
            Yii::$app->session->setFlash('error', 'No students are enrolled in the selected cohort.');
            return $this->redirect(['student/find-a-student']);
//                , 'id' => $divisionid]);

        }

        return $this->render('student_listing', [
            'division_id' => $divisionid,
            'programmename' => $programmename,
            'academicyear' => $academicyear,
            'cordinator_details' => $cordinator_details,
            'all_students_provider' => $all_students_provider,
            'a_f_provider' => $a_f_provider,
            'g_l_provider' => $g_l_provider,
            'm_r_provider' => $m_r_provider,
            's_z_provider' => $s_z_provider,      
        ]);
    }
    
    
    /**
     * Renders the 'Academic Holds' view and process form submission
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 07/01/2015
     * Date Last Modified: 07/01/2015
     */
    public function actionViewActiveAcademicHolds($notified = NULL, $studentholdid = NULL)
    {
        if($notified != NULL && $studentholdid != NULL)
        {
            $student_hold = Hold::find()
                        ->where(['studentholdid' => $studentholdid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            if($student_hold)
            {
                if($notified == 1)
                {
                    $student_hold->wasnotified = 1;
                    $student_hold->save();
                }
                elseif($notified == 0)
                {
                    $student_hold->wasnotified = 0;
                    $student_hold->save();
                }
            }
        }
        
        $divisions = Division::find()
                    ->where(['isactive' => 1, 'isdeleted' => 0])
                    ->andWhere(['not in', 'divisionid', [1, 8]])
                    ->all();
        
        $all_holds = NULL;
        $all_provider = NULL;
        $all_info = array();
        $all_holds_data_container = array();
        
        $dasgs_holds = NULL;
        $dasgs_provider = NULL;
        $dasgs_info = array();
        $dasgs_holds_data_container = array();
        
        $dtve_holds = NULL;
        $dtve_provider = NULL;
        $dtve_info = array();
        $dtve_holds_data_container = array();
        
        $dte_holds =  NULL;
        $dte_provider = NULL;
        $dte_info = array();
        $dte_holds_data_container = array();
        
        $dne_holds = NULL;
        $dne_provider = NULL;
        $dne_info = array();
        $dne_holds_data_container = array();
        
        $all_holds = StudentRegistration::getAcademicActiveHolds(1);
        foreach ($all_holds as $all_hold)
        {
            $all_info['studentholdid'] = $all_hold['studentholdid'];
            $all_info['studentregistrationid'] = $all_hold['studentregistrationid'];
            $all_info['personid'] = $all_hold['personid'];
            $all_info['studentid'] = $all_hold['studentid'];
            $all_info['firstname'] = $all_hold['firstname'];
            $all_info['lastname'] = $all_hold['lastname'];
            $all_info['programme'] = $all_hold['programme'];
            $all_info['holdtype'] = $all_hold['holdtype'];
            $all_info['wasnotified'] = $all_hold['wasnotified'];
            $all_holds_data_container[] = $all_info; 
        }
        $all_provider = new ArrayDataProvider([
                    'allModels' => $all_holds_data_container,
                    'pagination' => [
                        'pageSize' => 25,
                    ],
                    'sort' => [
                        'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                        'attributes' => ['firstname', 'lastname'],
                        ]
            ]); 
                  
        
        $dasgs_holds = StudentRegistration::getAcademicActiveHolds(4);
        foreach ($dasgs_holds as $dasgs_hold)
        {
            $dasgs_info['studentholdid'] = $dasgs_hold['studentholdid'];
            $dasgs_info['studentregistrationid'] = $dasgs_hold['studentregistrationid'];
            $dasgs_info['personid'] = $dasgs_hold['personid'];
            $dasgs_info['studentid'] = $dasgs_hold['studentid'];
            $dasgs_info['firstname'] = $dasgs_hold['firstname'];
            $dasgs_info['lastname'] = $dasgs_hold['lastname'];
            $dasgs_info['programme'] = $dasgs_hold['programme'];
            $dasgs_info['holdtype'] = $dasgs_hold['holdtype'];
            $dasgs_info['wasnotified'] = $dasgs_hold['wasnotified'];
            $dasgs_holds_data_container[] = $dasgs_info; 
        }
        $dasgs_provider = new ArrayDataProvider([
                    'allModels' => $dasgs_holds_data_container,
                    'pagination' => [
                        'pageSize' => 25,
                    ],
                    'sort' => [
                        'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                        'attributes' => ['firstname', 'lastname'],
                        ]
            ]); 
        
        
        $dtve_holds = StudentRegistration::getAcademicActiveHolds(5);
        foreach ($dtve_holds as $dtve_hold)
        {
            $dtve_info['studentholdid'] = $dtve_hold['studentholdid'];
            $dtve_info['studentregistrationid'] = $dtve_hold['studentregistrationid'];
            $dtve_info['personid'] = $dtve_hold['personid'];
            $dtve_info['studentid'] = $dtve_hold['studentid'];
            $dtve_info['firstname'] = $dtve_hold['firstname'];
            $dtve_info['lastname'] = $dtve_hold['lastname'];
            $dtve_info['programme'] = $dtve_hold['programme'];
            $dtve_info['holdtype'] = $dtve_hold['holdtype'];
            $dtve_info['wasnotified'] = $dtve_hold['wasnotified'];
            $dtve_holds_data_container[] = $dtve_info; 
        }
        $dtve_provider = new ArrayDataProvider([
                    'allModels' => $dtve_holds_data_container,
                    'pagination' => [
                        'pageSize' => 25,
                    ],
                    'sort' => [
                        'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                        'attributes' => ['firstname', 'lastname'],
                        ]
            ]); 
        
        
        $dte_holds =  StudentRegistration::getAcademicActiveHolds(6);
        foreach ($dte_holds as $dte_hold)
        {
            $dte_info['studentholdid'] = $dte_hold['studentholdid'];
            $dte_info['studentregistrationid'] = $dte_hold['studentregistrationid'];
            $dte_info['personid'] = $dte_hold['personid'];
            $dte_info['studentid'] = $dte_hold['studentid'];
            $dte_info['firstname'] = $dte_hold['firstname'];
            $dte_info['lastname'] = $dte_hold['lastname'];
            $dte_info['programme'] = $dte_hold['programme'];
            $dte_info['holdtype'] = $dte_hold['holdtype'];
            $dte_info['wasnotified'] = $dte_hold['wasnotified'];
            $dte_holds_data_container[] = $dte_info; 
        }
        $dte_provider = new ArrayDataProvider([
                    'allModels' => $dte_holds_data_container,
                    'pagination' => [
                        'pageSize' => 25,
                    ],
                    'sort' => [
                        'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                        'attributes' => ['firstname', 'lastname'],
                        ]
            ]); 
        
        
        $dne_holds = StudentRegistration::getAcademicActiveHolds(7);
        foreach ($dne_holds as $dne_hold)
        {
            $dne_info['studentholdid'] = $dne_hold['studentholdid'];
            $dne_info['studentregistrationid'] = $dne_hold['studentregistrationid'];
            $dne_info['personid'] = $dne_hold['personid'];
            $dne_info['studentid'] = $dne_hold['studentid'];
            $dne_info['firstname'] = $dne_hold['firstname'];
            $dne_info['lastname'] = $dne_hold['lastname'];
            $dne_info['programme'] = $dne_hold['programme'];
            $dne_info['holdtype'] = $dne_hold['holdtype'];
            $dne_info['wasnotified'] = $dne_hold['wasnotified'];
            $dne_holds_data_container[] = $dne_info; 
        }
        $dne_provider = new ArrayDataProvider([
                    'allModels' => $dne_holds_data_container,
                    'pagination' => [
                        'pageSize' => 25,
                    ],
                    'sort' => [
                        'defaultOrder' => ['lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                        'attributes' => ['firstname', 'lastname'],
                        ]
            ]); 
   
        
        return $this->render('active_academic_holds', [
            'divisions' => $divisions,
            'all_provider' => $all_provider,
            'dasgs_provider' => $dasgs_provider,
            'dtve_provider' => $dtve_provider,
            'dte_provider' => $dte_provider,
            'dne_provider' => $dne_provider,
        ]);
    }
    
    
    /**
     * Returns listing of transfers and deferrals
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 09/09/2016
     * Date LAst Modified: 09/09/2016
     */
    public function actionViewTransfersAndDeferrals()
    {
        $transfers_data = NULL;
        $deferrals_data = NULL;
        
        $transfers_provider = array();
        $deferrals_provider = array();
        
        $transfer_info = array();
        $deferral_info = array();
        
        $transfers = StudentTransfer::find()
                ->where(['isdeleted' => 0])
                ->all();
        
        if($transfers)
        {
            foreach ($transfers as $transfer)
            {
                
                $transfer_info["studentregistrationid"] = $transfer->studentregistrationid;
                $transfer_info["personid"] = $transfer->personid;
                
                $user = User::find()
                        ->where(['personid' => $transfer->personid, 'isdeleted' => 0])
                        ->one();
                if ($user == false)
                    continue;
                $transfer_info["username"] = $user->username;
                
                $student = Student::find()
                        ->where(['personid' => $transfer->personid, 'isdeleted' => 0])
                        ->one();
                if ($student == false)
                    continue;
                $transfer_info["title"] = $student->title;
                $transfer_info["firstname"] = $student->firstname;
                $transfer_info["lastname"] = $student->lastname;
                
                $transfer_info["date"] = $transfer->transferdate;
                
                $offer_from = Offer::find()
                        ->where(['offerid' => $transfer->offerfrom, 'isdeleted' => 0])
                        ->one();
                if($offer_from == false)
                    continue;
                $transfer_info["offer_from_id"] = $offer_from->offerid;
                
                $previous_cape_subjects_names = array();
                $previous_cape_subjects = array();
                $previous_application = $offer_from->getApplication()->one();
                $previous_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $previous_application->getAcademicoffering()->one()->programmecatalogid]);
                $previous_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $previous_application->applicationid]);
                foreach ($previous_cape_subjects as $cs)
                { 
                    $previous_cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $transfer_info['previous_programme'] = empty($previous_cape_subjects) ? $previous_programme->getFullName() : $previous_programme->name . ": " . implode(' ,', $previous_cape_subjects_names);
           
                 $offer_to = Offer::find()
                        ->where(['offerid' => $transfer->offerto, 'isdeleted' => 0])
                        ->one();
                if($offer_to == false)
                    continue;
                $transfer_info["offer_to_id"] = $offer_to->offerid;
                $current_cape_subjects_names = array();                
                $current_cape_subjects = array();
                $current_application = $offer_to->getApplication()->one();
                $current_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $current_application->getAcademicoffering()->one()->programmecatalogid]);
                $current_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $current_application->applicationid]);
                foreach ($current_cape_subjects as $cs)
                { 
                    $current_cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $transfer_info['current_programme'] = empty($current_cape_subjects) ? $current_programme->getFullName() : $current_programme->name . ": " . implode(' ,', $current_cape_subjects_names);
                
                $transfer_info["transfer_officerid"] = $transfer->transferofficer;
                
                $employee_name = Employee::getEmployeeName($transfer->transferofficer);
                $transfer_info["transfer_officer_name"] = $employee_name;
                
                $transfers_data[] =  $transfer_info;
            }
            
            $transfers_provider = new ArrayDataProvider([
                    'allModels' => $transfers_data,
                    'pagination' => [
                        'pageSize' => 25,
                    ],
                    'sort' => [
                        'defaultOrder' => ['date' => SORT_DESC, 'lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                        'attributes' => ['username', 'firstname', 'lastname', 'date'],
                        ]
            ]); 
        }
        
        $deferrals = StudentDeferral::find()
                ->where(['isdeleted' => 0])
                ->all();
        if($deferrals)
        {
            foreach ($deferrals as $deferral)
            {
                
                $deferral_info["studentdeferralid"] = $deferral->studentdeferralid;
                $deferral_info["studentregistrationid"] = $deferral->registrationto;
                $deferral_info["personid"] = $deferral->personid;
                
                $user = User::find()
                        ->where(['personid' => $deferral->personid, 'isdeleted' => 0])
                        ->one();
                if ($user == false)
                    continue;
                $deferral_info["username"] = $user->username;
                
                $student = Student::find()
                        ->where(['personid' => $deferral->personid, 'isdeleted' => 0])
                        ->one();
                if ($student == false)
                    continue;
                $deferral_info["title"] = $student->title;
                $deferral_info["firstname"] = $student->firstname;
                $deferral_info["lastname"] = $student->lastname;
                
                $deferral_info["date"] = $deferral->deferraldate;
                $deferral_info["iscurrent"] = $deferral->isactive;
                
                $deferral_info["registration_from_id"] = $deferral->registrationfrom;
                $registration_from = StudentRegistration::find()
                        ->where(['studentregistrationid' => $deferral->registrationfrom, 'isdeleted' => 0])
                        ->one();
                if($registration_from == false)
                    continue;
                
                $previous_cape_subjects_names = array();
                $previous_cape_subjects = array();
                $previous_application = Offer::find()
                        ->where(['offerid' => $registration_from->offerid, 'isdeleted' => 0])
                        ->one()
                        ->getApplication()
                        ->one();
                $previous_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $previous_application->getAcademicoffering()->one()->programmecatalogid]);
                $previous_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $previous_application->applicationid]);
                foreach ($previous_cape_subjects as $cs)
                { 
                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $previous_programme_name = empty($previous_cape_subjects) ? $previous_programme->getFullName() : $previous_programme->name . ": " . implode(' ,', $cape_subjects_names);
                $deferral_info['previous_programme'] = $previous_programme_name;
                $previous_year = AcademicYear::find()
                        ->innerJoin('academic_offering', '`academic_year`.`academicyearid` = `academic_offering`.`academicyearid`')
                        ->where(['academic_year.isdeleted' => 0,
                                    'academic_offering.academicofferingid' => $previous_application->academicofferingid, 'academic_offering.isdeleted' => 0
                                    ])
                        ->one();
                if ($previous_year == false)
                    continue;
                $deferral_info["previous_year"] = $previous_year->title;
                $deferral_info["previous_year_programme"] = "(" . $previous_year->title . ") " .  $previous_programme_name;
                
                $registration_to = StudentRegistration::find()
                        ->where(['studentregistrationid' => $deferral->registrationto, 'isdeleted' => 0])
                        ->one();
                if($registration_to == false)
                    continue;
                $deferral_info["registration_to_id"] = $deferral->registrationto;
                $current_cape_subjects_names = array();                
                $current_cape_subjects = array();
                $current_application = Offer::find()
                        ->where(['offerid' => $registration_to->offerid, 'isdeleted' => 0])
                        ->one()
                        ->getApplication()
                        ->one();
                $current_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $current_application->getAcademicoffering()->one()->programmecatalogid]);
                $current_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $current_application->applicationid]);
                foreach ($current_cape_subjects as $cs)
                { 
                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $current_programme_name = empty($current_cape_subjects) ? $current_programme->getFullName() : $current_programme->name . ": " . implode(' ,', $cape_subjects_names);
                $deferral_info['current_programme'] = $current_programme_name;
                
                $deferral_info["deferral_officerid"] = $deferral->deferralofficer;
                
                $employee_name = Employee::getEmployeeName($deferral->deferralofficer);
                $deferral_info["deferral_officer_name"] = $employee_name;
                $current_year = AcademicYear::find()
                        ->innerJoin('academic_offering', '`academic_year`.`academicyearid` = `academic_offering`.`academicyearid`')
                        ->where(['academic_year.isdeleted' => 0,
                                    'academic_offering.academicofferingid' => $current_application->academicofferingid, 'academic_offering.isdeleted' => 0])
                        ->one();
                if ($current_year == false)
                    continue;
                $deferral_info["current_year"] = $current_year->title;
                $deferral_info["current_year_programme"] = "(" . $current_year->title . ") " .  $current_programme_name;
                
                $deferrals_data[] =  $deferral_info;
            }
            
            $deferrals_provider = new ArrayDataProvider([
                    'allModels' => $deferrals_data,
                    'pagination' => [
                        'pageSize' => 25,
                    ],
                    'sort' => [
                        'defaultOrder' => ['date' => SORT_DESC, 'lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                        'attributes' => ['username', 'firstname', 'lastname', 'date'],
                        ]
            ]); 
        }
       
        
        return $this->render('transfers_and_deferrals', [
            'transfers_provider' => $transfers_provider,
            'deferrals_provider' => $deferrals_provider
        ]);
    }
    
    
    /**
     * Exports Transfers Listing
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date CreatedL 21/09/2016
     * Date Last Modified: 21/09/2016
     */
    public function actionExportTransfers()
    {
        $transfers_data = NULL;
        $transfers_provider = array();
        $transfer_info = array();
        
        $transfers = StudentTransfer::find()
                ->where(['isdeleted' => 0])
                ->all();
        
        if($transfers)
        {
            foreach ($transfers as $transfer)
            {
                $transfer_info["studentregistrationid"] = $transfer->studentregistrationid;
                $transfer_info["personid"] = $transfer->personid;
                
                $user = User::find()
                        ->where(['personid' => $transfer->personid, 'isdeleted' => 0])
                        ->one();
                if ($user == false)
                    continue;
                $transfer_info["username"] = $user->username;
                
                $student = Student::find()
                        ->where(['personid' => $transfer->personid, 'isdeleted' => 0])
                        ->one();
                if ($student == false)
                    continue;
                $transfer_info["title"] = $student->title;
                $transfer_info["firstname"] = $student->firstname;
                $transfer_info["lastname"] = $student->lastname;
                
                $transfer_info["date"] = $transfer->transferdate;
                
                $offer_from = Offer::find()
                        ->where(['offerid' => $transfer->offerfrom, 'isdeleted' => 0])
                        ->one();
                if($offer_from == false)
                    continue;
                $transfer_info["offer_from_id"] = $offer_from->offerid;
                
                $previous_cape_subjects_names = array();
                $previous_cape_subjects = array();
                $previous_application = $offer_from->getApplication()->one();
                $previous_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $previous_application->getAcademicoffering()->one()->programmecatalogid]);
                $previous_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $previous_application->applicationid]);
                foreach ($previous_cape_subjects as $cs)
                { 
                    $previous_cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $transfer_info['previous_programme'] = empty($previous_cape_subjects) ? $previous_programme->getFullName() : $previous_programme->name . ": " . implode(' ,', $previous_cape_subjects_names);
           
                 $offer_to = Offer::find()
                        ->where(['offerid' => $transfer->offerto, 'isdeleted' => 0])
                        ->one();
                if($offer_to == false)
                    continue;
                $transfer_info["offer_to_id"] = $offer_to->offerid;
                $current_cape_subjects_names = array();                
                $current_cape_subjects = array();
                $current_application = $offer_to->getApplication()->one();
                $current_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $current_application->getAcademicoffering()->one()->programmecatalogid]);
                $current_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $current_application->applicationid]);
                foreach ($current_cape_subjects as $cs)
                { 
                    $current_cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $transfer_info['current_programme'] = empty($current_cape_subjects) ? $current_programme->getFullName() : $current_programme->name . ": " . implode(' ,', $current_cape_subjects_names);
                
                $transfer_info["transfer_officerid"] = $transfer->transferofficer;
                
                $employee_name = Employee::getEmployeeName($transfer->transferofficer);
                $transfer_info["transfer_officer_name"] = $employee_name;
                
                $transfers_data[] =  $transfer_info;
            }
            
            $transfers_provider = new ArrayDataProvider([
                    'allModels' => $transfers_data,
                    'pagination' => [
                        'pageSize' => 100,
                    ],
                    'sort' => [
                        'defaultOrder' => ['date' => SORT_DESC, 'lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                        'attributes' => ['username', 'firstname', 'lastname', 'date'],
                        ]
            ]); 
        }
               
        $title = "Title: Transfers";
        $date =  "  Date: " . date('Y-m-d') . "     ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = " Generator: " . Employee::getEmployeeName($employeeid);
        $filename = $title . $date . $generating_officer;
        
        return $this->renderPartial('export_transfers', [
            'dataProvider' => $transfers_provider,
            'filename' => $filename,
        ]);
    }
    
    
    /**
     * Exports Deferrals Listing
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date CreatedL 21/09/2016
     * Date Last Modified: 21/09/2016
     */
    public function actionExportDeferrals()
    {
        $deferrals_data = NULL;
        $deferrals_provider = array();
        $deferral_info = array();
        
        $deferrals = StudentDeferral::find()
                ->where(['isdeleted' => 0])
                ->all();
        if($deferrals)
        {
            foreach ($deferrals as $deferral)
            {
                
                $deferral_info["studentdeferralid"] = $deferral->studentdeferralid;
                $deferral_info["studentregistrationid"] = $deferral->registrationto;
                $deferral_info["personid"] = $deferral->personid;
                
                $user = User::find()
                        ->where(['personid' => $deferral->personid, 'isdeleted' => 0])
                        ->one();
                if ($user == false)
                    continue;
                $deferral_info["username"] = $user->username;
                
                $student = Student::find()
                        ->where(['personid' => $deferral->personid, 'isdeleted' => 0])
                        ->one();
                if ($student == false)
                    continue;
                $deferral_info["title"] = $student->title;
                $deferral_info["firstname"] = $student->firstname;
                $deferral_info["lastname"] = $student->lastname;
                
                $deferral_info["date"] = $deferral->deferraldate;
                $deferral_info["iscurrent"] = $deferral->isactive;
                
                $deferral_info["registration_from_id"] = $deferral->registrationfrom;
                $registration_from = StudentRegistration::find()
                        ->where(['studentregistrationid' => $deferral->registrationfrom, 'isdeleted' => 0])
                        ->one();
                if($registration_from == false)
                    continue;
                
                $previous_cape_subjects_names = array();
                $previous_cape_subjects = array();
                $previous_application = Offer::find()
                        ->where(['offerid' => $registration_from->offerid, 'isdeleted' => 0])
                        ->one()
                        ->getApplication()
                        ->one();
                $previous_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $previous_application->getAcademicoffering()->one()->programmecatalogid]);
                $previous_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $previous_application->applicationid]);
                foreach ($previous_cape_subjects as $cs)
                { 
                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $previous_programme_name = empty($previous_cape_subjects) ? $previous_programme->getFullName() : $previous_programme->name . ": " . implode(' ,', $cape_subjects_names);
                $deferral_info['previous_programme'] = $previous_programme_name;
                $previous_year = AcademicYear::find()
                        ->innerJoin('academic_offering', '`academic_year`.`academicyearid` = `academic_offering`.`academicyearid`')
                        ->where(['academic_year.isdeleted' => 0,
                                    'academic_offering.academicofferingid' => $previous_application->academicofferingid, 'academic_offering.isdeleted' => 0
                                    ])
                        ->one();
                if ($previous_year == false)
                    continue;
                $deferral_info["previous_year"] = $previous_year->title;
                $deferral_info["previous_year_programme"] = "(" . $previous_year->title . ") " .  $previous_programme_name;
                
                $registration_to = StudentRegistration::find()
                        ->where(['studentregistrationid' => $deferral->registrationto, 'isdeleted' => 0])
                        ->one();
                if($registration_to == false)
                    continue;
                $deferral_info["registration_to_id"] = $deferral->registrationto;
                $current_cape_subjects_names = array();                
                $current_cape_subjects = array();
                $current_application = Offer::find()
                        ->where(['offerid' => $registration_to->offerid, 'isdeleted' => 0])
                        ->one()
                        ->getApplication()
                        ->one();
                $current_programme = ProgrammeCatalog::findOne(['programmecatalogid' => $current_application->getAcademicoffering()->one()->programmecatalogid]);
                $current_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $current_application->applicationid]);
                foreach ($current_cape_subjects as $cs)
                { 
                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $current_programme_name = empty($current_cape_subjects) ? $current_programme->getFullName() : $current_programme->name . ": " . implode(' ,', $cape_subjects_names);
                $deferral_info['current_programme'] = $current_programme_name;
                
                $deferral_info["deferral_officerid"] = $deferral->deferralofficer;
                
                $employee_name = Employee::getEmployeeName($deferral->deferralofficer);
                $deferral_info["deferral_officer_name"] = $employee_name;
                $current_year = AcademicYear::find()
                        ->innerJoin('academic_offering', '`academic_year`.`academicyearid` = `academic_offering`.`academicyearid`')
                        ->where(['academic_year.isdeleted' => 0,
                                    'academic_offering.academicofferingid' => $current_application->academicofferingid, 'academic_offering.isdeleted' => 0])
                        ->one();
                if ($current_year == false)
                    continue;
                $deferral_info["current_year"] = $current_year->title;
                $deferral_info["current_year_programme"] = "(" . $current_year->title . ") " .  $current_programme_name;
                
                $deferrals_data[] =  $deferral_info;
            }
            
            $deferrals_provider = new ArrayDataProvider([
                    'allModels' => $deferrals_data,
                    'pagination' => [
                        'pageSize' => 100,
                    ],
                    'sort' => [
                        'defaultOrder' => ['date' => SORT_DESC, 'lastname' => SORT_ASC, 'firstname' => SORT_ASC],
                        'attributes' => ['username', 'firstname', 'lastname', 'date'],
                        ]
            ]); 
        }
       
        
        $title = "Title: Deferrals";
        $date =  "  Date: " . date('Y-m-d') . "     ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = " Generator: " . Employee::getEmployeeName($employeeid);
        $filename = $title . $date . $generating_officer;
        
        return $this->renderPartial('export_deferrals', [
            'dataProvider' => $deferrals_provider,
            'filename' => $filename,
        ]);
    }
    
    
    
    
}
