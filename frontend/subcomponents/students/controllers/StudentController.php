<?php

namespace app\subcomponents\students\controllers;

use yii\web\Controller;

use Yii;
use frontend\models\Division;
use frontend\models\Student;
use common\models\User;
use yii\data\ArrayDataProvider;

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
        //$division = Division::findOne(['divisionid' => $divisionid ]);
        
        $students = Student::find()
                ->innerJoin('student_registration', '`student`.`personid` = `student_registration`.`personid`')
                ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `student_registration`.`academicofferingid`')
                ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where(['student.isdeleted' => 0, 'student.isactive' => 1, 'student_registration.isdeleted' => 0, 'student_registration.isactive' => 1, 
                    'application_period.divisionid' => $divisionid])
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
                 $info_string = $info_string .  " Student ID: " . $app_id;
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
}
