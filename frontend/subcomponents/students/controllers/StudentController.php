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
    * Last Modified: 1/08/2015 by Gamal Crichton
    */
    public function actionSearchStudent()
    {
        $dataProvider = NULL;
        $info_string = "";
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            $stu_id = $request->post('id');
            $firstname = $request->post('firstname');
            $lastname = $request->post('lastname');
            $email = $request->post('email');
            
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
    * Last Modified: 1/08/2015 by Gamal Crichton
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
        foreach($registrations as $registration)
        {
            $reg_details = array();
            $acad_off = $registration->getAcademicoffering()->one();
            $cape_subjects_names = array();
            $programme = $acad_off ? $acad_off->getProgrammeCatalog()->one() : NULL;
            $application = Application::find()
                    ->innerJoin('offer', '`offer`.`applicationid` = `application`.`applicationid`')
                    ->where(['personid' => $personid, 'application.academicofferingid' => $acad_off->academicofferingid])
                    ->one();
            $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
            foreach ($cape_subjects as $cs) { $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; }
            
            $reg_details['order'] = $application->ordering;
            $reg_details['applicationid'] = $application->applicationid;
            $reg_details['programme_name'] = $cape_subjects ? "CAPE: " . implode(' ,', $cape_subjects_names) : $programme->getFullName();
            $reg_details['active'] = $registration->isactive;
            $reg_details['divisionid'] = $application->divisionid;

            $data[] = $reg_details;
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
}
