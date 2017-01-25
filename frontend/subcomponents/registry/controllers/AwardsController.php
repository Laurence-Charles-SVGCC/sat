<?php

    namespace app\subcomponents\registry\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\helpers\Url;
    use yii\data\ArrayDataProvider;

    use common\models\User;
    use frontend\models\Award;
    use frontend\models\PersonAward;

    use frontend\models\Division;
    use frontend\models\Employee;
    use frontend\models\Student;
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


    class AwardsController extends Controller
    {

        /**
         * Renders Award Control Panel
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 25/04/2016
         * Date Last Modified: 25/04/2016
         */
        public function actionManageAwards()
        {
            $awards = Award::find()
                    ->where(['isactive' => 1, 'isdeleted' => 0])
                    ->all();
            
            return $this->render('awards_panel',
                    [
                        'awards' => $awards,
                    ]);
        }


        /**
         * Creates/Edits an 'award'
         * 
         * @param type $action
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 25/04/2016
         * Date Last Modified: 25/04/2016
         */
        public function actionConfigureAward($action, $recordid = NULL)
        {
            $load_flag = false;
            $save_flag = false;
            $academicyears =  AcademicYear::getYearListing(); 
            
            if ($action == "create")
            {
                $award = new Award();
                $action = "Create";
            }
            else
            {
                $award = Award::find()
                        ->where(['awardid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                $action = "Edit";
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = $award->load($post_data);
                $save_flag = $award->save();
                if ($save_flag == true)
                    return self::actionManageAwards();
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when saving award configuration. Please try again.');
                }
            }
            
            return $this->render('configure_award',
                    [
                        'award' => $award,
                        'action' => $action,
                        'academicyears' => $academicyears,
                    ]);
        }
        
        
        /**
         * Deletes an award
         * 
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 25/04/2016
         * Date Last Modified: 25/04/2016
         */
        public function actionDeleteAward($recordid)
        {
            $save_flag = false;
            $award = Award::find()
                        ->where(['awardid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            if($award)
            {
                $award->isactive = 0;
                $award->isdeleted = 1;
                $save_flag = $award->save();
                if ($save_flag == false)
                    Yii::$app->getSession()->setFlash('error', 'Error occured when deleting award record.');
            }
            else
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when retrieving award record.');
            }
            
            return self::actionManageAwards();
        }
        
        
        /**
         * Renders awardee listing
         * 
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 25/04/2016
         * Date Last Modified: 25/04/2016
         */
        public function actionViewAwardees($recordid)
        {
            $award = Award::find()
                        ->where(['awardid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            $assignments = Award::getAwardees($recordid);
            $awardees = array();
            
            $keys = array();
            array_push($keys, 'personid');
            array_push($keys, 'studentregistrationid');
            array_push($keys, 'username');
            array_push($keys, 'title');
            array_push($keys, 'firstname');
            array_push($keys, 'lastname');
            array_push($keys, 'dateawarded');
            
            if($award && $assignments)
            {
                foreach($assignments as $assignment)
                {
                    $person = User::find()
                            ->where(['personid' => $assignment->personid])
                            ->one();
                    $student = Student::find()
                            ->where(['personid' => $assignment->personid])
                            ->one();
                    
                    $values = array();
                    $row = array();

                    array_push($values, $student->personid);
                    array_push($values, $assignment->studentregistrationid);
                    array_push($values, $person->username);
                    array_push($values, $student->title);
                    array_push($values, $student->firstname);
                    array_push($values, $student->lastname);
                    array_push($values, $assignment->dateawarded);
                   
                    $row = array_combine($keys, $values);
                    array_push($awardees, $row);

                    $values = NULL;
                    $row = NULL;
                }
                
                return $this->render('view_awardees',
                    [
                        'awardees' => $awardees,
                        'award' => $award,
                    ]);
            }
            else
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when retrieving records.');
            }
        }


       
    }

