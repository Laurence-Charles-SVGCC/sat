<?php
    namespace app\subcomponents\applications\controllers;

    use Yii;
    use yii\filters\VerbFilter;
    use frontend\models\provider_builders\ApplicantRegistrationProviderBuilder;
    use frontend\models\ApplicantRegistration;
    
     class ReviewApplicationsController extends \yii\web\Controller
    {
        public function behaviors()
        {
            return [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post'],
                    ],
                ],
            ];
        }


        /**
        * Renders ApplicantRegistration search form and process search  by email request
        * 
        * @return 'applicant_search_by_email' view
        * 
        * Author: charles.laurence1@gmil.com
        * Created: 2017_10_04
        * Modified: 2017_10_12
        */
       public function actionFindApplicantByEmail()
       {
            if (Yii::$app->user->can('System Administrator') == false && Yii::$app->user->can('Registrar') == false)
            {
                throw new UnauthorizedAccessException();
            }
            
           $info_string = "";
           $email = NULL;
           $dataProvider = array();

           if (Yii::$app->request->post())
           {
               //Everytime a new search is initiated session variable must be removed
                if (Yii::$app->session->get('email'))
                {
                   Yii::$app->session->remove('email');
                }

               $request = Yii::$app->request;
               $email = $request->post('email_field');

               if(Yii::$app->session->get('email') == null  && $email == true)
               {
                   Yii::$app->session->set('email', $email);
               } 
           }
           else    
           {
               $email = Yii::$app->session->get('email');
           }


           if (Yii::$app->request->post() && ($email == NULL  || strcmp($email, "") == 0))
           {
               Yii::$app->getSession()->setFlash('error', 'A search criteria must be entered.');
           }
           elseif ( $email != NULL  && strcmp($email, "") != 0)
           {
               $info_string = $info_string .  " Email: " . $email;
               $dataProvider = ApplicantRegistrationProviderBuilder::generateApplicantRegistrationByEmail($email, 50);
               if ($dataProvider->count > 0)
               {
                   Yii::$app->getSession()->setFlash('success', $dataProvider->count . ' applicant account(s) found.');
               }
               else
               {
                   Yii::$app->getSession()->setFlash('error', 'No applicant found matching this criteria.');
               }
           }

           return $this->render('applicant_search_by_email', [
               'dataProvider' => $dataProvider,
               'info_string' => $info_string
           ]);
       }
       
       
       /**
        * Renders ApplicantRegistration search form and process search  by applicantid request
        * 
        * @return 'applicant_search_by_email' view
        * 
        * Author: charles.laurence1@gmil.com
        * Created: 2017_10_04
        * Modified: 2017_10_12
        */
       public function actionFindApplicantByApplicantid()
       {
            if (Yii::$app->user->can('System Administrator') == false && Yii::$app->user->can('Registrar') == false)
            {
                throw new UnauthorizedAccessException();
            }
            
           $info_string = "";
           $applicantid = NULL;
           $dataProvider = array();

           if (Yii::$app->request->post())
           {
               //Everytime a new search is initiated session variable must be removed
                if (Yii::$app->session->get('applicantid'))
                {
                   Yii::$app->session->remove('applicantid');
                }

               $request = Yii::$app->request;
               $applicantid = $request->post('applicantid_field');

               if(Yii::$app->session->get('applicantid') == null  && $applicantid == true)
               {
                   Yii::$app->session->set('applicantid', $applicantid);
               } 
           }
           else    
           {
               $applicantid = Yii::$app->session->get('applicantid');
           }


           if (Yii::$app->request->post() && ($applicantid == NULL  || strcmp($applicantid, "") == 0))
           {
               Yii::$app->getSession()->setFlash('error', 'A search criteria must be entered.');
           }
           elseif ( $applicantid != NULL  && strcmp($applicantid, "") != 0)
           {
               $info_string = $info_string .  " Email: " . $applicantid;
               $dataProvider = ApplicantRegistrationProviderBuilder::generateApplicantRegistrationByApplicantID($applicantid, 50);
               if ($dataProvider->count > 0)
               {
                   Yii::$app->getSession()->setFlash('success', $dataProvider->count . ' applicant account(s) found.');
               }
               else
               {
                   Yii::$app->getSession()->setFlash('error', 'No applicant found matching this criteria.');
               }
           }

           return $this->render('applicant_search_by_applicantid', [
               'dataProvider' => $dataProvider,
               'info_string' => $info_string
           ]);
       }
       
       
       /**
        * Renders ApplicantRegistration search form and process search  by applicantid request
        * 
        * @return 'applicant_search_by_email' view
        * 
        * Author: charles.laurence1@gmil.com
        * Created: 2017_10_04
        * Modified: 2017_10_12
        */
       public function actionFindApplicantByName()
       {
            if (Yii::$app->user->can('System Administrator') == false && Yii::$app->user->can('Registrar') == false)
            {
                throw new UnauthorizedAccessException();
            }
            
           $info_string = "";
           $search_criteria = array();
           $dataProvider = array();

           if (Yii::$app->request->post())
           {
               //Everytime a new search is initiated session variable must be removed
                if (Yii::$app->session->get('firstname'))
                {
                    Yii::$app->session->remove('firstname');
                }

                if (Yii::$app->session->get('lastname'))
                {
                    Yii::$app->session->remove('lastname');
                }

                $request = Yii::$app->request;
                $firstname = $request->post('fname_field');
                $lastname = $request->post('lname_field');

               if(Yii::$app->session->get('firstname') == null  && $firstname == true)
               {
                    Yii::$app->session->set('firstname', $firstname);
               }
            
                if(Yii::$app->session->get('lastname') == null  && $lastname == true)
                {
                    Yii::$app->session->set('lastname', $lastname);
                }
           }
           else    
           {
                $firstname = Yii::$app->session->get('firstname');
                $lastname = Yii::$app->session->get('lastname');
           }


           if (Yii::$app->request->post() && ($firstname == NULL || strcmp($firstname,"") == 0)  && ($lastname == NULL || strcmp($lastname,"") == 0) )
           {
               Yii::$app->getSession()->setFlash('error', 'A search criteria must be entered.');
           }
           elseif ( ($firstname != NULL && strcmp($firstname,"") != 0)  || ($lastname != NULL && strcmp($lastname,"") != 0) )
           {
               if ($firstname)
                {
                    $search_criteria['firstname'] = $firstname;
                    $info_string = $info_string .  " First Name: " . $firstname; 
                }
                if ($lastname)
                {
                    $search_criteria['lastname'] = $lastname;
                    $info_string = $info_string .  " Last Name: " . $lastname;
                }

               $dataProvider = ApplicantRegistrationProviderBuilder::generateApplicantRegistrationByName($search_criteria, 50);
               if ($dataProvider->count > 0)
               {
                   Yii::$app->getSession()->setFlash('success', $dataProvider->count . ' applicant account(s) found.');
               }
               else
               {
                   Yii::$app->getSession()->setFlash('error', 'No applicant found matching this criteria.');
               }
           }

           return $this->render('applicant_search_by_name', [
               'dataProvider' => $dataProvider,
               'info_string' => $info_string
           ]);
       }
       
       
       /**
        * Resends email with account creation token
        * 
        * Author: charles.laurence1@gmil.com
        * Created: 2018_02_28
        * Modified: 2018_02_28
        */
       public function actionResendVerificationEmail($id)
       {
           $applicant_registration = ApplicantRegistration::find()
                   ->where(['applicantregistrationid' => $id])
                   ->one();
           
            $transaction = \Yii::$app->db->beginTransaction();
            try 
            {
                $applicant_registration->token = $applicant_registration->generateToken();
                if ($applicant_registration->save() == false)
                {
                    Yii::$app->getSession()->setFlash('danger', 'Error occured resetting token.');
                }
                else
                {
                    $email_transmission_feedback = $applicant_registration->sendApplicantAccountRequestEmail();
                    if ($email_transmission_feedback == false)
                    {
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash('danger', 'Error occured sending email verification.');
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('success ', 'Verification email sent successfully.');
                        $transaction->commit();
                    }
                }
            }catch (Exception $ex) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('danger', 'Error occured processing request.');
            }
           
           return $this->redirect(\Yii::$app->request->getReferrer());
       }
    
    
            
}
    
    
    
