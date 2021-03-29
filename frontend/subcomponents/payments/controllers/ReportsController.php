<?php

namespace app\subcomponents\payments\controllers;

use Yii;
use yii\web\Controller;
use yii\base\Model;
use yii\data\ArrayDataProvider;

use common\models\User;
use frontend\models\Applicant;
use frontend\models\ApplicationPeriod;
use frontend\models\Phone;
use frontend\models\Relation;
use frontend\models\CompulsoryRelation;
use frontend\models\Offer;
use frontend\models\StudentRegistration;
use frontend\models\Employee;
use frontend\models\EmployeeDepartment;
use frontend\models\Email;
use frontend\models\Address;


class ReportsController extends Controller
{

    /**
     * Renders page applicatino period selection for insurance information listing
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 13/05/2016
     * Date Last Modified: 13/05/2016
     */
    public function actionFindBeneficieries()
    {

        $periods = ApplicationPeriod::find()
            ->innerJoin('academic_offering', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
            ->innerJoin('application', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
            ->where([
                'application_period.isactive' => 1, 'application_period.isdeleted' => 0,
                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                'application.isactive' => 1, 'application.isdeleted' => 0
            ])
            ->andWhere(['>=', 'applicationperiodstatusid', 5])
            ->all();

        return $this->render('insurance_listing_criteria', [
            'periods' => $periods,
        ]);
    }


    /**
     * Generate insurance listing
     * 
     * @param type $id
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 10/10/2016
     * Date Last Modified: 10/10/2016
     */
    public function actionGenerateInsuranceListing($applicationperiodid)
    {
        $accepted_dataProvider = NULL;
        $enrolled_dataProvider = NULL;

        $accepted_data = array();
        $enrolled_data = array();

        $accepted_cond = array();
        $accepted_cond['application.isactive'] = 1;
        $accepted_cond['application.isdeleted'] = 0;
        $accepted_cond['academic_offering.isactive'] = 1;
        $accepted_cond['academic_offering.isdeleted'] = 0;
        $accepted_cond['academic_offering.applicationperiodid'] = $applicationperiodid;
        $accepted_cond['application_period.isactive'] = 1;
        $accepted_cond['application_period.isdeleted'] = 0;
        $accepted_cond['application.applicationstatusid'] = 9;
        $accepted_cond['offer.isactive'] = 1;
        $accepted_cond['offer.isdeleted'] = 0;
        $accepted_cond['offer.offertypeid'] = 1;

        $accepted_applicants = Applicant::find()
            ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
            ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
            ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
            ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
            ->where($accepted_cond)
            ->groupby('applicant.personid')
            ->orderBy('applicant.lastname ASC')
            ->all();


        foreach ($accepted_applicants as $accepted_applicant) {
            $offers = Offer::hasOffer($accepted_applicant->personid, $applicationperiodid);

            if ($offers == true) {
                foreach ($offers as $offer) {
                    $username = User::findOne(['personid' => $accepted_applicant->personid, 'isdeleted' => 0])->username;

                    $accepted_info = array();
                    $accepted_info['personid'] = $accepted_applicant->personid;
                    $accepted_info['username'] = $username;
                    $accepted_info['title'] = $accepted_applicant->title;
                    $accepted_info['firstname'] = $accepted_applicant->firstname;
                    $accepted_info['middlename'] = $accepted_applicant->middlename;
                    $accepted_info['lastname'] = $accepted_applicant->lastname;
                    $accepted_info['dateofbirth'] = $accepted_applicant->dateofbirth;
                    $accepted_info['email'] = Email::find()->where(['personid' => $accepted_applicant->personid, 'isdeleted' => 0])->one()->email;

                    $address_record = Address::find()->where(['personid' => $accepted_applicant->personid, 'addresstypeid' => 1, 'isdeleted' => 0])->one();
                    $address = "";
                    if ($address_record->town == "other") {
                        $address .= $address_record->country . ", " . $address_record->addressline;
                    } else {
                        $address .= $address_record->country . ", " . $address_record->town;
                    }
                    $accepted_info['address'] =  $address;

                    $phone = Phone::find()
                        ->where(['personid' => $accepted_applicant->personid, 'isdeleted' => 0])
                        ->one();
                    $numbers = "";
                    if ($phone->homephone)
                        $numbers .= $phone->homephone . ", ";
                    if ($phone->cellphone)
                        $numbers .= $phone->cellphone . ", ";
                    if ($phone->workphone)
                        $numbers .= $phone->workphone;
                    $accepted_info['phone'] = $numbers;


                    $beneficiery = Relation::find()->where(['personid' => $accepted_applicant->personid, 'relationtypeid' => 6, 'isdeleted' => 0])->one();
                    if ($beneficiery == false) {
                        $beneficiery = CompulsoryRelation::find()->where(['personid' => $accepted_applicant->personid, 'relationtypeid' => 6, 'isdeleted' => 0])->one();
                        if ($beneficiery == false) {
                            //                                $accepted_info['beneficiery_address'] = "??";
                            continue;
                        } else {
                            $accepted_info['beneficiery_address'] = $beneficiery->address;
                            $accepted_info['beneficiery_relationship'] = $beneficiery->relationdetail;
                        }
                    } else {
                        $address_field = $beneficiery->address;
                        if ($address_field) {
                            $beneficiery_address = $beneficiery->address;
                        } else {
                            $beneficiery_address = "";
                            if ($beneficiery->town == "other") {
                                $beneficiery_address .= $beneficiery->country . ", " . $beneficiery->addressline;
                            } else {
                                $beneficiery_address .= $beneficiery->country . ", " . $beneficiery->town;
                            }
                        }
                        $accepted_info['beneficiery_address'] = $beneficiery_address;
                        $accepted_info['beneficiery_relationship'] = null;
                    }

                    $accepted_info['beneficiery_name'] = "{$beneficiery->title} {$beneficiery->firstname} {$beneficiery->lastname}";

                    $beneficiery_number = "";
                    if ($beneficiery->homephone)
                        $beneficiery_number .= $beneficiery->homephone . ", ";
                    if ($beneficiery->cellphone)
                        $beneficiery_number .=  $beneficiery->cellphone . ", ";
                    if ($beneficiery->workphone)
                        $beneficiery_number .= $beneficiery->workphone;
                    $accepted_info['beneficiery_number'] = $beneficiery_number;
                    $accepted_data[] = $accepted_info;


                    $has_enrolled = StudentRegistration::find()
                        ->where(['offerid' => $offer->offerid, 'isdeleted' => 0])
                        ->one();

                    if ($has_enrolled) {
                        $enrolled_info = array();
                        $enrolled_info['personid'] = $accepted_applicant->personid;
                        $enrolled_info['applicantid'] = $accepted_applicant->applicantid;
                        $enrolled_info['username'] = $username;
                        $enrolled_info['title'] = $accepted_applicant->title;
                        $enrolled_info['firstname'] = $accepted_applicant->firstname;
                        $enrolled_info['middlename'] = $accepted_applicant->middlename;
                        $enrolled_info['lastname'] = $accepted_applicant->lastname;
                        $enrolled_info['dateofbirth'] = $accepted_applicant->dateofbirth;

                        $enrolled_info['email'] = Email::find()->where(['personid' => $accepted_applicant->personid, 'isdeleted' => 0])->one()->email;

                        $address_record = Address::find()->where(['personid' => $accepted_applicant->personid, 'addresstypeid' => 1, 'isdeleted' => 0])->one();
                        $address = "";
                        if ($address_record->town == "other") {
                            $address .= $address_record->country . ", " . $address_record->addressline;
                        } else {
                            $address .= $address_record->country . ", " . $address_record->town;
                        }
                        $enrolled_info['address'] =  $address;

                        $phone = Phone::find()
                            ->where(['personid' => $accepted_applicant->personid, 'isdeleted' => 0])
                            ->one();
                        $numbers = "";
                        if ($phone->homephone)
                            $numbers .= $phone->homephone . ", ";
                        if ($phone->cellphone)
                            $numbers .= $phone->cellphone . ", ";
                        if ($phone->workphone)
                            $numbers .= $phone->workphone;
                        $enrolled_info['phone'] = $numbers;

                        $beneficiery = Relation::find()->where(['personid' => $accepted_applicant->personid, 'relationtypeid' => 6, 'isdeleted' => 0])->one();
                        if ($beneficiery == false) {
                            $beneficiery = CompulsoryRelation::find()->where(['personid' => $accepted_applicant->personid, 'relationtypeid' => 6, 'isdeleted' => 0])->one();
                            $enrolled_info['beneficiery_address'] = $beneficiery->address;
                            $enrolled_info['beneficiery_relationship'] = $beneficiery->relationdetail;
                        } else {
                            $address_field = $beneficiery->address;
                            if ($address_field) {
                                $beneficiery_address = $beneficiery->address;
                            } else {
                                $beneficiery_address = "";
                                if ($beneficiery->town == "other") {
                                    $beneficiery_address .= $beneficiery->country . ", " . $beneficiery->addressline;
                                } else {
                                    $beneficiery_address .= $beneficiery->country . ", " . $beneficiery->town;
                                }
                            }
                            $enrolled_info['beneficiery_address'] = $beneficiery_address;
                            $enrolled_info['beneficiery_relationship'] = null;
                        }

                        $enrolled_info['beneficiery_name'] = "{$beneficiery->title} {$beneficiery->firstname} {$beneficiery->lastname}";

                        $beneficiery_number = "";
                        if ($beneficiery->homephone)
                            $beneficiery_number .= $beneficiery->homephone . ", ";
                        if ($beneficiery->cellphone)
                            $beneficiery_number .=  $beneficiery->cellphone . ", ";
                        if ($beneficiery->workphone)
                            $beneficiery_number .= $beneficiery->workphone;
                        $enrolled_info['beneficiery_number'] = $beneficiery_number;

                        $currentRegistration =
                            StudentRegistration::find()
                            ->where([
                                "personid" => $accepted_applicant->personid,
                                "isactive" => 1
                            ])
                            ->one();
                        if ($currentRegistration == true) {
                            $enrolled_info['programme'] =
                                StudentRegistration::generateRegistrationDescription(
                                    $currentRegistration->studentregistrationid
                                );
                        } else {
                            $enrolled_info['programme'] = null;
                        }


                        $enrolled_data[] = $enrolled_info;
                    }
                }
            }
        }

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
            ->where(['applicationperiodid' => $applicationperiodid])
            ->one()
            ->name;

        $accepted_header = "Accepted Applicants Listing";
        $accepted_title = "Title: " . $periodname . " " .  $accepted_header;

        $enrolled_header = "Enrolled Students Listing";
        $enrolled_title = "Title: " . $periodname . " " .  $enrolled_header;

        $date = " Date: " . date('Y-m-d') . "   ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = " Generator: " . Employee::getEmployeeName($employeeid);

        $accepted_filename = $accepted_title . $date . $generating_officer;
        $enrolled_filename = $enrolled_title . $date . $generating_officer;


        return $this->render('student_insurance_listing', [
            'accepted_dataProvider' => $accepted_dataProvider,
            'enrolled_dataProvider' => $enrolled_dataProvider,

            'accepted_header' => $accepted_header,
            'enrolled_header' => $enrolled_header,

            'accepted_filename' => $accepted_filename,
            'enrolled_filename' => $enrolled_filename,
        ]);
    }
}
