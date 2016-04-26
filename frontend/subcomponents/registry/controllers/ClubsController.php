<?php

    namespace app\subcomponents\registry\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\helpers\Url;
    use yii\data\ArrayDataProvider;
    use yii\base\Model;

    use frontend\models\Club;
    use frontend\models\ClubDivision;
    
    use frontend\models\Division;
    use frontend\models\Employee;
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


    class ClubsController extends Controller
    {

        /**
         * Renders Club Control Panel
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 26/04/2016
         * Date Last Modified: 26/04/2016
         */
        public function actionManageClubs()
        {
            $clubs = Club::find()
                    ->where(['isactive' => 1, 'isdeleted' => 0])
                    ->all();
            
            return $this->render('clubs_panel',
                    [
                        'clubs' => $clubs,
                    ]);
        }
        
        
        /**
         * Creates/Edits an 'club'
         * 
         * @param type $action
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 26/04/2016
         * Date Last Modified: 26/04/2016
         */
        public function actionConfigureClub($action, $recordid = NULL)
        {
            $load_flag = false;
            $save_flag = false;
            $clubdivision_load_flag = false;
            $clubdivision_save_flag = false;
            
            if ($action == "create")
            {
                $club = new Club();
                $action = "Create";
            }
            else
            {
                $club = Club::find()
                        ->where(['clubid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                $action = "Edit";
            }
           
            $club_division = new ClubDivision();
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = $club->load($post_data);
                $clubdivision_load_flag = $club_division->load($post_data);
                        
                if ($load_flag == false  || $clubdivision_load_flag == false)
                    Yii::$app->getSession()->setFlash('error', 'Error occured when loading form. Please try again.');
                
                elseif ($load_flag == true  && $clubdivision_load_flag == true) 
                {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try 
                    {
                        $save_flag = $club->save();
                        if ($save_flag == false)
                        {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error occured when saving club record. Please try again.');
                        }
                        else
                        {
                            $club_division->clubid = $club->clubid;
                            $clubdivision_save_flag = $club_division->save();
                            if ($clubdivision_save_flag == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('error', 'Error occured when saving club_division record. Please try again.');
                            }
                            
                            $transaction->commit();
                            return self::actionManageClubs();
                        }
                    } catch (Exception $e) {
                    $transaction->rollBack();
                    }
                }
            }
            
            return $this->render('configure_club',
                    [
                        'club' => $club,
                        'clubdivision' => $club_division,
                        'action' => $action,
                    ]);
        }
        
        
        /**
         * Deletes an club
         * 
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 26/04/2016
         * Date Last Modified: 26/04/2016
         */
        public function actionDeleteClub($recordid)
        {
            $save_flag = false;
            $club = Club::find()
                        ->where(['clubid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            if($club)
            {
                $club->isactive = 0;
                $club->isdeleted = 1;
                $save_flag = $club->save();
                if ($save_flag == false)
                    Yii::$app->getSession()->setFlash('error', 'Error occured when deleting club record.');
            }
            else
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when retrieving club record.');
            }
            
            return self::actionManageClubs();
        }

    }

