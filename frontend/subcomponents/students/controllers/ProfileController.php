<?php

/* 
 * Contoller for Student Profile views.
 * Author: Laurence Charles
 * Date Created: 20/12/2015
 */

    namespace app\subcomponents\students\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\helpers\Url;
    use yii\data\ArrayDataProvider;
    use yii\base\Model;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Json;
    use yii\web\Request;

    use frontend\models\Applicant;
    use frontend\models\Student;
    use common\models\User;
    use frontend\models\Phone;
    use frontend\models\Email;
    use frontend\models\Address;
    use frontend\models\Relation;
    use frontend\models\CompulsoryRelation;
    use frontend\models\MedicalCondition;
    use frontend\models\PersonInstitution;
    use frontend\models\Institution;
    use frontend\models\CsecQualification;
    use frontend\models\CsecCentre;
    use frontend\models\ExaminationBody;
    use frontend\models\ExaminationProficiencyType;
    use frontend\models\ExaminationGrade;
    use frontend\models\Subject;
    use frontend\models\Application;
    use frontend\models\ApplicationCapesubject;
    use frontend\models\Division;
    use frontend\models\Offer; 
    use frontend\models\StudentRegistration;
    use frontend\models\AcademicOffering;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\Department;
    use frontend\models\StudentGeneralModel;
    use frontend\models\RelationType;
    use frontend\models\Hold;
    use frontend\models\StudentTransfer;
    use frontend\models\StudentDeferral;
    use frontend\models\CapeGroup;
    use frontend\models\StudentStatus;
    use frontend\models\QualificationType;
    use frontend\models\CapeSubjectGroup;
    use frontend\models\ApplicationStatus;
    use frontend\models\RegistrationType;    
    use frontend\models\AcademicYear;
    use frontend\models\Cordinator;
    use frontend\models\Assessment;
    use frontend\models\AssessmentCape;
    use frontend\models\AssessmentStudent;
    use frontend\models\AssessmentStudentCape;
    use frontend\models\BatchStudent;
    use frontend\models\AcademicStatus;
    use frontend\models\BatchStudentCape;
    use frontend\models\GeneralWorkExperience;
    use frontend\models\Reference;
    use frontend\models\TeachingExperience;
    use frontend\models\NurseWorkExperience;
    use frontend\models\NursePriorCertification;
    use frontend\models\NursingAdditionalInfo;
    use frontend\models\TeachingAdditionalInfo;
    use frontend\models\CriminalRecord;
    use frontend\models\PostSecondaryQualification;
    use frontend\models\ExternalQualification;
    use frontend\models\Award;
    use frontend\models\PersonAward;
    use frontend\models\AwardCategory;
    use frontend\models\AwardType;
    use frontend\models\Semester;
    use frontend\models\AwardScope;
    use frontend\models\Club;
    use frontend\models\ClubMember;
    use frontend\models\ClubMemberHistory;
    use frontend\models\Event;
    use frontend\models\EventType;
    use frontend\models\DocumentIntent;
    use frontend\models\DocumentSubmitted;
    use frontend\models\DocumentType;
    use frontend\models\Employee;
    use frontend\models\ApplicantDeferral;
    use frontend\models\TransactionType;
    use frontend\models\PaymentMethod;
    use frontend\models\TransactionItem;
    use frontend\models\Transaction;
    
    
    class ProfileController extends Controller
    {
        public function actionIndex()
        {
            return $this->render('index');
        }
        
        
        /**
         * Prepares and renders 'student_profile'
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 20/12//2015
         * Date Last Modified: 20/12/2015
         */
        public function actionStudentProfile($personid, $studentregistrationid)
        {
            $finances_dataProvider = array();
            $finances_data = array();
            
            $applicant= Applicant::findByPersonID($personid);
            $student = Student::getStudent($personid);
            $user = User::getUser($personid);
            
            $transactions = Transaction::find()
                ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->orderBy('transactionsummaryid')
                ->all();
        
            if ($transactions)
            {
                foreach ($transactions as $transaction)
                {
                    $trans = array();
                    $semester = $transaction->getSemester()->one();
                    if ($semester == false)
                        continue;

                    $summary = $transaction->getTransactionsummary()->one();
                     if ($summary == false)
                        continue;

                    $payment_method = PaymentMethod::find()
                            ->where(['paymentmethodid' => $transaction->paymentmethodid, 'isdeleted' => 0])
                            ->one();
                    if ($payment_method == false)
                        continue;

                    $transaction_type = TransactionType::find()
                            ->where(['transactiontypeid' => $transaction->transactiontypeid, 'isdeleted' => 0])
                            ->one();
                    if ($transaction_type == false)
                        continue;

                    $transaction_item = TransactionItem::find()
                            ->where(['transactionitemid' => $transaction->transactionitemid, 'isdeleted' => 0])
                            ->one();
                    if ($transaction_item == false)
                        continue;

                    $trans['id'] = $user->personid;
                    $trans['username'] = $user->username;
                    $trans['fullname'] = $student->firstname . " " . $student->lastname;
                    $trans['transactionid'] = $transaction->transactionid;
                    $trans['summaryid'] = $summary->transactionsummaryid;
                    $trans['payment_method'] = $payment_method->name;
                    $trans['type'] = $transaction_type->name;
                    $trans['date_paid'] = $transaction->paydate;
                    $trans['transaction_item'] = $transaction_item->name;
                    $trans['receiptnumber'] =  $transaction->receiptnumber;
                    $trans['comments'] =  $transaction->comments ?  $transaction->comments: "N/A";
                    $trans['transaction_group_id'] = $transaction->transactionsummaryid;
                    $trans['academic_year'] = $semester->getAcademicyear()->one()->title;
                    $trans['academic_semester'] = $semester->title;
                    $trans['purpose'] = $transaction->getTransactionpurpose()->one()->name;
                    $trans['total_due'] =  $transaction->totaldue;
                    $trans['total_paid'] =  $transaction->paymentamount;
                    $trans['balance'] = $transaction->totaldue - $transaction->paymentamount;
                    $finances_data[] = $trans;
                }
            }
            $finances_dataProvider = new ArrayDataProvider([
                    'allModels' => $finances_data,
                    'pagination' => ['pageSize' => 10],
                    'sort' => [ 'attributes' => ['academic_year', 'purpose', 'transaction_item'],],
                ]);
            
            
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
                            ->where(['divisionid' => $application->divisionid, 'isdeleted' => 0])
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
                        ->where(['divisionid' => $application->divisionid, 'isdeleted' => 0])
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
                else if ($application->ordering == 3)
                {
                    array_push($third, $application);
                    $isCape = Application::isCapeApplication($application->academicofferingid);
                    if ($isCape == true)
                    {
                        $capeSubjects = ApplicationCapesubject::getRecords($application->applicationid);
                        array_push($third, $capeSubjects);
                    }
                    $d = Division::find()
                        ->where(['divisionid' => $application->divisionid, 'isdeleted' => 0])
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
            
            /**************************  Applicant Deferrals  ************************/
            $applicant_deferral = ApplicantDeferral::find()
                ->where(['applicantid' => $applicant->applicantid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
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
            
            /****************************** Transcript ******************************/
            $is_cape = StudentRegistration::isCape($studentregistrationid);
            $person = User::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $studentregistration = StudentRegistration::find()
                    ->where(['studentregistrationid' => $studentregistrationid, 'isdeleted' => 0])
                    ->one();
            $student = Student::find()
                    ->where(['personid' => $personid, 'isdeleted' => 0])
                    ->one();
            $applicant = Applicant::find()
                    ->where(['personid' => $personid, 'isdeleted' => 0])
                    ->one();
            
            if ($person == true  &&  $studentregistration == true  &&  $applicant == true)
            {
                $academicofferingid = $studentregistration->academicofferingid;
                $academic_offering = AcademicOffering::find()
                                    ->where(['academicofferingid' => $academicofferingid, 'isdeleted' => 0])
                                    ->one();
                $academicyearid = $academic_offering->academicyearid;
                $programme_catalog = ProgrammeCatalog::find()
                                    ->where(['programmecatalogid' => $academic_offering->programmecatalogid, 'isdeleted' => 0])
                                    ->one();
                $qualification = QualificationType::find()
                            ->where(['qualificationtypeid' => $programme_catalog->qualificationtypeid])
                            ->one();
                $programmename =  $qualification->abbreviation . " " .  $programme_catalog->name . " " . $programme_catalog->specialisation;
                $department = Department::find()
                            ->where(['departmentid' => $programme_catalog->departmentid, 'isdeleted' => 0])
                            ->one();
                $divisionid = $department->divisionid;
                
                if ($is_cape == true)
                    $cumulative_gpa = 0;
                else
                    $cumulative_gpa = StudentRegistration::calculateCumulativeGPA($studentregistrationid);
                
                $cape_subjects = ApplicationCapesubject::getCapeSubjectListing($studentregistrationid);
            }
  
            $enrollments = StudentRegistration::find()
                    ->where(['personid' => $personid, 'isdeleted' => 0])
                    ->all();
            /******************************* Holds *********************************/
            $financial_holds = Hold::getStudentHoldByCategory($studentregistrationid, 1);
            $academic_holds = Hold::getStudentHoldByCategory($studentregistrationid, 2);
            $library_holds = Hold::getStudentHoldByCategory($studentregistrationid, 3);
            
            /******************************* Statuses *********************************/
            $academic_status = StudentRegistration::getUpdatedAcademicStatus($studentregistrationid);
            
            $student_status = "Determination Pending";
            $student_status_record = StudentStatus::find()
                        ->where(['studentstatusid' => $studentregistration->studentstatusid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            $student_status =  $student_status_record->name;
            
            /**************************  Transfers  && Deferrals ***************************/
            $transfers = StudentTransfer::getTransfers($personid);
            $deferrals = StudentDeferral::getDeferrals($personid);
            
            /***************************** Awards/Clubs ********************************/
            $awards = PersonAward::getAwards($personid);
            $awardDetails = PersonAward::getAwardDetails($personid);
            
            $clubs = ClubMember::getClubs($personid);
            $clubDetails = ClubMember::getClubDetails($personid);
            /*********************************  Events  ********************************/
            $events = Event::getEvents($studentregistrationid);
            $data = array();
            
            foreach($events as $event)
            {
                $event_data = array();
                $event_data['eventid'] = $event->eventid;
                $event_data['recordid'] = $event->recordid;
                $eventtype = EventType::find()
                        ->where(['eventtypeid' => $event->eventtypeid])
                        ->one()
                        ->name;
                $event_data['eventtypeid'] = $event->eventtypeid;
                $event_data['eventtype'] = $eventtype;
                $event_data['studentregistrationid'] = $event->studentregistrationid;
                $event_data['personid'] = $personid;
                $event_data['date'] = $event->date;
                $event_data['summary'] = $event->summary;
                $data[] = $event_data;
            }
            
            $dataProvider = new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => [
                    'pageSize' => 25,
                ],
                'sort' => [
                    'defaultOrder' => ['date' => SORT_ASC],
                    'attributes' => ['date'],
                  ]
            ]);
            /***************************************************************************/
            
            return $this->render('student_profile',[
                'studentregistrationid' => $studentregistrationid,
                
                //finances tab
                'finances_dataProvider' => $finances_dataProvider,
                
                //models for profile tab
                'user' =>  $user,
                'applicant' => $applicant,
                'student' => $student,
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
                'student_status' => $student_status,
                'academic_status' => $academic_status,
                
                //models for addtional information tab
                'medicalConditions' => $medicalConditions,
                
                //models for addtional information tab
                'medicalConditions' => $medicalConditions,
                'general_work_experience' => $genral_work_experience,
                'references' => $references,
                'teaching' => $teaching,
                'nursing' => $nursing,
                'nursing_certification' => $nursing_certification,
                'nursinginfo' => $nursinginfo,
                'teachinginfo' => $teachinginfo,
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
                
                //models for transcript tab
                'iscape' => $is_cape,
                'person' => $person,
                'studentregistration' => $studentregistration,
                'applicant' => $applicant, 
                'student' => $student,
                'academicyearid' =>$academicyearid, 
                'academicofferingid' => $academicofferingid, 
                'programmename' => $programmename, 
                'cape_subjects' => $cape_subjects,
                'divisionid' => $divisionid,
                'cumulative_gpa' => $cumulative_gpa,
                'enrollments' => $enrollments,
                
                //models for holds tab
                'financial_holds' => $financial_holds,
                'academic_holds' => $academic_holds,
                'library_holds' => $library_holds, 
                
                //models for transfers tab
                'transfers' => $transfers,
                'deferrals' => $deferrals,
                'applicant_deferral' => $applicant_deferral,
                
                //models for awards and clubs
                'awards' => $awards,
                'awardDetails' => $awardDetails,
                'clubs' => $clubs,
                'clubDetails' => $clubDetails,
                
                //models for event
                'events' => $events,
                'dataProvider' => $dataProvider,
                
            ]);
        }
        
        
        /**
         * Updates 'General' section of Student Profile
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 28/12/2015
         * Date Last Modified
         */
        public function actionEditGeneral($personid, $studentregistrationid)
        {
            $applicant = Applicant::find()
                        ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            $student = Student::find()
                        ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            $studentregistration = StudentRegistration::find()
                    ->where(['studentregistrationid' => $studentregistrationid, 'isdeleted' => 0])
                    ->one();
            
            if ($applicant==true && $student==true && $studentregistration==true)
            {
                $student_profile = new StudentGeneralModel();
                $student_profile->transferInfo($applicant, $student, $studentregistrationid);   
            }
            else
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to retrieve initial models. Please try again.');
                return $this->redirect(['student-profile',
                    'personid' => $personid, 
                    'studentregistrationid' => $studentregistrationid,                     
                ]);
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                $student_profile_load_flag = false;
                $student_profile_validation_flag = false;
                
                $applicant_save_flag = false;
                
                $student_save_flag = false;
                
                $studentregistration_save_flag = false;
                
                $student_profile_load_flag = $student_profile->load($post_data); 
                if ($student_profile_load_flag == true)
                {
                    $student_profile_validation_flag = $student_profile->validate();
                    if ($student_profile_validation_flag == true)
                    {   
                        //put code to transer date to applicant and student model
                        $applicant->loadGeneral($student_profile);
                        $student->loadGeneral($student_profile);
                        $studentregistration->studentstatusid = $student_profile->studentstatusid;
                        
                        $transaction = \Yii::$app->db->beginTransaction();
                        try 
                        {                   
                            $applicant_save_flag = $applicant->save();
                            $student_save_flag = $student->save();
                            $studentregistration_save_flag = $studentregistration->save();
                            
//                            $string = "";
//                            if ($applicant_save_flag == false)
//                                $string.="applicant wrong + ";
//                            if ($student_save_flag == false)
//                                $string.="student wrong + ";
//                            if ($studentregistration_save_flag == false)
//                                $string.="student registration wrong + ";
                            
                            if ($applicant_save_flag == true  && $student_save_flag == true  &&  $studentregistration_save_flag == true)        //if save operations succeed
                            {
                                $transaction->commit();
                                return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
                            }
                            else
                            {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save information. Please try again.');
//                                Yii::$app->getSession()->setFlash('error', $string);
                    
                            }
                        } catch (Exception $e) 
                        {
                            $transaction->rollBack();
                        }
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate student_profile model. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load studet_profile model. Please try again.');
            }
            
            return $this->render('edit_general', [
                        'general' => $student_profile,
                        'personid' => $personid,
                        'studentregistrationid' => $personid, $studentregistrationid,
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
        public function actionEditExtracurricular($personid, $studentregistrationid)
        {
            $applicant = Applicant::find()
                        ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();

            $studentregistration = StudentRegistration::find()
                     ->where(['studentregistrationid' => $studentregistrationid, 'isdeleted' => 0])
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
                        return $this->redirect(['student-profile',
                                     'personid' => $personid, 
                                     'studentregistrationid' => $studentregistrationid,                     
                                 ]);
                    }
                    else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save applicant model. Please try again.');
                }
                else
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load applicant model. Please try again.');    
            }


            return $this->render('edit_extracurricular', [
                'studentregistration' => $studentregistration,
                'applicant' => $applicant
            ]);
        }
        
        
        /**
         * Updates 'Contact Details' section of Student Profile
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 28/12/2015
         * Date Last Modified
         */
        public function actionEditContactDetails($personid, $studentregistrationid)
        {
            $user = User::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $phone = Phone::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $email = Email::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $student = Student::find()
                        ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            
            if ($post_data = Yii::$app->request->post())
            {
                if ($phone == true && $email == true  && $student == true)
                {
                    //load flags
                    $phone_load_flag = false;
                    $email_load_flag = false;
                    $student_load_flag = false;
                    
                    //validation flags
                    $phone_valid_flag = false;
                    $email_valid_flag = false;
                    $student_valid_flag = false;
                    
                    //save flags
                    $phone_save_flag = false;
                    $email_save_flag = false;
                    $student_save_flag = false;
                    $user_save_flag = false;
                    
                    $phone_load_flag = $phone->load($post_data);
                    $email_load_flag = $email->load($post_data);
                    $student_load_flag = $student->load($post_data);
                    $user->email = $student->email;
                    
                    if ($phone_load_flag == true && $email_load_flag == true  && $student_load_flag == true)
                    {
                        $phone_valid_flag = $phone->validate();
                        $email_valid_flag = $email->validate();
                        $student_valid_flag = $student->validate();
                        
                        if ($phone_valid_flag == true && $email_valid_flag == true  && $student_valid_flag == true)
                        {
                            $transaction = \Yii::$app->db->beginTransaction();
                            try 
                            {
                                $phone_save_flag = $phone->save();
                                $email_save_flag = $email->save();
                                $student_save_flag = $student->save();
                                $user_save_flag = $user->save();
                                
                                if ($phone_save_flag == true && $email_save_flag == true  && $student_save_flag == true && $user_save_flag == true)
                                {
                                    $transaction->commit();
                                    return $this->redirect(['student-profile',
                                        'personid' => $personid, 
                                        'studentregistrationid' => $studentregistrationid,                     
                                    ]);
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
                        'phone' => $phone,
                        'email' => $email,
                        'student' => $student,
                        'studentregistrationid' => $studentregistrationid,
                        'personid' => $personid,
            ]);
        }
        
        
        /**
         * Updates 'Addresses' section of Student Profile
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 28/12/2015
         * Date Last Modified
         */
        public function actionEditAddresses($personid, $studentregistrationid)
        {
            $applicant = Applicant::find()
                        ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
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
                                    return $this->redirect(['student-profile',
                                        'personid' => $personid, 
                                        'studentregistrationid' => $studentregistrationid,                     
                                    ]);
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
                        'applicant' => $applicant,
                        'addresses' => $addresses,
                        'studentregistrationid' => $studentregistrationid,
                        'personid' => $personid,
            ]);        
        }      
        
        
        /**
         * Updates an optional relative
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 28/12/2015
         * Date Last Modified: 03/01/2016
         */
        public function actionEditOptionalRelative($personid, $studentregistrationid, $recordid)
        {
            $relative = Relation::find()
                        ->where(['relationid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            if ($relative == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to locate record. Please try again.');
                return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
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
                            return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
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
                        'personid' => $personid, 
                        'studentregistrationid' => $studentregistrationid,
                        'relative' => $relative,
                        'relation_name' => $relation_name,
            ]); 
        }
        
        
        /**
         * Deletes an optional relative 
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 03/01/2016
         * Date Last Modified: 03/01/2016
         */
        public function actionDeleteOptionalRelative($personid, $studentregistrationid, $recordid)
        {
            $relative = Relation::find()
                        ->where(['relationid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            if ($relative == true)
            {
                $save_flag = false;
                $relative->isdeleted = 1;
                $save_flag = $relative->save();
                if($save_flag == true)
                {
                    return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured deleting record. Please try again.');
                    return $this->redirect(['student-profile',
                                        'personid' => $personid, 
                                        'studentregistrationid' => $studentregistrationid,                     
                                    ]);
                }
            }
            
            else
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured locating record. Please try again.');
                return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
            }
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
         * Date Last Modified: 03/01/2016
         */
        public function actionEditCompulsoryRelative($personid, $studentregistrationid, $recordid)
        {
            $relative = CompulsoryRelation::find()
                        ->where(['compulsoryrelationid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            if ($relative == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to locate record. Please try again.');
                return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
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
                            return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
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
                        'personid' => $personid, 
                        'studentregistrationid' => $studentregistrationid,
                        'relative' => $relative,
                        'relation_name' => $relation_name,
            ]); 
        }
        
        
        /**
         * Creates an optional relation 
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 03/01/2016
         * Date Last Modified: 03/01/2016
         */
        public function actionAddOptionalRelative($personid, $studentregistrationid)
        {
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
                            return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
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
                        'personid' => $personid, 
                        'studentregistrationid' => $studentregistrationid,
                        'relative' => $relative,
                        'optional_relations' => $optional_relations,
            ]); 
        }
        
        
        /**
         * Deletes a medical condition
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 03/01/2016
         * Date Last Modified: 03/01/2016
         */
        public function actionDeleteMedicalCondition($personid, $studentregistrationid, $recordid)
        {
            $condition = MedicalCondition::find()
                        ->where(['medicalconditionid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            if ($condition == true)
            {
                $save_flag = false;
                $condition->isdeleted = 1;
                $save_flag = $condition->save();
                if($save_flag == true)
                {
                    return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured deleting medical condition record. Please try again.');
                    return $this->redirect(['student-profile',
                                        'personid' => $personid, 
                                        'studentregistrationid' => $studentregistrationid,                     
                                    ]);
                }
            }
            
            else
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured retrieving medical condition record. Please try again.');
                return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
            }
        }
        
        
        /**
         * Updates a medical condition
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 03/01/2016
         * Date Last Modified: 03/01/2016
         */
        public function actionEditMedicalCondition($personid, $studentregistrationid, $recordid)
        {
            $condition = MedicalCondition::find()
                        ->where(['medicalconditionid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            if ($condition == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to retrieve medical condition record. Please try again.');
                return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
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
                            return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
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
                        'personid' => $personid, 
                        'studentregistrationid' => $studentregistrationid,
                        'condition' => $condition,
            ]); 
        }
        
        
        /**
         * Creates a medical condition record 
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 03/01/2016
         * Date Last Modified: 03/01/2016
         */
        public function actionAddMedicalCondition($personid, $studentregistrationid)
        {
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
                            return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
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
                        'personid' => $personid, 
                        'studentregistrationid' => $studentregistrationid,
                        'condition' => $condition,
            ]); 
        }
        
        
        /**
         * Handles 'examination_body' dropdownlist of 'add_csecqualification' view
         * 
         * @param type $exam_body_id
         * 
         * Author: Laurence Charles
         * Date Created: 04/01/2016
         * Date Last Modified: 04/01/2016
         */
        public function actionExaminationBodyDependants($exam_body_id)
        {
            $subjects = Subject::getSubjectList($exam_body_id);      
            $proficiencies = ExaminationProficiencyType::getExaminationProficiencyList($exam_body_id);
            $grades = ExaminationGrade::getExaminationGradeList($exam_body_id);
            $pass = NULL;

            if (count($subjects)>0  && count($proficiencies)>0  && count($grades)>0)    //if subjects related to examination body exist
            {     
                $pass = 1;
                echo Json::encode(['subjects' => $subjects, 'proficiencies' => $proficiencies, 'grades' => $grades, 'pass' => $pass]);       //return json encoded array of subjects    
            }
            else
            {
                $pass = 0;
                echo Json::encode(['pass'=> $pass]);
            }    
        }
        
        
        /**
         * Creates a qualification record 
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 04/01/2016
         * Date Last Modified: 04/01/2016
         */
        public function actionAddQualification($personid, $studentregistrationid)
        {
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
                            return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
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
                        'personid' => $personid, 
                        'studentregistrationid' => $studentregistrationid,
                        'qualification' => $qualification,
            ]); 
        }
        
        
        /**
         * Deletes a qualification
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 04/01/2016
         * Date Last Modified: 04/01/2016
         */
        public function actionDeleteQualification($personid, $studentregistrationid, $recordid)
        {
            $qualification = CsecQualification::find()
                        ->where(['csecqualificationid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            if ($qualification == true)
            {
                $save_flag = false;
                $qualification->isdeleted = 1;
                $save_flag = $qualification->save();
                if($save_flag == true)
                {
                    return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured deleting qualification record. Please try again.');
                    return $this->redirect(['student-profile',
                                        'personid' => $personid, 
                                        'studentregistrationid' => $studentregistrationid,                     
                                    ]);
                }
            }            
            else
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured retrieving qualification record. Please try again.');
                return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
            }
        }
        
        
        /**
         * Updates a qualification record
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 04/01/2016
         * Date Last Modified: 04/01/2016
         */
        public function actionEditQualification($personid, $studentregistrationid, $recordid)
        {
            $qualification = CsecQualification::find()
                        ->where(['csecqualificationid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            
            if ($qualification == false)
            {          
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to retrieve qualification record. Please try again.');
                return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
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
                            return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
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
                        'personid' => $personid, 
                        'studentregistrationid' => $studentregistrationid,
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
        public function actionEditTechnicalQualifications($personid, $studentregistrationid)
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
                        return $this->redirect(['student-profile',
                                     'personid' => $personid, 
                                     'studentregistrationid' => $studentregistrationid,                     
                                 ]);
                    }
                    else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save applicant model. Please try again.');
                }
                else
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load applicant model. Please try again.');    
            }


            return $this->render('edit_technical_qualifications', [
                'studentregistrationid' => $studentregistrationid,
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
        public function actionPostSecondaryQualification($personid, $studentregistrationid, $action)
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
                        return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when deleting Post Secondarty Degree. Please try again.');
                    return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
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
                            return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
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
                'studentregistrationid' => $studentregistrationid,
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
        public function actionExternalQualification($personid, $studentregistrationid, $action)
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
                            return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
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
                'studentregistrationid' => $studentregistrationid,  
            ]); 
       }
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        /**
         * Deletes personinstitutiton record
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 04/01/2016
         * Date Last Modified: 04/01/2016
         */
        public function actionDeleteSchool($personid, $studentregistrationid, $recordid)
        {
            $school = PersonInstitution::find()
                    ->where(['personinstitutionid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            if ($school == true)
            {
                $save_flag = false;
                $school->isdeleted = 1;
                $save_flag = $school->save();
                if($save_flag == true)
                {
                    return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured deleting school record. Please try again.');
                    return $this->redirect(['student-profile',
                                        'personid' => $personid, 
                                        'studentregistrationid' => $studentregistrationid,                     
                                    ]);
                }
            }            
            else
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured retrieving school record. Please try again.');
                return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
            }
        }
        
        
        /**
         * Updates personinstitutiton record
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @param type $recordid
         * @param type $levelid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 05/01/2016
         * Date Last Modified: 05/01/2016
         */
        public function actionEditSchool($personid, $studentregistrationid, $recordid, $levelid)
        {
            $school = PersonInstitution::find()
                    ->where(['personinstitutionid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            
            if ($school == false)
            {          
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to retrieve institution record. Please try again.');
                return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
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
                            return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
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
                        'personid' => $personid, 
                        'studentregistrationid' => $studentregistrationid,
                        'school' => $school,
                        'levelid' => $levelid,
                        'school_name' => $school_name,
            ]);
        }
        
        
        /**
         * Adds new personinstitutiton record
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @param type $levelid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 05/01/2016
         * Date Last Modified: 05/01/2016
         */
        public function actionAddSchool($personid, $studentregistrationid, $levelid)
        {
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
                            return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
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
                        'personid' => $personid, 
                        'studentregistrationid' => $studentregistrationid,
                        'school' => $school,
                        'levelid' => $levelid,
            ]);
        }
        
        
        
        /**
         * Resolves a hold
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 06/01/2016
         * Date Last Modified: 06/01/2016
         */
        public function actionResolveHold($personid, $studentregistrationid, $recordid)
        {
            $hold = Hold::find()
                        ->where(['studentholdid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            if ($hold == true)
            {
                $save_flag = false;
                $hold->holdstatus = 0;
                
                $employeeid = Yii::$app->user->identity->personid;
                $hold->resolvedby = $employeeid;
                $hold->dateresolved = date("Y-m-d");
                
                $save_flag = $hold->save();
                if($save_flag == true)
                {
                    return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured resolving hold record. Please try again.');
                    return $this->redirect(['student-profile',
                                        'personid' => $personid, 
                                        'studentregistrationid' => $studentregistrationid,                     
                                    ]);
                }
            }            
            else
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured resolving hold record. Please try again.');
                return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
            }
        }
        
        
        /**
         * Reactivates a hold
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 06/01/2016
         * Date Last Modified: 06/01/2016
         */
        public function actionReactivateHold($personid, $studentregistrationid, $recordid)
        {
            $hold = Hold::find()
                        ->where(['studentholdid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            if ($hold == true)
            {
                $save_flag = false;
                $hold->holdstatus = 1;
                $employeeid = Yii::$app->user->identity->personid;
                $hold->appliedby = $employeeid;
                $hold->dateapplied = date("Y-m-d");
                
                $hold->resolvedby = NULL;
                $hold->dateresolved = NULL;
                
                $save_flag = $hold->save();
                if($save_flag == true)
                {
                    return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured reactivating hold record. Please try again.');
                    return $this->redirect(['student-profile',
                                        'personid' => $personid, 
                                        'studentregistrationid' => $studentregistrationid,                     
                                    ]);
                }
            }            
            else
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured reactivating hold record. Please try again.');
                return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
            }
        }
        
        
        /**
         * Creates a student_hold record 
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 06/01/2016
         * Date Last Modified: 06/01/2016
         */
        public function actionAddHold($personid, $studentregistrationid, $categoryid)
        {
            $hold = new Hold();
            $hold->studentregistrationid = $studentregistrationid;
            
            $employeeid = Yii::$app->user->identity->personid;
            $hold->appliedby = $employeeid;
            $hold->dateapplied = date("Y-m-d");
          
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $validation_flag = false;
                $save_flag = false;
                
                $load_flag = $hold->load($post_data);
                if($load_flag == true)
                {
                    $validation_flag = $hold->validate();
                    
                    if($validation_flag == true)
                    {
                        $save_flag = $hold->save();
                        if($save_flag == true)
                        {
                            return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
                        }
                        else
                            Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save hold record. Please try again.');
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate hold  record. Please try again.');
                }
                else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load hold  record. Please try again.');              
            }
            
            return $this->render('add_hold', [
                        'personid' => $personid, 
                        'studentregistrationid' => $studentregistrationid,
                        'hold' => $hold,
                        'categoryid' => $categoryid,
            ]); 
        }
        
        
        /**
         * Deletes Hold record
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 07/01/2016
         * Date Last Modified: 07/01/2016
         */
        public function actionDeleteHold($personid, $studentregistrationid, $recordid)
        {
            $hold = Hold::find()
                    ->where(['studentholdid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            if ($hold == true)
            {
                $save_flag = false;
                $hold->isdeleted = 1;
                
                //hold is resolved once it is deleted
                $hold->holdstatus = 0;
                $employeeid = Yii::$app->user->identity->personid;
                $hold->resolvedby = $employeeid;
                $hold->dateresolved = date("Y-m-d");
                
                $save_flag = $hold->save();
                if($save_flag == true)
                {
                    return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured deleting hold record. Please try again.');
                    return $this->redirect(['student-profile',
                                        'personid' => $personid, 
                                        'studentregistrationid' => $studentregistrationid,                     
                                    ]);
                }
            }            
            else
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured retrieving hold record. Please try again.');
                return $this->redirect(['student-profile',
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid,                     
                                ]);
            }
        }
        
        
        /**
         * Retrieves the academic offerins; essential for the dependant dropdown widget
         * 
         * @param type $division_id
         * @return array
         * 
         * Author: Laurence Charles
         * Date Created: 09/01/2016
         * Date Last Modified: 09/01/2016
         */
        public static function getAcademicOfferingList($division_id, $personid)
        { 
            $id = $personid;
            $intent = Applicant::getApplicantIntent($id);
            $db = Yii::$app->db;
            
            if ($intent == 1  || $intent == 4 || $intent == 6)       //if user is applying for full time programme
            {
                $programmetypeid = 1;   //used to identify full time programmes
            }
            
            else if ($intent == 2 || $intent ==3  || $intent ==5  || $intent ==7)      //if user is applying for part time
            {
                $programmetypeid = 2;  //will be used to identify part time programmes
            } 
            
            $records = $db->createCommand(
                    'SELECT academic_offering.academicofferingid, programme_catalog.name, programme_catalog.specialisation, qualification_type.abbreviation'
                    . ' FROM programme_catalog '
                    . ' JOIN academic_offering'
                    . ' ON programme_catalog.programmecatalogid = academic_offering.programmecatalogid'
                    . ' JOIN qualification_type'
                    . ' ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid' 
                    . ' JOIN application_period'
                    . ' ON academic_offering.applicationperiodid = application_period.applicationperiodid'
                    . ' JOIN academic_year'
                    . ' ON application_period.academicyearid = academic_year.academicyearid' 
                    . ' WHERE academic_offering.isactive=1'
                    . ' AND academic_offering.isdeleted=0'
                    . ' AND application_period.isactive = 1'
                    . ' AND academic_year.iscurrent = 1'
                    . ' AND academic_year.isdeleted = 0'
                    . ' AND programme_catalog.programmetypeid= ' . $programmetypeid
                    . ' AND programme_catalog.departmentid'
                    . ' IN ('
                    . ' SELECT departmentid'
                    . ' FROM department'
                    . ' WHERE divisionid = '. $division_id
                    . ' );'
                    )
                    ->queryAll();  
//            $records = $db->createCommand(
//                    'SELECT academic_offering.academicofferingid, programme_catalog.name, programme_catalog.specialisation, qualification_type.abbreviation'
//                    . ' FROM programme_catalog '
//                    . ' JOIN academic_offering'
//                    . ' ON programme_catalog.programmecatalogid = academic_offering.programmecatalogid'
//                    . ' JOIN qualification_type'
//                    . ' ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid' 
//                    . ' JOIN application_period'
//                    . ' ON academic_offering.applicationperiodid = application_period.applicationperiodid' 
//                    . ' WHERE academic_offering.isactive=1'
//                    . ' AND academic_offering.isdeleted=0'
//                     . ' AND application_period.iscomplete = 0'
//                    . ' AND application_period.isactive = 1'
//                    . ' AND programme_catalog.programmetypeid= ' . $programmetypeid
//                    . ' AND programme_catalog.departmentid'
//                    . ' IN ('
//                    . ' SELECT departmentid'
//                    . ' FROM department'
//                    . ' WHERE divisionid = '. $division_id
//                    . ' );'
//                    )
//                    ->queryAll();  

            $arr = array();
            foreach ($records as $record)
            {
                $combined = array();
                $keys = array();
                $values = array();
                array_push($keys, "id");
                array_push($keys, "name");
                $k1 = strval($record["academicofferingid"]);
                $k2 = strval($record["abbreviation"] . " " . $record["name"] . " " . $record["specialisation"]);
                array_push($values, $k1);
                array_push($values, $k2);
                $combined = array_combine($keys, $values);
                array_push($arr, $combined);
                $combined = NULL;
                $keys = NULL;
                $values = NULL;        
            }
            return $arr;  
        }
        
        
        /**
         * Encodes the academic offerins; essential for the dependant dropdown widget
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 09/01/2016
         * Date Last Modified: 09/01/2016
         */
        public function actionAcademicoffering($personid) 
        {
            $out = [];
            if (isset($_POST['depdrop_parents'])) 
            {
                $parents = $_POST['depdrop_parents'];
                if ($parents != null) {
                    $division_id = $parents[0];
                    $out = self::getAcademicOfferingList($division_id, $personid); 
                    echo Json::encode(['output'=>$out, 'selected'=>'']);
                    return;
                }
            }
            echo Json::encode(['output'=>'', 'selected'=>'']);
        }
        
        
        /**
         * Transfers a student
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 08/01/2016
         * Date Last Modified: 10/01/2016
         */
        public function actionAddTransfer($personid, $studentregistrationid)
        {
            $current_reg = StudentRegistration::find()
                    ->where(['studentregistrationid' => $studentregistrationid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $offer = Offer::find()
                    ->where(['offerid' => $current_reg->offerid, 'isdeleted' => 0])
                    ->one();
            $current_cape_subjects_names = array();                
            $current_cape_subjects = array();
            $current_application = $offer->getApplication()->one();
            $programme_record = ProgrammeCatalog::findOne(['programmecatalogid' => $current_application->getAcademicoffering()->one()->programmecatalogid]);
            $current_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $current_application->applicationid]);
            foreach ($current_cape_subjects as $cs)
            { 
                $current_cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
            }
            $current_programme = empty($current_cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $current_cape_subjects_names);
            
            date_default_timezone_set('America/St_Vincent');
            $selected = NULL;
            $capegroups = CapeGroup::getGroups();
            $groupCount = count($capegroups);
            $application = new Application();
            $transfer = new StudentTransfer();

            //Create blank records to accommodate capesubject-application associations
            $applicationcapesubject = array();
            for ($i = 0; $i < $groupCount; $i++)
            {
                $temp = new ApplicationCapesubject();
                //Values giving default value so as to facilitate validation (selective saving will be implemented)
                $temp->capesubjectid = 0;
                $temp->applicationid = 0;
                array_push($applicationcapesubject, $temp);
            }
            
            //Handles post request
            if ($post_data = Yii::$app->request->post())
            {
                //Application meodels flags
                $application_load_flag = false;
                $application_validation_flag = false;
                $application_save_flag = false;

                //ApplicatinonCapeSubject Flags
                $capesubject_load_flag = false;
                $capesubject_validation_flag = false;
                $capesubject_save_flag = false;
                
                //Transfer flags
                $transfer_load_flag = false;
                $transfer_validation_flag = false;
                $transfer_save_flag = false;
                
                //Register flag
                 $registration_save_flag = false;
                
                //load models
                $application_load_flag = $application->load($post_data);
                $transfer_load_flag = $transfer->load($post_data);
                        
                if($transfer_load_flag == true  &&  $application_load_flag == true)
                {
                    $registration = StudentRegistration::find()
                                ->where(['studentregistrationid' => $studentregistrationid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one();
                    
                    if($registration == true)
                    {
                        //updates application model
                        $application->personid = $personid;    
                        $application->applicationtimestamp = date('Y-m-d H:i:s' );
                        $application->submissiontimestamp = date('Y-m-d H:i:s' );
                        $application->ordering = Application::getNextApplicationID($personid);
                        $application->ipaddress = Yii::$app->request->getUserIP();
                        $application->browseragent = Yii::$app->request->getUserAgent();
                        $application->applicationstatusid = 9;
                        $application_validation_flag = $application->validate();

                        if ($application_validation_flag == true)
                        {
                            $transaction = \Yii::$app->db->beginTransaction();
                            try 
                            {
                                //inactivate old offer
                                $current_active_offer_save_flag = false;
                                $current_active_offer = Offer::find()
                                                    ->where(['offerid' => $registration->offerid, 'isactive' => 1, 'isdeleted' => 0])
                                                    ->one();
                                if ($current_active_offer == true)
                                {   
                                    $current_active_offer->revokedby = Yii::$app->user->identity->personid;
                                    $current_active_offer->revokedate = date('Y-m-d');
                                    $current_active_offer->isactive = 0;
                                    $current_active_offer_save_flag = $current_active_offer->save();

                                    if($current_active_offer_save_flag == true)
                                    {
                                        //inactivate old application
                                        $old_application_save_flag = false;
                                        $old_application = Application::find()
                                                        ->where(['applicationid' => $current_active_offer->applicationid, 'isactive' => 1 , 'isdeleted' => 0])
                                                        ->one();
                                        if($old_application == true)
                                        {
                                            $old_application->isactive = 0;
                                            $old_application_save_flag = $old_application->save();
                                            if($old_application_save_flag == true)
                                            {
                                                if (Application::isCape($old_application->academicofferingid) == true)
                                                {
                                                    $old_application_cape_records = ApplicationCapesubject::find()
                                                            ->where(['applicationid' => $old_application->applicationid, 'isdeleted' => 0])
                                                            ->all();
                                                    if ($old_application_cape_records == true)
                                                    {
                                                        foreach ($old_application_cape_records as $record)
                                                        {
                                                            $record->isactive = 0;
                                                            $record_save_flag = $record->save();
                                                            if ($record_save_flag == false)
                                                            {
                                                                $transaction->rollBack();
                                                                Yii::$app->getSession()->setFlash('error', 'Error deactiveing application-capesubject records... Please try again.');
                                                                return $this->render('add_transfer', [
                                                                        'personid' => $personid, 
                                                                        'studentregistrationid' => $studentregistrationid,
                                                                        'capegroups' => $capegroups,

                                                                        'application' => $application,
                                                                        'applicationcapesubject' =>  $applicationcapesubject,
                                                                        'transfer' => $transfer,
                                                                        ]);
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                $application_save_flag = $application->save();
                                                if ($application_save_flag == true)
                                                {
                                                    //Processes application_cape_subject models
                                                    $is_cape = Application::isCape($application->academicofferingid);
                                                    if ($is_cape == true)       //if application is for CAPE programme
                                                    {       
                                                        $capesubject_load_flag = Model::loadMultiple($applicationcapesubject, $post_data);
                                                        if ($capesubject_load_flag == true)
                                                        {
                                                            $capesubject_validation_flag = Model::validateMultiple($applicationcapesubject);

                                                            if ($capesubject_validation_flag == true)
                                                            {
                                                                //CAPE subject selection is only updated if 3-4 subjects have been selected
                                                                $selected = 0;
                                                                foreach ($applicationcapesubject as $subject) 
                                                                {
                                                                    if ($subject->capesubjectid != 0)           //if valid subject is selected
                                                                    {        
                                                                        $selected++;
                                                                    }
                                                                }

                                                                if($selected >= 2 && $selected <= 4)
                                                                {
                                                                    foreach ($applicationcapesubject as $subject) 
                                                                    {
                                                                        $subject->applicationid = $application->applicationid;      //updates applicationid

                                                                        if ($subject->capesubjectid != 0 && $subject->applicationid != 0 )       //if none is selected then reocrd should not be saved
                                                                        {        
                                                                            $capesubject_save_flag = $subject->save();
                                                                            if ($capesubject_save_flag == false)          //CapeApplicationSubject save operation succeeds
                                                                            {
                                                                                $transaction->rollBack();
                                                                                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save cape subject records. Please try again.');
                                                                                return $this->render('add_transfer', [
                                                                                            'personid' => $personid, 
                                                                                            'studentregistrationid' => $studentregistrationid,
                                                                                            'capegroups' => $capegroups,

                                                                                            'application' => $application,
                                                                                            'applicationcapesubject' =>  $applicationcapesubject,
                                                                                            'transfer' => $transfer,
                                                                                ]); 
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                else 
                                                                {   
                                                                    $transaction->rollBack();
                                                                    Yii::$app->getSession()->setFlash('error', 'You must select 2-4 subjects... Please try again.');
                                                                }
                                                            }
                                                            else
                                                            {
                                                                $transaction->rollBack();
                                                                Yii::$app->getSession()->setFlash('error', 'Error validating cape subject selection... Please try again.');
                                                            }
                                                        }
                                                        else
                                                        {
                                                            $transaction->rollBack();
                                                            Yii::$app->getSession()->setFlash('error', 'Error loading cape subject models... Please try again.');
                                                        }
                                                    }//end of isCape block

                                                    //Create new offer record
                                                    $new_offer_save_flag = false;
                                                    $new_offer = new Offer();
                                                    $new_offer->applicationid = $application->applicationid;
                                                    $new_offer->offertypeid = 1;
                                                    $new_offer->issuedby = Yii::$app->user->identity->personid;
                                                    $new_offer->issuedate = date('Y-m-d');
                                                    $new_offer->ispublished = 1;
                                                    $new_offer_save_flag = $new_offer->save();

                                                    if ($new_offer_save_flag == true)
                                                    {
                                                        $registration->offerid = $new_offer->offerid;   //associate new offer with student_registration
                                                        $registration->academicofferingid = $application->academicofferingid;
                                                        $registration_save_flag = $registration->save();
                                                        
                                                        if($registration_save_flag == true)
                                                        {
                                                            //update and validate transfer model
                                                            $transfer->studentregistrationid = $studentregistrationid;
                                                            $transfer->personid = $personid;
                                                            $transfer->transferofficer = Yii::$app->user->identity->personid;
                                                            $transfer->offerfrom = $current_active_offer->offerid;
                                                            $transfer->offerto = $new_offer->offerid;
                                                            $transfer->transferdate = date('Y-m-d');
                                                            $transfer_validation_flag = $transfer->validate();
                                                            
                                                            if ($transfer_validation_flag == true)
                                                            {
                                                                $transfer_save_flag = $transfer->save();
                                                                if($transfer_save_flag == true)
                                                                {
                                                                    if($application->divisionid != $old_application->divisionid)
                                                                   {
                                                                        $applicant = Applicant::find()
                                                                            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                                                                            ->one();
                                                                        if ($applicant)
                                                                        {
                                                                            $new_id = Applicant::preparePotentialStudentID($application->divisionid, $applicant->applicantid, "transfer");
                                                                            $applicant->potentialstudentid = $new_id;
                                                                            $applicant_save_flag = $applicant->save();
                                                                            if ($applicant_save_flag == true)
                                                                            {
                                                                                $user = User::find()
                                                                                        ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                                                                                        ->one();
                                                                                $user->username = $new_id;
                                                                                $user_save_flag = $user->save();
                                                                                if($user_save_flag == true)
                                                                                {
                                                                                    $transaction->commit();
                                                                                    return $this->redirect(['student-profile',
                                                                                        'personid' => $personid, 
                                                                                        'studentregistrationid' => $studentregistrationid,                     
                                                                                    ]);
                                                                                }
                                                                                else
                                                                                {
                                                                                    $transaction->rollBack();
                                                                                    Yii::$app->getSession()->setFlash('error', 'Error updateing username... Please try again.');
                                                                                }
                                                                            }
                                                                            else
                                                                            {
                                                                                $transaction->rollBack();
                                                                                Yii::$app->getSession()->setFlash('error', 'Error saving applicant record... Please try again.');
                                                                            }
                                                                        }
                                                                        else
                                                                        {
                                                                            $transaction->rollBack();
                                                                            Yii::$app->getSession()->setFlash('error', 'Error loading applicant record... Please try again.');
                                                                        }
                                                                   }
                                                                   else         //for transfers within the same division
                                                                   {
                                                                       $transaction->commit();
                                                                        return $this->redirect(['student-profile',
                                                                            'personid' => $personid, 
                                                                            'studentregistrationid' => $studentregistrationid,                     
                                                                        ]);
                                                                   }
                                                                }
                                                                else
                                                                {
                                                                    $transaction->rollBack();
                                                                    Yii::$app->getSession()->setFlash('error', 'Error saving transfer record... Please try again.');
                                                                }
                                                            }
                                                            else
                                                            {
                                                                $transaction->rollBack();
                                                                Yii::$app->getSession()->setFlash('error', 'Error validating transfer record... Please try again.');
                                                            }
                                                        }
                                                        else
                                                        {
                                                            $transaction->rollBack();
                                                            Yii::$app->getSession()->setFlash('error', 'Error updating registration record... Please try again.');
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $transaction->rollBack();
                                                        Yii::$app->getSession()->setFlash('error', 'Error saving new offer... Please try again.');
                                                    }
                                                }
                                                else
                                                {
                                                    $transaction->rollBack();
                                                    Yii::$app->getSession()->setFlash('error', 'Error saving new application... Please try again.');
                                                }
                                            }
                                            else
                                            {
                                                $transaction->rollBack();
                                                Yii::$app->getSession()->setFlash('error', 'Error updating old application... Please try again.');
                                            }
                                        }
                                        else
                                        {
                                            $transaction->rollBack();
                                            Yii::$app->getSession()->setFlash('error', 'Error retrieving old application... Please try again.');
                                        }
                                    }
                                    else
                                    {
                                        $transaction->rollBack();
                                        Yii::$app->getSession()->setFlash('error', 'Error saving old offer... Please try again.');
                                    }
                                }
                                else
                                {
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error retrieving old offer... Please try again.');
                                }
                            }catch (Exception $e) 
                            {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                            }
                        }
                        else
                        {
                            Yii::$app->getSession()->setFlash('error', 'Error validating new application. Please try again.');
                        }
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error retrieving registration record.... Please try again.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error loading transfer and application records... Please try again.');             
                }
            }//END POST
            
            return $this->render('add_transfer', [
                        'personid' => $personid, 
                        'studentregistrationid' => $studentregistrationid,
                        'current_programme' => $current_programme,
                
                        'capegroups' => $capegroups,
                        'application' => $application,
                        'applicationcapesubject' =>  $applicationcapesubject,
                        'transfer' => $transfer,          
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

           $student_registration = StudentRegistration::find()
                            ->where(['personid' => $personid, 'isdeleted' => 0])
                            ->one();
           $studentregistrationid = $student_registration->studentregistrationid;
           
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
                           return self::actionStudentProfile($user->personid, $studentregistrationid);
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
               'studentregistrationid' => $studentregistrationid,
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

           $student_registration = StudentRegistration::find()
                            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
           $studentregistrationid = $student_registration->studentregistrationid;
           
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
                   return self::actionStudentProfile($user->personid, $studentregistrationid);
               }
               else
                   Yii::$app->getSession()->setFlash('error', 'Error occured deleting record. Please try again.');              
           }            
           else
               Yii::$app->getSession()->setFlash('error', 'Error occured retrieving record. Please try again.');


           return self::actionStudentProfile($user->personid, $studentregistrationid);
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
       public function actionReference($personid, $recordid = NULL)
       {
           $user = User::find()
                   ->where(['personid' => $personid])
                   ->one();

           $student_registration = StudentRegistration::find()
                            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
           $studentregistrationid = $student_registration->studentregistrationid;
           
           $action = $recordid == NULL? "add" : "edit";
           $reference = $recordid == NULL? new Reference() : Reference::find()->where(['referenceid' => $recordid])->one();

           if ($post_data = Yii::$app->request->post())
           {
               $load_flag = false;
               $validation_flag = false;
               $save_flag = false;

               $load_flag = $reference->load($post_data);
               if($load_flag == true)
               {
                   if($action == "add")
                       $reference->personid = $personid;
                   
                   $validation_flag = $reference->validate();

                   if($validation_flag == true)
                   {
                       $save_flag = $reference->save();
                       if($save_flag == true)
                       {
                           return self::actionStudentProfile($user->personid, $studentregistrationid);
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


           return $this->render('reference', [
               'user' => $user,
               'personid' => $personid,
               'reference' => $reference,
               'action' => $action,
               'studentregistrationid' => $studentregistrationid,
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

           $student_registration = StudentRegistration::find()
                            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
           $studentregistrationid = $student_registration->studentregistrationid;
           
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
                           return self::actionStudentProfile($user->personid, $studentregistrationid);
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


           return $this->render('nurse_work_experience', [
               'user' => $user,
               'personid' => $personid,
               'nurseExperience' => $nurseExperience,
               'action' => $action,
               'studentregistrationid' => $studentregistrationid,
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

           $student_registration = StudentRegistration::find()
                            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
           $studentregistrationid = $student_registration->studentregistrationid;
           
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
                   return self::actionStudentProfile($user->personid, $studentregistrationid);
               }
               else
                   Yii::$app->getSession()->setFlash('error', 'Error occured deleting record. Please try again.');              
           }            
           else
               Yii::$app->getSession()->setFlash('error', 'Error occured retrieving record. Please try again.');


           return self::actionStudentProfile($user->personid, $studentregistrationid);
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

           $student_registration = StudentRegistration::find()
                            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
           $studentregistrationid = $student_registration->studentregistrationid;
           
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
                           return self::actionStudentProfile($user->personid, $studentregistrationid);
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
               'studentregistrationid' => $studentregistrationid,
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

           $student_registration = StudentRegistration::find()
                            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
           $studentregistrationid = $student_registration->studentregistrationid;
           
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
                   return self::actionStudentProfile($user->personid, $studentregistrationid);
               }
               else
                   Yii::$app->getSession()->setFlash('error', 'Error occured deleting record. Please try again.');              
           }            
           else
               Yii::$app->getSession()->setFlash('error', 'Error occured retrieving record. Please try again.');


           return self::actionStudentProfile($user->personid, $studentregistrationid);
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

           $student_registration = StudentRegistration::find()
                            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
           $studentregistrationid = $student_registration->studentregistrationid;
           
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
                           return self::actionStudentProfile($user->personid, $studentregistrationid);
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

           return self::actionStudentProfile($user->personid, $studentregistrationid);
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

           $student_registration = StudentRegistration::find()
                            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
           $studentregistrationid = $student_registration->studentregistrationid;
           
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
                           return self::actionStudentProfile($user->personid, $studentregistrationid);
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
               'studentregistrationid' => $studentregistrationid,
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

           $student_registration = StudentRegistration::find()
                            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
           $studentregistrationid = $student_registration->studentregistrationid;
           
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
                   return self::actionStudentProfile($user->personid, $studentregistrationid);
               }
               else
                   Yii::$app->getSession()->setFlash('error', 'Error occured deleting record. Please try again.');              
           }            
           else
               Yii::$app->getSession()->setFlash('error', 'Error occured retrieving record. Please try again.');


           return self::actionStudentProfile($user->personid, $studentregistrationid);
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

           $student_registration = StudentRegistration::find()
                            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
           $studentregistrationid = $student_registration->studentregistrationid;
           
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
                           return self::actionStudentProfile($user->personid, $studentregistrationid);
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

           return self::actionStudentProfile($user->personid, $studentregistrationid);
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
           
           $student_registration = StudentRegistration::find()
                            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
           $studentregistrationid = $student_registration->studentregistrationid;

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
                           return self::actionStudentProfile($user->personid, $studentregistrationid);
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


           return $this->render('criminal_record', [
               'user' => $user,
               'personid' => $personid,
               'criminalrecord' => $criminalrecord,
               'action' => $action,
               'studentregistrationid' => $studentregistrationid,
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
           
           $student_registration = StudentRegistration::find()
                            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
           $studentregistrationid = $student_registration->studentregistrationid;

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
                   return self::actionStudentProfile($user->personid, $studentregistrationid);
               }
               else
                   Yii::$app->getSession()->setFlash('error', 'Error occured deleting record. Please try again.');              
           }            
           else
               Yii::$app->getSession()->setFlash('error', 'Error occured retrieving record. Please try again.');

//           return self::actionApplicantProfile($user->username);
           return self::actionStudentProfile($user->personid, $studentregistrationid);
        }
        
        
        /**
         * Updates student card
         * 
         * @param type $personid
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 23/04/2016
         * Date Last Modified: 23/04/2016
         */
        public function actionEditStudentCard($personid, $recordid)
        {
            $save_flag = false;
            
            $user = User::find()
                   ->where(['personid' => $personid])
                   ->one();
           
            $reg = StudentRegistration::find()
                            ->where(['studentregistrationid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
            $studentregistrationid = $reg->studentregistrationid;
            
            if (Yii::$app->request->post())
            {
                $request = Yii::$app->request;
                $receivedpicture = $request->post('receivedpicture');
                $cardready = $request->post('cardready');
                $cardcollected = $request->post('cardcollected');
  
                $reg->receivedpicture = $receivedpicture;
                $reg->cardready = $cardready;
                $reg->cardcollected = $cardcollected;
                $save_flag = $reg->save();
                if ($save_flag == false)
                    Yii::$app->getSession()->setFlash('error', 'Error occured saving record. Please try again.');
                else
                    return self::actionStudentProfile($user->personid, $studentregistrationid);
            }
            
            return $this->render('edit_student_card', [
                    'user' => $user,
                    'personid' => $personid,
                    'reg' => $reg,
                ]);
        }
        
        
        /**
         * Delete an award assignment
         * 
         * @param type $personid
         * @param type $recordid
         * @param type $awardid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 28/04/2016
         * Date Last Modified: 28/04/2016
         */
        public function actionDeleteAward($personid, $recordid , $awardid)
        {
            $save_flag = false;
            
            $user = User::find()
                   ->where(['personid' => $personid])
                   ->one();
           
            $award_assignment = PersonAward::find()
                    ->where(['personawardid' => $awardid])
                    ->one();
            if(!$award_assignment)
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured finding record. Please try again.');
            }
            else
            {
                $award_assignment->isactive = 0;
                $award_assignment->isdeleted = 1;
                $save_flag = $award_assignment->save();
                if (!$save_flag)
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured deleting record. Please try again.');
                }
                else
                {
                    return self::actionStudentProfile($user->personid, $recordid);
                }
            }
        }
        
        /**
         * Creates/edits an award assignment
         * 
         * @param type $personid
         * @param type $recordid
         * @param type $action
         * @param type $awardid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 28/04/2016
         * Date Last Modified: 28/04/2016
         */
        public function actionAward($personid, $recordid, $action, $awardid=NULL)
        {
            $save_flag = false;
            
            $user = User::find()
                   ->where(['personid' => $personid])
                   ->one();
           
            $registration = StudentRegistration::find()
                            ->where(['studentregistrationid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
            if($action == "create")
            {
                $award_assignment = new PersonAward();
            }
            elseif($action == "edit")
            {
                $award_assignment = PersonAward::find()
                        ->where(['personawardid' => $awardid])
                        ->one();
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $validation_flag = false;
                $save_flag = false;

                $load_flag = $award_assignment->load($post_data);
                if($load_flag == true)
                {
                    $award_assignment->personid = $personid;
                    $award_assignment->studentregistrationid = $recordid;
                    $validation_flag = $award_assignment->validate();

                    if($validation_flag == true)
                    {
                        
                        $save_flag = $award_assignment->save();
                        if($save_flag == true)
                        {
                            return self::actionStudentProfile($user->personid, $recordid);
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
            
            return $this->render('assign_award', [
                    'award_assignment' => $award_assignment,
                    'action' => $action,
                    'personid' => $personid,
                    'studentregistrationid' => $recordid,
                ]);
        }
        
        
        /**
         * View an award details
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 28/04/2016
         * Date Last Modified: 28/04/2016
         */
        public function actionViewAward($personid, $studentregistrationid, $recordid)
        {
            $award = Award::find()
                        ->where(['awardid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            $name = $award->name;
            $description = $award->description;
            $awardcategory = AwardCategory::find()
                        ->where(['awardcategoryid' => $award->awardcategoryid])
                        ->one()
                        ->name;
            $awardtype = AwardType::find()
                        ->where(['awardtypeid' => $award->awardtypeid])
                        ->one()
                        ->name;
            
            $academicyear = ($award->awardtypeid == 2)? : false;
            
            $semester = ($award->awardtypeid == 1)? :false;
            
            $awardscope = AwardScope::find()
                        ->where(['awardscopeid' => $award->awardscopeid])
                        ->one()
                        ->name;
            
            $division = ($award->awardscopeid == 2)?
                        Division::find()
                            ->where(['divisionid' => $award->divisionid])
                            ->one()
                            ->name
                        : false;
            
            $department = ($award->awardscopeid == 3)?
                        Department::find()
                            ->where(['departmentid' => $award->departmentid])
                            ->one()
                            ->name
                        : false;
            
            $programme = ($award->awardscopeid == 4)?
                        ProgrammeCatalog::find()
                            ->where(['programmecatalogid' => $award->programmecatalogid])
                            ->one()
                            ->name
                        : false;
            
            $subject = $award->subject ? $award->subject: false;
                    
            return $this->render('view_award_details',
                    [
                        'award' => $award,
                        'name' => $name,
                        'description' => $description,
                        'awardcategory' => $awardcategory,
                        'awardtype' => $awardtype,
                        'academicyear' => $academicyear,
                        'semester' => $semester,
                        'awardscope' => $awardscope,
                        'division' => $division,
                        'department' => $department,
                        'programme' => $programme,
                        'subject' => $subject,
                        'personid' => $personid,
                        'studentregistrationid' => $studentregistrationid,
                    ]);
        }
        

        /**
         * Delete an club assignment
         * 
         * @param type $personid
         * @param type $recordid
         * @param type $clubid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 28/04/2016
         * Date Last Modified: 28/04/2016
         */
        public function actionDeleteClub($personid, $recordid , $clubid)
        {
            $save_flag = false;
            
            $user = User::find()
                   ->where(['personid' => $personid])
                   ->one();
           
            $club_assignment = ClubMember::find()
                    ->where(['clubmemberid' => $clubid])
                    ->one();
            
            $member_history = ClubMemberHistory::find()
                    ->where(['clubmemberid' => $club_assignment->clubmemberid, 'isactive'=> 1, 'isdeleted'=> 0])
                    ->all();
            
            if(!$club_assignment)
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured finding record. Please try again.');
            }
            else
            {
                $club_assignment->isactive = 0;
                $club_assignment->isdeleted = 1;
                $save_flag = $club_assignment->save();
                
                if (!$save_flag)
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured deleting record. Please try again.');
                }
                else
                {
                    if ($member_history)
                    {
                        foreach($member_history as $history)
                        {
                            $history->isactive = 0;
                            $history->isdeleted = 1;
                            $history->save();
                        }
                    }
                    return self::actionStudentProfile($user->personid, $recordid);
                }
            }
        }
        
        
        /**
         * Creates/edits an club assignment
         * 
         * @param type $personid
         * @param type $recordid
         * @param type $action
         * @param type $clubid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 28/04/2016
         * Date Last Modified: 28/04/2016
         */
        public function actionClub($personid, $recordid, $action, $clubid = NULL)
        {
            $save_flag = false;
            
            if($action == "create")
            {
                $club_assignment = new ClubMember();
            }
            elseif($action == "edit")
            {
                $club_assignment = ClubMember::find()
                        ->where(['clubmemberid' => $clubid])
                        ->one();
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $validation_flag = false;
                $save_flag = false;

                $load_flag = $club_assignment->load($post_data);
                if($load_flag == true)
                {
                    $club_assignment->personid = $personid;
                    $club_assignment->studentregistrationid = $recordid;
                    $validation_flag = $club_assignment->validate();

                    if($validation_flag == true)
                    {
                        $save_flag = $club_assignment->save();
                        if($save_flag == true)
                        {
                            return self::actionStudentProfile($personid, $recordid);
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
            
            return $this->render('assign_club', [
                    'club_assignment' => $club_assignment,
                    'action' => $action,
                    'personid' => $personid,
                    'studentregistrationid' => $recordid,
                ]);
        }
        
        
        /**
         * Updates club member's role
         * 
         * @param type $personid
         * @param type $recordid
         * @param type $clubid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 28/04/2016
         * Date Last Modified: 28/04/2016
         */
        public function actionUpdateClubRole($personid, $recordid, $clubid = NULL)
        {
            $member_history = new ClubMemberHistory();
            
            $save_flag = false;
            $history_save_flag = false;
            
            $club_assignment = ClubMember::find()
                    ->where(['clubmemberid' => $clubid])
                    ->one();
          
            if ($post_data = Yii::$app->request->post())
            {
                $transaction = \Yii::$app->db->beginTransaction();
                try 
                {
                    $load_flag = false;
                    $validation_flag = false;
                    $save_flag = false;

                    $member_history->clubid = $club_assignment->clubid;
                    $member_history->studentregistrationid = $personid;
                    $member_history->personid = $recordid;
                    $member_history->oldclubroleid = $club_assignment->clubroleid;

                    $load_flag = $club_assignment->load($post_data);
                    if($load_flag == true)
                    {
                        $validation_flag = $club_assignment->validate();

                        if($validation_flag == true)
                        {
                            $member_history->newclubroleid = $club_assignment->clubroleid;
                            $history_save_flag = $member_history->save();
                            $save_flag = $club_assignment->save();
                            
                            if($save_flag == true  && $history_save_flag ==true)
                            {
                                $transaction->commit();
                                return self::actionStudentProfile($personid, $recordid);
                            }
                            else
                            {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save record. Please try again.');
                            }
                        }
                        else
                            Yii::$app->getSession()->setFlash('error', 'Error occured when trying to validate record. Please try again.');
                    }
                    else
                            Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load record. Please try again.');              
                } catch (Exception $e) {
                    
                }
            }
            
            return $this->render('update_club_role', [
                    'club_assignment' => $club_assignment,
                    'member_history' => $member_history,
                    'personid' => $personid,
                    'studentregistrationid' => $recordid,
                ]);
        }
        
        
        /*
         *     
                            
                            
                            $member_history->clubid =
                            $member_history->studentregistrationid =xxx
                            $member_history->personid = xxx
                            $member_history->startdate = xxx
                            $member_history->enddate = 
                            $member_history->oldclubroleid = 
                            $member_history->newclubroleid

                            
                            
         */
        
        
        /**
         * View an club details
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 28/04/2016
         * Date Last Modified: 28/04/2016
         */
        public function actionViewClub($personid, $studentregistrationid, $recordid)
        {
            $club = Club::find()
                        ->where(['clubid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            $name = $club->name;
            $description = $club->description;
            $motto = $club->motto;
            $yearfounded = $club->yearfounded;
                    
            return $this->render('view_club_details',
                    [
                        'club' => $club,
                        'name' => $name,
                        'description' => $description,
                        'motto' => $motto,
                        'yearfounded' => $yearfounded,
                        'personid' => $personid,
                        'studentregistrationid' => $studentregistrationid,
                    ]);
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

            $student_registration = StudentRegistration::find()
                            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
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
                        return self::actionStudentProfile($user->personid, $student_registration->studentregistrationid);
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
                'studentregistrationid' => $student_registration->studentregistrationid,
            ]);
        }
        
        
        /**
         * Defers a student to a current academic_offering
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 08/01/2016
         * Date Last Modified: 10/01/2016 | 20/09/2016
         */
        public function actionAddDeferral($personid, $studentregistrationid, $type)
        {
            if ($type == "deferral")
            {
                $title = "Deferral Resumption";
            }
            elseif ($type == "start_new")
            {
                $title = "Registration Renewal";
            }
            
            $current_registration = StudentRegistration::find()
                    ->where(['studentregistrationid' => $studentregistrationid, 'isdeleted' => 0])
                    ->one();
            if($current_registration == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Error retrieving registration record.');
                return $this->redirect(\Yii::$app->request->getReferrer());
            }
                    
            $current_offer = Offer::find()
                    ->where(['offerid' => $current_registration->offerid, 'isdeleted' => 0])
                    ->one();
            if($current_offer == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Error retrieving offer record.');
                return $this->redirect(\Yii::$app->request->getReferrer());
            }
            
            $current_cape_subjects_names = array();                
            $current_cape_subjects = array();
            $current_application = $current_offer->getApplication()->one();
            $programme_record = ProgrammeCatalog::findOne(['programmecatalogid' => $current_application->getAcademicoffering()->one()->programmecatalogid]);
            $current_cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $current_application->applicationid]);
            foreach ($current_cape_subjects as $cs)
            { 
                $current_cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
            }
            $current_programme = empty($current_cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $current_cape_subjects_names);
            
            
            date_default_timezone_set('America/St_Vincent');
            $selected = NULL;
            $capegroups = CapeGroup::getGroups();
            $groupCount = count($capegroups);
            $new_application = new Application();
            $deferral = new StudentDeferral();

            //Create blank records to accommodate capesubject-application associations
            $applicationcapesubject = array();
            for ($i = 0; $i < $groupCount; $i++)
            {
                $temp = new ApplicationCapesubject();
                //Values giving default value so as to facilitate validation (selective saving will be implemented)
                $temp->capesubjectid = 0;
                $temp->applicationid = 0;
                array_push($applicationcapesubject, $temp);
            }
            
            //Handles post request
            if ($post_data = Yii::$app->request->post())
            {
                //Deferral flags
                $deferral_load_flag = false;
                $deferral_save_flag = false;
                
                //Register flags
                 $current_registration_save_flag = false;
                 $new_registration_save_flag = false;
                 
                $application_load_flag = $new_application->load($post_data);
                $deferral_load_flag = $deferral->load($post_data);
                        
                if($deferral_load_flag == true  &&  $application_load_flag == true)
                {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try 
                    {
                        /**********  current registration, applications, applicationcapesubject and offers made inactive ***********/
                        $current_registration->studentstatusid = 6;
                        $current_registration->isactive = 0;
                        $current_registration_save_flag = $current_registration->save();
                        if ($current_registration_save_flag == false)
                        {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error updating current registration record.');
                            return $this->redirect(\Yii::$app->request->getReferrer());
                        }
                        
                        $current_application_save_flag = false;
                        $current_application->isactive = 0;
                        $current_application_save_flag = $current_application->save();
                        if($current_application_save_flag == false)
                        {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error de-activating current application record.');
                            return $this->redirect(\Yii::$app->request->getReferrer());
                        }
                        
                        if (Application::isCape($current_application->academicofferingid) == true)
                        {
                            $current_application_cape_records = ApplicationCapesubject::find()
                                    ->where(['applicationid' => $current_application->applicationid, 'isdeleted' => 0])
                                    ->all();
                            if ($current_application_cape_records == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('error', 'Error retrieving capesubject records.');
                                return $this->redirect(\Yii::$app->request->getReferrer());
                            }
                            
                            foreach ($current_application_cape_records as $record)
                            {
                                $record->isactive = 0;
                                $record_save_flag = $record->save();
                                if ($record_save_flag == false)
                                {
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error de-activating capesubject records.');
                                    return $this->redirect(\Yii::$app->request->getReferrer());
                                }
                            }
                        }
                        
                        $current_offer_save_flag = false;
                        $current_offer->isactive = 0;
                        $current_offer_save_flag = $current_offer->save();
                        /******************************************************************************************/
                        
                        $new_application_load_flag = false;
                        $new_application_save_flag = false;
                        $capesubject_load_flag = false;
                        $capesubject_save_flag = false;
                
                        $new_application->personid = $personid;    
                        $new_application->applicationtimestamp = date('Y-m-d H:i:s' );
                        $new_application->submissiontimestamp = date('Y-m-d H:i:s' );
                        $new_application->ordering = Application::getNextApplicationID($personid);
                        $new_application->ipaddress = Yii::$app->request->getUserIP();
                        $new_application->browseragent = Yii::$app->request->getUserAgent();
                        $new_application->applicationstatusid = 9;
                      
                        $new_application_save_flag = $new_application->save();
                        if ($new_application_save_flag == false)
                        {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error retrieving registration record.');
                            return $this->redirect(\Yii::$app->request->getReferrer());
                        }
                        
                        //Processes application_cape_subject models
                        $is_cape = Application::isCape($new_application->academicofferingid);
                        if ($is_cape == true)       //if application is for CAPE programme
                        {       
                            $capesubject_load_flag = Model::loadMultiple($applicationcapesubject, $post_data);
                            if ($capesubject_load_flag == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('error', 'Error loading cape subjects.');
                                return $this->redirect(\Yii::$app->request->getReferrer());
                            }
                            
                            //CAPE subject selection is only updated if 3-4 subjects have been selected
                            $selected = 0;
                            foreach ($applicationcapesubject as $subject) 
                            {
                                if ($subject->capesubjectid != 0)           //if valid subject is selected
                                {        
                                    $selected++;
                                }
                            }
                            
                            if($selected < 2 && $selected > 4)
                            {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('error', 'You must select 2-4 subjects... Please try again.');
                                return $this->redirect(\Yii::$app->request->getReferrer());
                            }
                                    
                            foreach ($applicationcapesubject as $subject) 
                            {
                                $subject->applicationid = $new_application->applicationid;      //updates applicationid

                                if ($subject->capesubjectid != 0 && $subject->applicationid != 0 )  
                                {        
                                    $capesubject_save_flag = $subject->save();
                                    if ($capesubject_save_flag == false)          
                                    {
                                        $transaction->rollBack();
                                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save cape subject records. Please try again.');
                                         return $this->redirect(\Yii::$app->request->getReferrer());
                                    }
                                }
                            }  
                        }//end of isCape block
                       
                        //Create new offer record
                        $new_offer_save_flag = false;
                        $new_offer = new Offer();
                        $new_offer->applicationid = $new_application->applicationid;
                        $new_offer->offertypeid = 1;
                        $new_offer->issuedby = Yii::$app->user->identity->personid;
                        $new_offer->issuedate = date('Y-m-d');
                        $new_offer->ispublished = 1;
                        $new_offer_save_flag = $new_offer->save();

                        if ($new_offer_save_flag == false)
                        {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error occured saving offer.');
                             return $this->redirect(\Yii::$app->request->getReferrer());
                        }
                        
                        //Creates new  student registration
                        $new_registration = new StudentRegistration();
                        $new_registration->offerid = $new_offer->offerid;
                        $new_registration->personid = $personid;
                        $new_registration->academicofferingid = $new_application->academicofferingid;
                        $programme_catalog = ProgrammeCatalog::find()
                                ->innerJoin('academic_offering', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                                ->where(['programme_catalog.isdeleted' => 0,
                                                'academic_offering.academicofferingid' => $new_application->academicofferingid, 'academic_offering.isdeleted' => 0
                                            ])
                                ->one();
                        if ($programme_catalog == false)
                        {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error retrieving programme catalog.');
                             return $this->redirect(\Yii::$app->request->getReferrer());
                        }
                        
                        $new_registration->registrationtypeid = $programme_catalog->programmetypeid;
                        $new_registration->currentlevel = 1;
                        $new_registration->registrationdate = date('Y-m-d');
                        $new_registration_save_flag = $new_registration->save();

                        if ($new_registration_save_flag == false)
                        {   
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error saving new student registration record.');
                            return $this->redirect(\Yii::$app->request->getReferrer());
                        }
                        
                        //update and validate deferral model
                        $deferral->personid = $personid;
                        $deferral->deferralofficer = Yii::$app->user->identity->personid;
                        $deferral->registrationfrom = $current_registration->studentregistrationid;
                        $deferral->registrationto = $new_registration->studentregistrationid;
                        $deferral->deferraldate = date('Y-m-d');
                        $deferral_save_flag = $deferral->save();
                        
                        if ($deferral_save_flag == false)
                        {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error saving deferral record.');
//                            $errors = $deferral->getErrors();
//                            Yii::$app->getSession()->setFlash('error', 'Error saving deferral record.' . http_build_query($errors));
//                            $errors = "personid= " . $deferral->personid . "    officer= " .$deferral->deferralofficer
//                                    . "   old= " . $deferral->registrationfrom . "   new= " . $deferral->registrationto
//                                    . "   date= " . $deferral->deferraldate . "   details= " . $deferral->details;
//                            Yii::$app->getSession()->setFlash('error', 'Error saving deferral record.' . print_r($errors));
                            return $this->redirect(\Yii::$app->request->getReferrer());
                        }
                        
                        $transaction->commit();
                        return self::actionStudentProfile($personid, $new_registration->studentregistrationid);     
                        
                    }catch (Exception $e){
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                        return $this->redirect(\Yii::$app->request->getReferrer());
                    }
                }
            }//END POST
            
            
            return $this->render('add_deferral', [
                        'personid' => $personid, 
                        'studentregistrationid' => $studentregistrationid,
                        'current_programme' => $current_programme,
                
                        'capegroups' => $capegroups,
                        'new_application' => $new_application,
                        'applicationcapesubject' =>  $applicationcapesubject,
                        'deferral' => $deferral,    
                        'title' => $title,
            ]); 
        }
        
        
    }