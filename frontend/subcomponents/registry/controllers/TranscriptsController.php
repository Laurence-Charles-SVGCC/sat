<?php

    namespace app\subcomponents\registry\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\helpers\Url;
    use yii\data\ArrayDataProvider;


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


    class TranscriptsController extends Controller
    {

        public function actionManageTranscripts()
        {
            return $this->render('transcripts_panel',
                    [

                    ]);
        }

    }

