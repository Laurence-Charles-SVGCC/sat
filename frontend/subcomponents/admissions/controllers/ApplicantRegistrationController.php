<?php
    namespace app\subcomponents\admissions\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\base\Model;
    use yii\data\ArrayDataProvider;

    use frontend\models\ApplicantRegistration;
    
    class ApplicantRegistrationController extends Controller
    {

        /**
        * Facilitates search for applicant registration account
        * 
        * @return type
        * 
        * Author: Laurence Charles
        * Date Created: 07/02/2017
        * Date Last Modified: 07/02/2017
        */
        public function actionIndex()
        {
            $dataProvider = array();
            $info_string = null;
            $data = array();
            
            $post_sent = Yii::$app->request->post();
            
            if (Yii::$app->request->post())
            {
                //Everytime a new search is initiated session variable must be removed
                 if (Yii::$app->session->get('email'))
                    Yii::$app->session->remove('email');

                $request = Yii::$app->request;
                $email = $request->post('email_field');

                if(Yii::$app->session->get('email') == null  && $email == true)
                    Yii::$app->session->set('email', $email);
            }
            else    
            {
                $email = Yii::$app->session->get('email');
            }
            
            $registrations = ApplicantRegistration::find()
                    ->where(['email' => $email])
                    ->all();
            
             if ($post_sent == true  &&  $email == false)
             {
                Yii::$app->getSession()->setFlash('error', 'A search criteria must be entered.');
            }
            elseif ($post_sent == true  && $registrations == false)
            {
                Yii::$app->getSession()->setFlash('error', 'No application registration account with that email has been found.');
            }
            elseif ($post_sent == true  && $registrations == true)
            {
                $info_string = $info_string .  " Email: " . $email;
                foreach ($registrations as $registration)
                {
                    $app['id'] = $registration->applicantregistrationid;
                    $app['applicantname'] = $registration->applicantname;
                    $app['title'] = $registration->title;
                    $app['firstname'] = $registration->firstname;
                    $app['lastname'] = $registration->lastname;
                    $app['email'] = $registration->email;
                    
                    $app['status'] = substr($registration->email, 0, 8) == 'removed_'? 'Deactivated' : 'Active';
                    
                    $data[] = $app;
                }
                
                $dataProvider = new ArrayDataProvider([
                        'allModels' => $data,
                        'pagination' => [
                            'pageSize' => 15,
                        ],
                        'sort' => [
                            'defaultOrder' => ['lastname' =>SORT_ASC, 'firstname' =>SORT_ASC,],
                            'attributes' => ['firstname', 'lastname', 'applicantname'],
                        ]
                ]); 
            }
           
            return $this->render('index', 
                    [
                    'dataProvider' => $dataProvider,
                    'info_string' => $info_string,
                    ]);
        }
        
        
        /**
         * Activate/Deactivate email address
         * 
         * @param type $id
         * @param type $action
         * @return type
         * 
         * Author: Laurence Charles
         * Date Creatred: 07/02/2017
         * Date LAst Modified: 07/02/2017
         */
        public function actionToggle($id, $action)
        {
            $registration = ApplicantRegistration::find()
                    ->where(['applicantregistrationid' => $id])
                    ->one();
            
            if ($registration == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Update unsuccessful - account could not be retrieved');
            }
            else
            {
                if ($action == "deactivate")
                {
//                    $new_email = "removed_" . $registration->email;
//                    $registration->email = $new_email;
                    $registration->email = "removed_" . $registration->email;
                }
                elseif($action == "activate")
                {
                    $registration->email = substr($registration->email, 8);
                }
                        
                if ($registration->save() == false)
                {
                    Yii::$app->getSession()->setFlash('error', 'Update failed');
                }
                else
                {
                    Yii::$app->getSession()->setFlash('success', 'Account deactivation was successful');
                }
            }
            return self::actionIndex();
        }
        
        
        
        
        
        
        
    }