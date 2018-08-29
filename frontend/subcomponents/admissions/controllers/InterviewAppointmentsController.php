<?php

    namespace app\subcomponents\admissions\controllers;

    use Yii;
    use yii\data\ArrayDataProvider;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\helpers\Url;
    use yii\base\Model;

    use common\models\User;
    use frontend\models\Offer;
    use frontend\models\ApplicationPeriod;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\Applicant;
    use frontend\models\EmployeeDepartment;


    class InterviewAppointmentsController extends Controller
    {
        public function behaviors()
        {
            return [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post', 'get'],
                    ],
                ],
            ];
        }


        /**
         * Interview Schedule control panel
         *
         * @return type
         *
         * Author: charles.laurence1@gmail.com
         * Created: 2018_05_09
         * Modified: 2018_05_09
         */
        public function actionIndex()
        {
            $user_divisionid = EmployeeDepartment::getUserDivision();

            if ($user_divisionid && $user_divisionid != 1)
            {
                $application_periods = ApplicationPeriod::find()
                                        ->where(['divisionid' => $user_divisionid, 'isactive' => 1, 'isdeleted' => 0, 'iscomplete' => 0])
                                        ->all();
            }
            else
            {
               $application_periods = ApplicationPeriod::find()
                                            ->where(['isactive' => 1, 'isdeleted' => 0, 'iscomplete' => 0])
                                            ->all();
            }

            foreach ($application_periods as $period)
            {
                $divisions[$period->divisionid] = $period->getDivisionName();
            }

            $prog_cond = array();
            $prog_cond['application_period.iscomplete'] = 0;
            $prog_cond['application_period.isactive'] = 1;
            $prog_cond['application_period.isdeleted'] = 0;
            $prog_cond['programme_catalog.isactive'] = 1;
            $prog_cond['programme_catalog.isdeleted'] = 0;

            if ($user_divisionid && $user_divisionid != 1)
                $prog_cond['application_period.divisionid'] = $user_divisionid;

            $programmes = ProgrammeCatalog::find()
                    ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                    ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                    ->where($prog_cond)
                    ->all();

            return $this->render('index', [
                'application_periods' => $application_periods,
                'programme_objects' => $programmes]);
        }


        /**
         * Schedule Interviews By Lastname
         *
         * @param type $applicationperiod_id
         * @param type $offertype
         * @param type $lower_bound
         * @param type $upper_bound
         * @return type
         *
         * Author: charles.laurence1@gmail.com
         * Created: 2018_05_09
         * Modified: 2018_05_09
         */
        public function actionScheduleInterviewsByLastname($applicationperiod_id, $offertype, $lower_bound, $upper_bound)
        {
            $offers = array();

            $application_period = ApplicationPeriod::find()
                    ->where(['applicationperiodid' =>  $applicationperiod_id, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();

            $offer_cond['offer.offertypeid'] = $offertype;
            $offer_cond['offer.isdeleted'] = 0;
            $offer_cond['academic_offering.applicationperiodid'] = $applicationperiod_id;
            $offer_cond['academic_offering.interviewneeded'] = 1;
            $offer_cond['academic_offering.isactive'] = 1;
            $offer_cond['academic_offering.isdeleted'] = 0;
            $offer_cond['application_period.isactive'] = 1;
            $offer_cond['application_period.isdeleted'] = 0;
            $offer_cond['application_period.iscomplete'] = 0;

             $all_offers = Offer::find()
                    ->joinWith('application')
                    ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->innerJoin('`application_period`', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->innerJoin('`applicant`', '`application`.`personid` = `applicant`.`personid`')
                    ->where($offer_cond)
                    ->orderBy("applicant.lastname ASC")
                    ->all();

            foreach ($all_offers as $offer)
            {
                $applicant = Applicant::find()
                        ->innerJoin('`application`', '`applicant`.`personid` = `application`.`personid`')
                        ->where(['applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                                       'application.isactive' => 1, 'application.isdeleted' => 0  ,'application.applicationid' => $offer->applicationid])
                        ->one();
                if ($applicant)
                {
                    $lastname = ucfirst($applicant->lastname);
                    $lastname_first_letter = substr($lastname, 0, 1);

                    #if equal to lower bound OR (greater than lower bound AND less than upper bound) OR equal to upper bound
                    if (strcmp($lastname_first_letter, $lower_bound) == 0
                            || (strcmp($lastname_first_letter, $lower_bound)  > 0 && strcmp($lastname_first_letter, $upper_bound)  < 0)
                            || strcmp($lastname_first_letter, $upper_bound) == 0)
                    {
                        $offers[] = $offer;
                    }
                }
            }

            if ($post_data = Yii::$app->request->post())
            {
                $transaction = \Yii::$app->db->beginTransaction();
                try
                {
                    $load_flag = Model::loadMultiple($offers, $post_data);
                    if ($load_flag == false)
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occurred loading records.');
                    }
                    else
                    {
                        foreach ($offers as $offer)
                        {
                            if ($offer->appointment == NULL || $offer->appointment == false || $offer->appointment == "" || $offer->appointment == " " )
                            {
                                $offer->appointment = NULL;
                            }
                            $save_flag = $offer->save();
                            if (  $save_flag == false)
                            {
                                 $transaction->rollBack();
                                 Yii::$app->getSession()->setFlash('error', 'Error occuvred saving record');
                            }
                        }
                        $transaction->commit();
                        return $this->redirect(['index']);
                    }
                } catch (Exception $ex)
                {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occured processing your request. Please try again');
                }
            }

            return $this->render('schedule_interviews_by_lastname', [
                'applicationperiod_id' => $applicationperiod_id,
                  'offertype' => $offertype,
                  'lower_bound' => $lower_bound,
                  'upper_bound' => $upper_bound,
                  'application_period' => $application_period,
                  'offers' => $offers]);
         }


         /**
          * Schedule Interviews By Programme
          *
          * @param type $academic_offering_id
          * @param type $offertype
          * @return type
          *
          * Author: charles.laurence1@gmail.com
         * Created: 2018_05_09
         * Modified: 2018_05_09
          */
         public function actionScheduleInterviewsByProgramme($academic_offering_id, $offertype)
         {
             $programme = ProgrammeCatalog::find()
                    ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                    ->where([ 'programme_catalog.isdeleted' => 0, 'academic_offering.academicofferingid' => $academic_offering_id,
                                    'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0])
                    ->one();
             $programme_name = $programme->getFullName();

            $offer_cond['offer.offertypeid'] = $offertype;
            $offer_cond['offer.isdeleted'] = 0;
            $offer_cond['academic_offering.academicofferingid'] = $academic_offering_id;
            $offer_cond['academic_offering.interviewneeded'] = 1;
            $offer_cond['academic_offering.isactive'] = 1;
            $offer_cond['academic_offering.isdeleted'] = 0;

             $offers = Offer::find()
                    ->joinWith('application')
                    ->innerJoin('`academic_offering`', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->innerJoin('`applicant`', '`application`.`personid` = `applicant`.`personid`')
                    ->where($offer_cond)
                    ->orderBy("applicant.lastname ASC")
                    ->all();

            if ($post_data = Yii::$app->request->post())
            {
                $transaction = \Yii::$app->db->beginTransaction();
                try
                {
                    $load_flag = Model::loadMultiple($offers, $post_data);
                    if ($load_flag == false)
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occurred loading records.');
                    }
                    else
                    {
                        foreach ($offers as $offer)
                        {
                            if ($offer->appointment == NULL || $offer->appointment == false || $offer->appointment == "" || $offer->appointment == " " )
                            {
                                $offer->appointment = NULL;
                            }
                            $save_flag = $offer->save();
                            if (  $save_flag == false)
                            {
                                 $transaction->rollBack();
                                 Yii::$app->getSession()->setFlash('error', 'Error occured saving record');
                            }
                        }
                        $transaction->commit();
                        return $this->redirect(['index']);
                    }
                } catch (Exception $ex)
                {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occured processing your request. Please try again');
                }
            }

            return $this->render('schedule_interviews_by_programme', [
                'offertype' => $offertype,
                'offers' => $offers,
                'programme_name' => $programme_name]);
         }



    }
