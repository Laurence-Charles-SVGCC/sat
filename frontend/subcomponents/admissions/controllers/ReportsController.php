<?php

namespace app\subcomponents\admissions\controllers;

use Yii;
use yii\web\Controller;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

use common\models\User;
use frontend\models\ApplicationPeriod;
use frontend\models\AcademicYear;
use frontend\models\ProgrammeCatalog;
use frontend\models\AcademicOffering;
use frontend\models\CapeSubject;
use frontend\models\Subject;
use frontend\models\CapeGroup;
use frontend\models\CapeSubjectGroup;
use frontend\models\EmployeeDepartment;
use frontend\models\Email;
use frontend\models\Applicant;
use frontend\models\Application;
use frontend\models\CsecQualification;
use frontend\models\Employee;
use frontend\models\ExaminationGrade;
use frontend\models\ApplicationCapesubject;
use frontend\models\Offer;
use frontend\models\StudentRegistration;

class ReportsController extends Controller {

    /**
     * Renders page for report selection
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 13/05/2016
     * Date Last Modified: 13/05/2016
     */
    public function actionIndex() {

        $periods = ApplicationPeriod::preparePeriods();

        return $this->render('find_applicants', [
                    'periods' => $periods,
        ]);
    }

    /**
     * Generate programme/capesubject listing
     * 
     * @param type $applicationperiodid
     * @param type $listing_type
     * 
     * Author: Laurence Charles
     * Date Created: 13/05/2016
     * Date Last Modified: 13/05/2016
     */
    public function actionGetListing($applicationperiodid, $listing_type) 
    {
        if ($listing_type == 1)     //if associate programme not for DASGS selected
        {    
            $records = AcademicOffering::find()
                    ->where(['applicationperiodid' => $applicationperiodid, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();

            $listing = array();
            foreach ($records as $record) 
            {
                $combined = array();
                $keys = array();
                $values = array();
                array_push($keys, "id");
                array_push($keys, "name");
                $k1 = strval($record->academicofferingid);
                $name = ProgrammeCatalog::getProgrammeName($record->academicofferingid);
                $k2 = strval($name);
                array_push($values, $k1);
                array_push($values, $k2);
                $combined = array_combine($keys, $values);
                array_push($listing, $combined);
                $combined = NULL;
                $keys = NULL;
                $values = NULL;
            }
        } 
        elseif ($listing_type == 2)     //if all programmes for DASGS selected
        {    
            $records = AcademicOffering::find()
                    ->where(['applicationperiodid' => $applicationperiodid, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();

            $listing = array();
            foreach ($records as $record) 
            {
                $combined = array();
                $keys = array();
                $values = array();
                array_push($keys, "id");
                array_push($keys, "name");
                $k1 = strval($record->academicofferingid);
                $name = ProgrammeCatalog::getProgrammeName($record->academicofferingid);
                $k2 = strval($name);
                array_push($values, $k1);
                array_push($values, $k2);
                $combined = array_combine($keys, $values);
                array_push($listing, $combined);
                $combined = NULL;
                $keys = NULL;
                $values = NULL;
            }
        } 
        elseif ($listing_type == 3)     //if associate programme for DASGS selected
        {    
            $records = AcademicOffering::find()
                    ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                    ->where(['academic_offering.applicationperiodid' => $applicationperiodid, 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0])
                    ->andWhere(['not', ['programme_catalog.name' => 'CAPE']])
                    ->all();

            $listing = array();
            foreach ($records as $record) 
            {
                $combined = array();
                $keys = array();
                $values = array();
                array_push($keys, "id");
                array_push($keys, "name");
                $k1 = strval($record->academicofferingid);
                $name = ProgrammeCatalog::getProgrammeName($record->academicofferingid);
                $k2 = strval($name);
                array_push($values, $k1);
                array_push($values, $k2);
                $combined = array_combine($keys, $values);
                array_push($listing, $combined);
                $combined = NULL;
                $keys = NULL;
                $values = NULL;
            }
        } 
        elseif ($listing_type == 4)     //if CAPE subjects selected
        {    
            $records = CapeSubject::find()
                    ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `cape_subject`.`academicofferingid`')
                    ->where(['academic_offering.applicationperiodid' => $applicationperiodid, 'cape_subject.isactive' => 1, 'cape_subject.isdeleted' => 0])
                    ->all();

            $listing = array();
            foreach ($records as $record) 
            {
                $combined = array();
                $keys = array();
                $values = array();
                array_push($keys, "id");
                array_push($keys, "name");
                $k1 = strval($record->capesubjectid);
                $subject = CapeSubject::find()
                        ->where(['capesubjectid' => $record->capesubjectid])
                        ->one();
                $k2 = strval($subject->subjectname);
                array_push($values, $k1);
                array_push($values, $k2);
                $combined = array_combine($keys, $values);
                array_push($listing, $combined);
                $combined = NULL;
                $keys = NULL;
                $values = NULL;
            }
        }

        if ($listing) 
        {
            $found = 1;
            echo Json::encode(['found' => $found, 'listingtype' => $listing_type, 'programmes' => $listing]);
        } 
        else 
        {
            $found = 0;
            echo Json::encode(['found' => $found]);
        }
    }

    /**
     * Generates reports for Borderline applicants based on maths/english subjects
     * 
     * @param type $passmaths
     * @param type $passenglish
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 13/05/2016
     * Date Last Modified: 13/05/2016
     */
    public function actionBorderline($passmaths, $passenglish) 
    {
        $dataProvider = NULL;
        $data = array();

        if (Yii::$app->request->post()) 
        {
            $request = Yii::$app->request;
            
            $application_periodid = $request->post('period');
            if (!$application_periodid)
                $application_periodid = Yii::$app->session->get('application_periodid');
            
            Yii::$app->session->set('application_periodid', $application_periodid);
        } 
        else 
        {
            $application_periodid = Yii::$app->session->get('application_periodid');
            Yii::$app->session->set('application_periodid', $application_periodid);
        }

        if ($application_periodid != 0) 
        {
            $divisionid = ApplicationPeriod::find()
                            ->where(['applicationperiodid' => $application_periodid])
                            ->one()
                    ->divisionid;

            $applicants = Applicant::find()
                    ->innerJoin('application', '`applicant`.`applicant.personid` = `application`.`personid`')
                    ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->where(['application_period.isactive' => 1, 'academic_offering.applicationperiodid' => $application_periodid,
                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                        'application.isactive' => 1, 'application.isdeleted' => 0, 'application.applicationstatusid' => 7
                    ])
                    ->groupby('applicant.personid')
                    ->orderBy('applicant.lastname ASC')
                    ->all();

            foreach ($applicants as $applicant) 
            {
                $qualifications = CsecQualification::find()
                        ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0])
                        ->all();

                $secondary_passes = 0;
                $tertiary_passes = 0;
                foreach ($qualifications as $qualification) 
                {
                    $exam_grade = ExaminationGrade::find()->where(['examinationgradeid' => $qualification->examinationgradeid])->one();
                    if (($qualification->examinationbodyid == 3 || $qualification->examinationbodyid == 5) && in_array($exam_grade->ordering, array(1, 2, 3)))
                        $secondary_passes++;
                    elseif ($qualification->examinationbodyid == 2 && in_array($exam_grade->ordering, array(1, 2, 3)))
                        $tertiary_passes++;
                }

                $pass_maths = CsecQualification::hasEnglish($qualifications) ? 1 : 0;
                $pass_english = CsecQualification::hasMath($qualifications) ? 1 : 0;

                if ($pass_maths == $passmaths && $pass_english == $passenglish) 
                {
                    $username = User::findOne(['personid' => $applicant->personid, 'isdeleted' => 0])->username;

                    $applications = Application::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                            ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                            ->where(['application_period.isactive' => 1, 'academic_offering.applicationperiodid' => $application_period_id,
                                'application.isactive' => 1, 'application.isdeleted' => 0, 'application.personid' => $applicant->personid
                            ])
                            ->all();

                    $first_programme = "N/A";
                    $first_choice = Application::find()
                            ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0, 'ordering' => 1])
                            ->one();
                    if ($first_choice) {
                        $programme = ProgrammeCatalog::find()
                                ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                                ->where(['academicofferingid' => $first_choice->academicofferingid])
                                ->one();
                        $cape_subjects1 = ApplicationCapesubject::findAll(['applicationid' => $first_choice->applicationid]);
                        foreach ($cape_subjects1 as $cs) 
                        {
                            $cape_subjects_names1[] = $cs->getCapesubject()->one()->subjectname;
                        }
                        $first_programme = empty($cape_subjects1) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names1);
                    }

                    $second_programme = "N/A";
                    $second_choice = Application::find()
                            ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0, 'ordering' => 2])
                            ->one();
                    if ($second_choice) 
                    {
                        $programme = ProgrammeCatalog::find()
                                ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                                ->where(['academicofferingid' => $second_choice->academicofferingid])
                                ->one();
                        $cape_subjects2 = ApplicationCapesubject::findAll(['applicationid' => $second_choice->applicationid]);
                        foreach ($cape_subjects2 as $cs) 
                        {
                            $cape_subjects_names2[] = $cs->getCapesubject()->one()->subjectname;
                        }
                        $second_programme = empty($cape_subjects2) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names2);
                    }

