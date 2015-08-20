<?php

namespace app\subcomponents\admissions\controllers;

use Yii;
use common\models\User;
use frontend\models\Applicant;
use frontend\models\Application;
use yii\data\ArrayDataProvider;
use frontend\models\ProgrammeCatalog;
use frontend\models\ApplicationCapesubject;
use frontend\models\Offer;
use yii\helpers\Url;
use frontend\models\PersonInstitution;
use frontend\models\Institution;
use frontend\models\Phone;
use frontend\models\Email;
use frontend\models\Relation;
use frontend\models\ApplicationHistory;

class ViewApplicantController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index',
            [
                'results' => Null,
                'result_users' => Null,
                'info_string' => '',
            ]);
    }
    
    /*
    * Purpose: Collect search parameters and display results of an applicant search.
    * Created: 1/08/2015 by Gamal Crichton
    * Last Modified: 1/08/2015 by Gamal Crichton
    */
    public function actionSearchApplicant()
    {
        $dataProvider = $app_ids = NULL;
        $info_string = "";
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            $app_id = $request->post('id');
            $firstname = $request->post('firstname');
            $lastname = $request->post('lastname');
            
            if ($app_id)
            {
                $user = User::findOne(['username' => $app_id, 'isdeleted' => 0]);
                 $cond_arr['personid'] = $user ? $user->personid : NULL;
                 $info_string = $info_string .  " Applicant ID: " . $app_id;
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
            
            if (empty($cond_arr))
            {
                Yii::$app->getSession()->setFlash('error', 'A search criteria must be entered.');
            }
            else
            {
                $cond_arr['isdeleted'] = 0;  
            
                $applicants = Applicant::find()->where($cond_arr)->all();
                if (empty($applicants))
                {
                    Yii::$app->getSession()->setFlash('error', 'No user found matching this criteria.');
                }
                else
                {
                    $data = array();
                    foreach ($applicants as $applicant)
                    {
                        $app = array();
                        $user = $applicant->getPerson()->one();
                        
                        $app['username'] = $user ? $user->username : '';
                        $app['applicantid'] = $applicant->applicantid;
                        $app['firstname'] = $applicant->firstname;
                        $app['middlename'] = $applicant->middlename;
                        $app['lastname'] = $applicant->lastname;
                        $app['gender'] = $applicant->gender;
                        $app['dateofbirth'] = $applicant->dateofbirth;
                        $data[] = $app;
                    }
                    $dataProvider = new ArrayDataProvider([
                        'allModels' => $data,
                        'pagination' => [
                            'pageSize' => 100,
                        ],
                        'sort' => [
                            'attributes' => ['applicantid', 'firstname', 'lastname'],
                            ]
                    ]);
                    if (!$user)
                    {
                        Yii::$app->session->setFlash('error', 'User not found');
                    }
                }
        }
    }
    return $this->render('index', 
        [
            'results' => $dataProvider,
            'result_users' => $app_ids,
            'info_string' => $info_string,
        ]);
  }
  
  /*
    * Purpose: Retrieve information necessary to display results of an applicant search.
    * Created: 1/08/2015 by Gamal Crichton
    * Last Modified: 1/08/2015 by Gamal Crichton
    */
  public function actionViewApplicant($applicantid, $username = '')
  {
      $applicant = Applicant::findOne(['applicantid' => $applicantid]);
      $personid = $applicant->getPerson()->one() ? $applicant->getPerson()->one()->personid : NULL;
      $applications = $personid ? Application::findAll(['personid' => $personid, 'isdeleted' => 0]) : array();
      $data = array();
        foreach($applications as $application)
        {
            $app_details = array();
            $app_his = ApplicationHistory::find()->where(['applicationid' => $application->applicationid,
                'isdeleted' => 0])->orderBy('applicationhistoryid DESC', 'desc')->one();
            $cape_subjects_names = array();
            $programme = ProgrammeCatalog::find()
                ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->where(['application.applicationid' => $application->applicationid])->one();
            $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $application->applicationid]);
            foreach ($cape_subjects as $cs) { $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; }
            $offer = Offer::findOne(['applicationid' => $application->applicationid, 'isdeleted' => 0]);

            $app_details['order'] = $application->ordering;
            $app_details['applicationid'] = $application->applicationid;
            $app_details['programme_name'] = $programme->getFullName();
            $app_details['subjects'] = implode(' ,', $cape_subjects_names);
            $app_details['offerid'] = $offer ? $offer->offerid : Null;
            $app_details['divisionid'] = $application->divisionid;
            $app_details['application_status'] = $app_his ? $app_his->applicationstatusid > 1 ? "Submitted" : "Incomplete" : Null; 

            $data[] = $app_details;
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
      
      return $this->render('view-applicant', 
              [
                  'applicant' => $applicant,
                  'dataProvider' => $dataProvider,
                  'username' => $username,
              ]);
  }
  
  /*
    * Purpose: Junction for various action to eb done to an applicant after an applicant search.
    * Created: 3/08/2015 by Gamal Crichton
    * Last Modified: 6/08/2015 by Gamal Crichton
    */
  public function actionApplicantActions()
  {
      if (Yii::$app->request->post())
      {
          $request = Yii::$app->request;
          $applicantusername = $request->post('applicantusername');
          if ($request->post('register') === '')
          {
              return $this->redirect(Url::to(['register-student/register-applicant', 'applicantusername' => $applicantusername]));
          }
          if ($request->post('view_personal') === '')
          {
              return $this->redirect(Url::to(['view-applicant/view-personal', 'applicantusername' => $applicantusername]));
          }
          if ($request->post('edit_personal') === '')
          {
              return $this->redirect(Url::to(['view-applicant/edit-personal', 'applicantusername' => $applicantusername]));
          }
          
      }
  }
  
  /*
    * Purpose: Prepares applicant personal information for viewing only
    * Created: 6/08/2015 by Gamal Crichton
    * Last Modified: 12/08/2015 by Gamal Crichton
    */
  public function actionViewPersonal($applicantusername)
  {
      //$app_info = self::getApplicantDetails($applicantusername);
      $user = User::findOne(['username' =>$applicantusername]);
      $applicant = $user ? Applicant::findOne(['personid' =>$user->personid]) : Null;
      $institutions = $applicant ? PersonInstitution::findAll(['personid' => $applicant->personid, 'isdeleted' => 0]) : array();
      //$in = Institution::findone(['institutionid' => $institution->institutionid, 'isdeleted' => 0]);
      $phone = $user ? Phone::findOne(['personid' =>$user->personid]) : NULL;
      $email = $user ? Email::findOne(['personid' =>$user->personid]) : NULL;
      $relations = $user ? Relation::findAll(['personid' =>$user->personid]) : NULL;
      
      if (!$applicant)
      {
          Yii::$app->session->setFlash('error', 'No details found for this applicant.');
      }
      
      return $this->render('view-applicant-details',
              [
                  'username' => $user ? $user->username : '',
                  'applicant' => $applicant,
                  'institutions' => $institutions,
                  'phone' => $phone,
                  'email' => $email,
                  'relations' => $relations,
              ]);
  }
  
  /*
    * Purpose: Prepares applicant personal information for editing
    * Created: 6/08/2015 by Gamal Crichton
    * Last Modified: 6/08/2015 by Gamal Crichton
    */
  public function actionEditPersonal($applicantusername)
  {
      if (Yii::$app->request->post())
      {
          //$institutions = new PersonInstitution();
          $request = Yii::$app->request;
          $applicant = Applicant::findOne(['applicantid' => $request->post('applicantid')]);
          if ($applicant->load(Yii::$app->request->post()) )
          { 
              if ($applicant->save())
              {
                  Yii::$app->session->setFlash('success', 'Applicant updated successfully');
                  $this->redirect(Url::to(['view-applicant/view-personal', 'applicantusername' =>$request->post('username')]));
              }
              Yii::$app->session->setFlash('error', 'Applicant could not be saved');
          }
          else
          {
              Yii::$app->session->setFlash('error', 'Applicant not found');
          } 
      }
      $user = User::findOne(['username' =>$applicantusername]);
      $applicant = $user ? Applicant::findOne(['personid' =>$user->personid]) : Null;
      $institutions = $applicant ? PersonInstitution::findAll(['personid' => $applicant->personid, 'isdeleted' => 0]) : array();
      
      if (!$applicant)
      {
          Yii::$app->session->setFlash('error', 'No details found for this applicant.');
      }
      
      return $this->render('edit-applicant-details',
              [
                  'username' => $user ? $user->username : '',
                  'applicant' => $applicant,
                  'institutions' => $institutions,
              ]);
  }
  
  private function getApplicantDetails($applicantusername)
  {
      $user = User::findOne(['username' =>$applicantusername]);
      $applicant = $user ? Applicant::findOne(['personid' =>$user->personid]) : Null;
      if ($applicant)
      {
          $institutions = PersonInstitution::findAll(['personid' => $applicant->personid, 'isdeleted' => 0]);
          
          $app['applicantid'] = $applicant->applicantid;
          $app['username'] = $user->username;
          $app['title'] = $applicant->title;
          $app['firstname'] = $applicant->firstname;
          $app['middlename'] = $applicant->middlename;
          $app['lastname'] = $applicant->lastname;
          $app['gender'] = $applicant->gender;
          $app['dateofbirth'] = $applicant->dateofbirth;
          $app['nationality'] = $applicant->nationality;
          $app['placeofbirth'] = $applicant->placeofbirth;
          $app['religion'] = $applicant->religion;
          $app['sponsor'] = $applicant->sponsorname;
          $app['clubs'] = $applicant->clubs;
          $app['otherinterests'] = $applicant->otherinterests;
          $app['maritalstatus'] = $applicant->maritalstatus;
          $app['institution'] = array();
          foreach ($institutions as $key => $institution)
          {
              $in = Institution::findone(['institutionid' => $institution->institutionid, 'isdeleted' => 0]);
              $app['institution'][$key]['name'] = $in ? $in->name : '';
              $app['institution'][$key]['formername'] = $in ? $in->formername : '';
              $app['institution'][$key]['startdate'] = $institution->startdate;
              $app['institution'][$key]['enddate'] = $institution->enddate;
              $app['institution'][$key]['hasgraduated'] = $institution->hasgraduated;
          }
          return $app;
      }
      return Null;
  }

}