                    $third_programme = "N/A";
                    $third_choice = Application::find()
                            ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0, 'ordering' => 3])
                            ->one();
                    if ($third_choice) 
                    {
                        $programme = ProgrammeCatalog::find()
                                ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                                ->where(['academicofferingid' => $third_choice->academicofferingid])
                                ->one();
                        $cape_subjects3 = ApplicationCapesubject::findAll(['applicationid' => $third_choice->applicationid]);
                        foreach ($cape_subjects3 as $cs) 
                        {
                            $cape_subjects_names3[] = $cs->getCapesubject()->one()->subjectname;
                        }
                        $third_programme = empty($cape_subjects3) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names3);
                    }

                    $info = array();
                    $info['personid'] = $applicant->personid;
                    $info['applicantid'] = $applicant->applicantid;
                    $info['username'] = $username;
                    $info['title'] = $applicant->title;
                    $info['firstname'] = $applicant->firstname;
                    $info['middlename'] = $applicant->middlename;
                    $info['lastname'] = $applicant->lastname;
                    $info['firstchoice'] = $first_programme;
                    $info['secondchoice'] = $second_programme;
                    $info['thirdchoice'] = $third_programme;
                    $info['secondarysubjects'] = count(CsecQualification::find()->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0, 'examinationbodyid' => [3, 5]])->all());
                    $info['secondarypasses'] = $secondary_passes;
                    $info['ones'] = CsecQualification::getSecondaryGradesCount($applicant->personid, 1);
                    $info['twos'] = CsecQualification::getSecondaryGradesCount($applicant->personid, 2);
                    $info['threes'] = CsecQualification::getSecondaryGradesCount($applicant->personid, 3);
                    $info['tertiarysubjects'] = count(CsecQualification::find()->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0, 'examinationbodyid' => 2])->all());
                    $info['tertiarypasses'] = $tertiary_passes;
                    $info['1st'] = CsecQualification::getTertiaryGradesCount($applicant->personid, 1);
                    $info['2nd'] = CsecQualification::getTertiaryGradesCount($applicant->personid, 2);
                    $info['3rd'] = CsecQualification::getTertiaryGradesCount($applicant->personid, 3);

                    $data[] = $info;

                    $cape_subjects1 = NULL;
                    $cape_subjects2 = NULL;
                    $cape_subjects3 = NULL;
                    $cape_subjects_names1 = NULL;
                    $cape_subjects_names2 = NULL;
                    $cape_subjects_names3 = NULL;
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        if($url)
            $platform = "local";
        else
            $platform = "live";
            
        if ($passmaths == 1 && $passenglish == 1)
            $header = "Bordeline Students with passes Mathematics and English Language";
        elseif ($passmaths == 1 && $passenglish == 0)
            $header = "Bordeline Students who pass Mathematics but fail English Language";
        elseif ($passmaths == 0 && $passenglish == 1)
            $header = "Bordeline Students who fail Mathematics but pass English Language";

        $title = "Title: " . $header . "   ";
        $date = "Date Generated: " . date('Y-m-d') . "   ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = "Generated By: " . Employee::getEmployeeName($employeeid);
        $filename = $title . $date . $generating_officer;

        return $this->render('applicant_listing', [
                    'dataProvider' => $dataProvider,
                    'header' => $header,
                    'filename' => $filename,
        ]);
    }

    /**
     * Generate a full applicant list for Application Period
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 13/05/2016
     * Date Last Modified: 13/05/2016
     */
    public function actionGenerateApplicantListing() 
    {
        $dataProvider = NULL;
        $data = array();

        if (Yii::$app->request->post()) 
        {
            $request = Yii::$app->request;
            
            $programmeid = NULL;
            $criteria = NULL;
            
            $application_periodid = $request->post('period');
            if (!$application_periodid)
                $application_periodid = Yii::$app->session->get('application_periodid');

            $prog1 = $request->post('prog1') ? $request->post('prog1') : 0;
            $prog2 = $request->post('prog2') ? $request->post('prog2') : 0;
            $prog3 = $request->post('prog3') ? $request->post('prog3') : 0;
            $prog4 = $request->post('prog4') ? $request->post('prog4') : 0;

            if ($prog1 != 0) 
            {
                $programmeid = $prog1;
                $criteria = "associate";
            } 
            elseif ($prog2 != 0) 
            {
                $programmeid = $prog2;
                $criteria = "associate";
            } 
            elseif ($prog3 != 0) 
            {
                $programmeid = $prog3;
                $criteria = "cape";
            } 
            elseif ($prog4 != 0) 
            {
                $programmeid = $prog4;
                $criteria = "associate";
            }
            
            if(!$programmeid)
                $programmeid = Yii::$app->session->get('programmeid');
            
            if(!$criteria)
                $criteria = Yii::$app->session->get('criteria');

            Yii::$app->session->set('application_periodid', $application_periodid);
            Yii::$app->session->set('programmeid', $programmeid);
            Yii::$app->session->set('criteria', $criteria);
        } 
        else 
        {
            $application_periodid = Yii::$app->session->get('application_periodid');
            $programmeid = Yii::$app->session->get('programmeid');
            $criteria = Yii::$app->session->get('criteria');
            

            Yii::$app->session->set('application_periodid', $application_periodid);
            Yii::$app->session->set('programmeid', $programmeid);
            Yii::$app->session->set('criteria', $criteria);
        }

        if ($application_periodid != 0) 
        {
            $divisionid = ApplicationPeriod::find()
                            ->where(['applicationperiodid' => $application_periodid])
                            ->one()
                    ->divisionid;

            $cond = array();
            $cond['application.isactive'] = 1;
            $cond['application.isdeleted'] = 0;
            $cond['academic_offering.isactive'] = 1;
            $cond['academic_offering.isdeleted'] = 0;
            $cond['academic_offering.applicationperiodid'] = $application_periodid;
            $cond['application_period.isactive'] = 1;
            $cond['application_period.isdeleted'] = 0;
            
            
            if ($criteria == "associate") 
            {
                $cond['application.academicofferingid'] = $programmeid;
                
                $applicants = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->where($cond)
                        ->andWhere(['>=', 'application.applicationstatusid', 3])
                        ->groupby('applicant.personid')
                        ->orderBy('applicant.lastname ASC')
                        ->all();
            } 
            elseif ($criteria == "cape") 
            {
                $offeringid = AcademicOffering::getCapeID($application_periodid);
                
                $cond['academic_offering.academicofferingid'] = $offeringid;
                $cond['application_capesubject.capesubjectid'] = $programmeid;
                $cond['application_capesubject.isactive'] = 1;
                $cond['application_capesubject.isdeleted'] = 0;
                
                $applicants = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->innerJoin('application_capesubject', '`application`.`applicationid` = `application_capesubject`.`applicationid`')
                        ->where($cond)
                        ->andWhere(['>=', 'application.applicationstatusid', 3])
                        ->groupby('applicant.personid')
                        ->orderBy('applicant.lastname ASC')
                        ->all();
            }


            foreach ($applicants as $applicant) {
                $qualifications = CsecQualification::find()
                        ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0])
                        ->all();

                $secondary_passes = 0;
                $tertiary_passes = 0;
                foreach ($qualifications as $qualification) {
                    $exam_grade = ExaminationGrade::find()->where(['examinationgradeid' => $qualification->examinationgradeid])->one();
                    if (($qualification->examinationbodyid == 3 || $qualification->examinationbodyid == 5) && in_array($exam_grade->ordering, array(1, 2, 3)))
                        $secondary_passes++;
                    elseif ($qualification->examinationbodyid == 2 && in_array($exam_grade->ordering, array(1, 2, 3)))
                        $tertiary_passes++;
                }


                $username = User::findOne(['personid' => $applicant->personid, 'isdeleted' => 0])->username;

                $applications = Application::find()
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->where(['application_period.isactive' => 1, 'academic_offering.applicationperiodid' => $application_periodid,
                            'application.isactive' => 1, 'application.isdeleted' => 0, 'application.personid' => $applicant->personid
                        ])
                        ->all();

                $first_programme = "N/A";
                $first_choice = Application::find()
                        ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0, 'ordering' => 1])
                        ->one();
                if ($first_choice) {
                    $programme = ProgrammeCatalog::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                            ->where(['academicofferingid' => $first_choice->academicofferingid])
                            ->one();
                    $cape_subjects1 = ApplicationCapesubject::findAll(['applicationid' => $first_choice->applicationid]);
                    foreach ($cape_subjects1 as $cs) {
                        $cape_subjects_names1[] = $cs->getCapesubject()->one()->subjectname;
                    }
                    $first_programme = empty($cape_subjects1) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names1);
                }

                $second_programme = "N/A";
                $second_choice = Application::find()
                        ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0, 'ordering' => 2])
                        ->one();
                if ($second_choice) {
                    $programme = ProgrammeCatalog::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                            ->where(['academicofferingid' => $second_choice->academicofferingid])
                            ->one();
                    $cape_subjects2 = ApplicationCapesubject::findAll(['applicationid' => $second_choice->applicationid]);
                    foreach ($cape_subjects2 as $cs) {
                        $cape_subjects_names2[] = $cs->getCapesubject()->one()->subjectname;
                    }
                    $second_programme = empty($cape_subjects2) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names2);
                }

                $third_programme = "N/A";
                $third_choice = Application::find()
                        ->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0, 'ordering' => 3])
                        ->one();
                if ($third_choice) {
                    $programme = ProgrammeCatalog::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                            ->where(['academicofferingid' => $third_choice->academicofferingid])
                            ->one();
                    $cape_subjects3 = ApplicationCapesubject::findAll(['applicationid' => $third_choice->applicationid]);
                    foreach ($cape_subjects3 as $cs) {
                        $cape_subjects_names3[] = $cs->getCapesubject()->one()->subjectname;
                    }
                    $third_programme = empty($cape_subjects3) ? $programme->getFullName() : $programme->name . ": " . implode(' ,', $cape_subjects_names3);
                }

                $info = array();
                $info['personid'] = $applicant->personid;
                $info['applicantid'] = $applicant->applicantid;
                $info['username'] = $username;
                $info['title'] = $applicant->title;
                $info['firstname'] = $applicant->firstname;
                $info['middlename'] = $applicant->middlename;
                $info['lastname'] = $applicant->lastname;
                $info['firstchoice'] = $first_programme;
                $info['secondchoice'] = $second_programme;
                $info['thirdchoice'] = $third_programme;
                $info['secondarysubjects'] = count(CsecQualification::find()->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0, 'examinationbodyid' => [3, 5]])->all());
                $info['secondarypasses'] = $secondary_passes;
                $info['ones'] = CsecQualification::getSecondaryGradesCount($applicant->personid, 1);
                $info['twos'] = CsecQualification::getSecondaryGradesCount($applicant->personid, 2);
                $info['threes'] = CsecQualification::getSecondaryGradesCount($applicant->personid, 3);
                $info['tertiarysubjects'] = count(CsecQualification::find()->where(['personid' => $applicant->personid, 'isactive' => 1, 'isdeleted' => 0, 'examinationbodyid' => 2])->all());
                $info['tertiarypasses'] = $tertiary_passes;
                $info['1st'] = CsecQualification::getTertiaryGradesCount($applicant->personid, 1);
                $info['2nd'] = CsecQualification::getTertiaryGradesCount($applicant->personid, 2);
                $info['3rd'] = CsecQualification::getTertiaryGradesCount($applicant->personid, 3);

                $data[] = $info;

                $cape_subjects1 = NULL;
                $cape_subjects2 = NULL;
                $cape_subjects3 = NULL;
                $cape_subjects_names1 = NULL;
                $cape_subjects_names2 = NULL;
                $cape_subjects_names3 = NULL;
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $periodname = ApplicationPeriod::find()
                        ->where(['applicationperiodid' => $application_periodid])
                        ->one()
                ->name;


        if ($programmeid == 0)
            $header = $periodname . " Full Applicant Listing";
        else {
            if ($criteria == "cape")
            {
                $subject = CapeSubject::find()
                        ->where(['capesubjectid' => $programmeid])
                        ->one()
                        ->subjectname;
                $header = $periodname . "   " . $subject;
            } 
            elseif ($criteria == "associate") 
            {
                $search_programme = ProgrammeCatalog::find()
                        ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                        ->where(['academicofferingid' => $programmeid])
                        ->one()
                        ->getFullName();
                $header = $periodname . "   " . $search_programme;
            }
        }

        $title = "Title: " . $header;
        $date = "Date Generated: " . date('Y-m-d') . "   ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = "Generated By: " . Employee::getEmployeeName($employeeid);
        $filename = $title . $date . $generating_officer;

        return $this->render('applicant_listing', [
                    'dataProvider' => $dataProvider,
                    'header' => $header,
                    'filename' => $filename,
                    'application_periodid' => $application_periodid,
                    'programmeid' => $programmeid,
                    'criteria' => $criteria,
        ]);
    }
    
    
    /**
     * Render index screen for unregistered applicants
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 13/05/2016
     * Date Last Modified: 13/05/2016
     */
    public function actionFindUnregisteredApplicants()
    {
        $periods = ApplicationPeriod::preparePeriods();
        
        return $this->render('find_unregistered', 
                            [
                                'periods' => $periods,
                            ]
        );
    }
    
    
    /**
     * Prepares and renders 'Unregister-Applicant" listing
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 13/05/2016
     * Date Last Modified: 13/05/2016
     */
    public function actionGetUnregisteredApplicants()
    {
        $dataProvider = NULL;
        $data = array();

        if (Yii::$app->request->post()) 
        {
            $request = Yii::$app->request;
           
            $application_periodid = $request->post('period');
            if (!$application_periodid)
                $application_periodid = Yii::$app->session->get('application_periodid');
            
            Yii::$app->session->set('application_periodid', $application_periodid);
        } 
        else 
        {
            $application_periodid = Yii::$app->session->get('application_periodid');
            Yii::$app->session->set('application_periodid', $application_periodid);
        }

        if ($application_periodid != 0) 
        {
            $divisionid = ApplicationPeriod::find()
                            ->where(['applicationperiodid' => $application_periodid])
                            ->one()
                    ->divisionid;

            $cond = array();
            $cond['application.isactive'] = 1;
            $cond['application.isdeleted'] = 0;
            $cond['academic_offering.isactive'] = 1;
            $cond['academic_offering.isdeleted'] = 0;
            $cond['academic_offering.applicationperiodid'] = $application_periodid;
            $cond['application_period.isactive'] = 1;
            $cond['application_period.isdeleted'] = 0;
            
            $divisionid = ApplicationPeriod::find()
                            ->where(['applicationperiodid' => $application_periodid])
                            ->one()
                    ->divisionid;

            $applicants = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->where($cond)
                        ->andWhere(['>=', 'application.applicationstatusid', 3])
                        ->groupby('applicant.personid')
                        ->orderBy('applicant.lastname ASC')
                        ->all();

            foreach ($applicants as $applicant) 
            {
                $offers = Offer::hasOffer($applicant->personid, $application_periodid);
                
                if($offers == true)
                {
                    foreach ($offers as $offer) 
                    {
                        $has_enrolled = StudentRegistration::find()
                                ->where(['offerid' => $offer->offerid])
                                ->one();
                        
                        if($has_enrolled == false)
                        {
//                            $offer = end($offers);
                    
                            $username = User::findOne(['personid' => $applicant->personid, 'isdeleted' => 0])->username;

                            $programme = "N/A";
                            $target_application = Application::find()
                                    ->where(['applicationid' => $offer->applicationid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->one();
                            if ($target_application) 
                            {
                                $programme_record = ProgrammeCatalog::find()
                                        ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                                        ->where(['academicofferingid' => $target_application->academicofferingid])
                                        ->one();
                                $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $target_application->applicationid]);
                                foreach ($cape_subjects as $cs) 
                                {
                                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname;
                                }
                                $programme = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
                            }

                            $info = array();
                            $info['personid'] = $applicant->personid;
                            $info['applicantid'] = $applicant->applicantid;
                            $info['username'] = $username;
                            $info['title'] = $applicant->title;
                            $info['firstname'] = $applicant->firstname;
                            $info['middlename'] = $applicant->middlename;
                            $info['lastname'] = $applicant->lastname;
                            $info['offerid'] = $offer->offerid;
                            $info['applicationid'] = $offer->applicationid;
                            $info['programme'] = $programme;

                            $data[] = $info;

                            $cape_subjects = NULL;
                            $cape_subjects_names = NULL;
                        }
                    }
                    
                }
            }
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
        
        $periodname = ApplicationPeriod::find()
                ->where(['applicationperiodid' => $application_periodid])
                ->one()
                ->name;
        
        $header = $periodname . " UnEnrolled Applicants";
        $title = "Title: " . $header;
        $date = "Date Generated: " . date('Y-m-d') . "   ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = "Generated By: " . Employee::getEmployeeName($employeeid);
        $filename = $title . $date . $generating_officer;

        return $this->render('display_unregistered_applicants', [
                    'dataProvider' => $dataProvider,
                    'header' => $header,
                    'filename' => $filename,
                    'application_periodid' => $application_periodid,
        ]);
    }
    
    
    /**
     * Render index screen for programme intake
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 15/05/2016
     * Date Last Modified: 15/05/2016
     */
    public function actionFindProgrammeIntake()
    {
        $periods = ApplicationPeriod::preparePeriods();
        
        return $this->render('find_programme_intake', 
                            [
                                'periods' => $periods,
                            ]
        );
    }
    
    
    
    /**
     * Generate programme/capesubject listing
     * 
     * @param type $applicationperiodid
     * @param type $listing_type
     * 
     * Author: Laurence Charles
     * Date Created: 13/05/2016
     * Date Last Modified: 13/05/2016
     */
    public function actionGetIntakeListing($applicationperiodid, $listing_type) 
    {
        if ($listing_type == 1)     //if all programmes selected
        {    
            $records = AcademicOffering::find()
                    ->where(['applicationperiodid' => $applicationperiodid, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();

            $listing = array();
            foreach ($records as $record) 
            {
                $combined = array();
                $keys = array();
                $values = array();
                array_push($keys, "id");
                array_push($keys, "name");
                $k1 = strval($record->academicofferingid);
                $name = ProgrammeCatalog::getProgrammeName($record->academicofferingid);
                $k2 = strval($name);
                array_push($values, $k1);
                array_push($values, $k2);
                $combined = array_combine($keys, $values);
                array_push($listing, $combined);
                $combined = NULL;
                $keys = NULL;
                $values = NULL;
            }
        } 
        elseif ($listing_type == 2)     //if CAPE subjects selected
        {    
            $records = CapeSubject::find()
                    ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `cape_subject`.`academicofferingid`')
                    ->where(['academic_offering.applicationperiodid' => $applicationperiodid, 'cape_subject.isactive' => 1, 'cape_subject.isdeleted' => 0])
                    ->all();

            $listing = array();
            foreach ($records as $record) 
            {
                $combined = array();
                $keys = array();
                $values = array();
                array_push($keys, "id");
                array_push($keys, "name");
                $k1 = strval($record->capesubjectid);
                $subject = CapeSubject::find()
                        ->where(['capesubjectid' => $record->capesubjectid])
                        ->one();
                $k2 = strval($subject->subjectname);
                array_push($values, $k1);
                array_push($values, $k2);
                $combined = array_combine($keys, $values);
                array_push($listing, $combined);
                $combined = NULL;
                $keys = NULL;
                $values = NULL;
            }
        }

        if ($listing) 
        {
            $found = 1;
            echo Json::encode(['found' => $found, 'listingtype' => $listing_type, 'programmes' => $listing]);
        } 
        else 
        {
            $found = 0;
            echo Json::encode(['found' => $found]);
        }
    }
    
    
    /**
     * Generate accepted applicant/ enrolled figure for a particular programme/capesubject
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 13/05/2016
     * Date Last Modified: 13/05/2016
     */
    public function actionGenerateProgrammeIntake()
    {
        $summary_dataProvider = NULL;
        $accepted_dataProvider = NULL;
        $enrolled_dataProvider = NULL;
        
        $summary_data = array();
        $accepted_data = array();
        $enrolled_data = array();
        
        if (Yii::$app->request->post()) 
        {
            $request = Yii::$app->request;
            
            $programmeid = NULL;
            $criteria = NULL;
            
            $application_periodid = $request->post('period');
            if (!$application_periodid)
                $application_periodid = Yii::$app->session->get('application_periodid');

            $prog = $request->post('prog') ? $request->post('prog') : 0;
            $subj = $request->post('subj') ? $request->post('subj') : 0;

            if ($prog != 0) 
            {
                $programmeid = $prog;
                $criteria = "programme";
            } 
            elseif ($subj != 0) 
            {
                $programmeid = $subj;
                $criteria = "subject";
            } 
            
            if(!$programmeid)
                $programmeid = Yii::$app->session->get('programmeid');
            
            if(!$criteria)
                $criteria = Yii::$app->session->get('criteria');

            Yii::$app->session->set('application_periodid', $application_periodid);
            Yii::$app->session->set('programmeid', $programmeid);
            Yii::$app->session->set('criteria', $criteria);
        } 
        else 
        {
            $application_periodid = Yii::$app->session->get('application_periodid');
            $programmeid = Yii::$app->session->get('programmeid');
            $criteria = Yii::$app->session->get('criteria');
            
            Yii::$app->session->set('application_periodid', $application_periodid);
            Yii::$app->session->set('programmeid', $programmeid);
            Yii::$app->session->set('criteria', $criteria);
        }

        
        if ($application_periodid != 0) 
        {
            $divisionid = ApplicationPeriod::find()
                    ->where(['applicationperiodid' => $application_periodid])
                    ->one()
                    ->divisionid;

            $accepted_cond = array();
            $accepted_cond['application.isactive'] = 1;
            $accepted_cond['application.isdeleted'] = 0;
            $accepted_cond['academic_offering.isactive'] = 1;
            $accepted_cond['academic_offering.isdeleted'] = 0;
            $accepted_cond['academic_offering.applicationperiodid'] = $application_periodid;
            $accepted_cond['application_period.isactive'] = 1;
            $accepted_cond['application_period.isdeleted'] = 0;
            $accepted_cond['application.applicationstatusid'] = 9;
            $accepted_cond['offer.isactive'] = 1;
            $accepted_cond['offer.isdeleted'] = 0;
            $accepted_cond['offer.offertypeid'] = 1;
            
            
            if ($criteria == "programme") 
            {
                $accepted_cond['application.academicofferingid'] = $programmeid;
                
                $accepted_applicants = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->where($accepted_cond)
                        ->groupby('applicant.personid')
                        ->orderBy('applicant.lastname ASC')
                        ->all();
            } 
            elseif ($criteria == "subject") 
            {
                $offeringid = AcademicOffering::getCapeID($application_periodid);
                
                $accepted_cond['academic_offering.academicofferingid'] = $offeringid;
                $accepted_cond['application_capesubject.capesubjectid'] = $programmeid;
                $accepted_cond['application_capesubject.isactive'] = 1;
                $accepted_cond['application_capesubject.isdeleted'] = 0;
                
                $accepted_applicants = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->innerJoin('application_capesubject', '`application`.`applicationid` = `application_capesubject`.`applicationid`')
                        ->where($accepted_cond)
                        ->groupby('applicant.personid')
                        ->orderBy('applicant.lastname ASC')
                        ->all();
            }


            foreach ($accepted_applicants as $accepted_applicant) 
            {
                $offers = Offer::hasOffer($accepted_applicant->personid, $application_periodid);
                
                if($offers == true)
                {
                    foreach ($offers as $offer) 
                    {
                        $username = User::findOne(['personid' => $accepted_applicant->personid, 'isdeleted' => 0])->username;
                        
                        if ($criteria == "programme") 
                        {
                            $programme = "N/A";
                            $target_application = Application::find()
                                    ->where(['applicationid' => $offer->applicationid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->one();
                            if ($target_application) 
                            {
                                $programme_record = ProgrammeCatalog::find()
                                        ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                                        ->where(['academicofferingid' => $target_application->academicofferingid])
                                        ->one();
                                $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $target_application->applicationid]);
                                foreach ($cape_subjects as $cs) 
                                {
                                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname;
                                }
                                $programme = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
                            }
                        }
                        elseif($criteria == "subject")
                        {
                            $subject = "N/A";
                            $subject = CapeSubject::find()
                                    ->where(['capesubjectid' => $programmeid])
                                    ->one()
                                    ->subjectname;
                        }

                        $accepted_info = array();
                        $accepted_info['personid'] = $accepted_applicant->personid;
                        $accepted_info['applicantid'] = $accepted_applicant->applicantid;
                        $accepted_info['username'] = $username;
                        $accepted_info['title'] = $accepted_applicant->title;
                        $accepted_info['firstname'] = $accepted_applicant->firstname;
                        $accepted_info['middlename'] = $accepted_applicant->middlename;
                        $accepted_info['lastname'] = $accepted_applicant->lastname;
                        $accepted_info['offerid'] = $offer->offerid;
                        $accepted_info['applicationid'] = $offer->applicationid;
                        if($criteria == "programme")
                            $accepted_info['programme'] = $programme;
                        elseif($criteria == "subject")
                            $accepted_info['programme'] = $subject;
                        $accepted_data[] = $accepted_info;
                        
                        $has_enrolled = StudentRegistration::find()
                                ->where(['offerid' => $offer->offerid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one();
                        
                        if($has_enrolled == true)
                        {
                            $enrolled_info = array();
                            $enrolled_info['personid'] = $accepted_applicant->personid;
                            $enrolled_info['applicantid'] = $accepted_applicant->applicantid;
                            $enrolled_info['username'] = $username;
                            $enrolled_info['title'] = $accepted_applicant->title;
                            $enrolled_info['firstname'] = $accepted_applicant->firstname;
                            $enrolled_info['middlename'] = $accepted_applicant->middlename;
                            $enrolled_info['lastname'] = $accepted_applicant->lastname;
                            $enrolled_info['offerid'] = $offer->offerid;
                            $enrolled_info['applicationid'] = $offer->applicationid;
                            if($criteria == "programme")
                                $enrolled_info['programme'] = $programme;
                            elseif($criteria == "subject")
                                $enrolled_info['programme'] = $subject;
                            $enrolled_data[] = $enrolled_info;
                        }
                        
                        $cape_subjects = NULL;
                        $cape_subjects_names = NULL;
                    }
                }
            }
            
            if($criteria == "programme")
            {
                $accepted_criteria = $programme;
                $enrolled_criteria = $programme;
            }
            elseif ($criteria == "subject")
            {
                $accepted_criteria = $subject;
                $enrolled_criteria = $subject;
            }
            
            /*************************************** prepare programmes *****************************************/
            $academic_offerings = AcademicOffering::find()
                    ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->where(['academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 'academic_offering.applicationperiodid' => $application_periodid,
                            'application_period.isactive' => 1, 'application_period.isdeleted' => 0,
                            'application.isactive' => 1, 'application.isdeleted' => 0
                            ])
                    ->all();
            
            $summary_info = array();
            
            foreach($academic_offerings as $offering)
            {
                $programme_record = ProgrammeCatalog::find()
                            ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                            ->where(['programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0,
                                    'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 'academic_offering.academicofferingid' => $offering->academicofferingid
                                    ])
                            ->one();
                $name = $programme_record->getFullName();
                
                $accepted_count = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->where(['application.isactive' => 1, 'application.isdeleted' => 0, 'application.applicationstatusid' => 9,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 'academic_offering.academicofferingid' => $offering->academicofferingid,
                                'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1,
                                'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.applicationperiodid' => $application_periodid,
                                ])
                        ->groupby('applicant.personid')
                        ->count();
                
                $enrolled_count = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->innerJoin('student_registration', '`offer`.`offerid` = `student_registration`.`offerid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->where(['application.isactive' => 1, 'application.isdeleted' => 0,  'application.applicationstatusid' => 9,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 'academic_offering.academicofferingid' => $offering->academicofferingid,
                                'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1,
                                'student_registration.isactive' => 1, 'student_registration.isdeleted' => 0,
                                'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.applicationperiodid' => $application_periodid,
                                ])
                        ->groupby('applicant.personid')
                        ->count();
                
                $summary_info['name'] = $name;
                $summary_info['accepted'] = $accepted_count;
                $summary_info['enrolled'] = $enrolled_count;
                $summary_data[] = $summary_info;
            }
            
            /*************************************** prepare subjects *****************************************/
            $subjects = CapeSubject::find()
                        ->innerJoin('application_capesubject', '`cape_subject`.`capesubjectid` = `application_capesubject`.`capesubjectid`')
                        ->innerJoin('application', '`application_capesubject`.`applicationid` = `application`.`applicationid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->where(['cape_subject.isactive' => 1, 'cape_subject.isdeleted' => 0,
                                'application_capesubject.isactive' => 1, 'application_capesubject.isdeleted' => 0,
                                'application.isactive' => 1, 'application.isdeleted' => 0,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, /*'academic_offering.academicofferingid' => $offeringid,*/
                                'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1,
                                'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.applicationperiodid' => $application_periodid,
                                ])
                        ->all();
            
            foreach($subjects as $subject)
            {
                $subject_name = CapeSubject::find()
                                    ->where(['capesubjectid' => $subject->capesubjectid])
                                    ->one()
                                    ->subjectname;
                
                $accepted_count = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_capesubject', '`application`.`applicationid` = `application_capesubject`.`applicationid`')
                        ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->where(['application.isactive' => 1, 'application.isdeleted' => 0,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 
                                'application_capesubject.isactive' => 1, 'application_capesubject.isdeleted' => 0, 'application_capesubject.capesubjectid' => $subject->capesubjectid,
                                'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1, 'offer.offertypeid' => 1,
                                'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.applicationperiodid' => $application_periodid,
                                ])
                        ->groupby('application.personid')
                        ->count();
                
                $enrolled_count = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_capesubject', '`application`.`applicationid` = `application_capesubject`.`applicationid`')
                        ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->innerJoin('student_registration', '`offer`.`offerid` = `student_registration`.`offerid`')
                        ->where(['application.isactive' => 1, 'application.isdeleted' => 0,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 
                                'application_capesubject.isactive' => 1, 'application_capesubject.isdeleted' => 0, 'application_capesubject.capesubjectid' => $subject->capesubjectid,
                                'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1, 'offer.offertypeid' => 1,
                                'student_registration.isactive' => 1, 'student_registration.isdeleted' => 0,
                                'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.applicationperiodid' => $application_periodid,
                                ])
                        ->groupby('application.personid')
                        ->count();
                
                $summary_info['name'] = $subject_name;
                $summary_info['accepted'] = $accepted_count;
                $summary_info['enrolled'] = $enrolled_count;
                $summary_data[] = $summary_info;
            }
            
           
        }
        
        $summary_dataProvider = new ArrayDataProvider([
            'allModels' => $summary_data,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);
        
        $accepted_dataProvider = new ArrayDataProvider([
            'allModels' => $accepted_data,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        $enrolled_dataProvider = new ArrayDataProvider([
            'allModels' => $enrolled_data,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        $periodname = ApplicationPeriod::find()
                ->where(['applicationperiodid' => $application_periodid])
                ->one()
                ->name;
        
        $summary_header = "Intake Overview";
        $summary_title = "Title: " . $periodname . $summary_header;
        
        
        $accepted_header = "Accepted Applicants Report - " . $accepted_criteria;
        $accepted_title = "Title: " . $periodname .  $accepted_header;
        
        $enrolled_header = "Enrolled Students Report - " . $enrolled_criteria;
        $enrolled_title = "Title: " . $periodname .  $enrolled_header;
        ;
        
        $date = "Date Generated: " . date('Y-m-d') . "   ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = "Generated By: " . Employee::getEmployeeName($employeeid);
        
        $summary_filename = $summary_title . $date . $generating_officer;
        $accepted_filename = $accepted_title . $date . $generating_officer;
        $enrolled_filename = $enrolled_title . $date . $generating_officer;
        
        $page_title = $periodname . " Intake Report";
        
        return $this->render('display_programme_intake', [
                    'summary_dataProvider' => $summary_dataProvider,
                    'accepted_dataProvider' => $accepted_dataProvider,
                    'enrolled_dataProvider' => $enrolled_dataProvider,
            
                    'summary_header' =>  $summary_header,
                    'accepted_header' => $accepted_header,
                    'enrolled_header' => $enrolled_header,
            
                    'summary_filename' => $summary_filename,
                    'accepted_filename' => $accepted_filename,
                    'enrolled_filename' => $enrolled_filename,
                    
                    'application_periodid' => $application_periodid,
                    'page_title' => $page_title,
                    'programmeid' => $programmeid,
                    'criteria' => $criteria
        ]);
    }
    
    
    

}