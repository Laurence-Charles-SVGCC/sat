<?php

/* 
 * Contoller for Student Profile views.
 * Author: Laurence Charles
 * Date Created: 01/05/2015
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
    use yii\web\UploadedFile;
    use yii\helpers\FileHelper;
    use yii\web\Response;
    
    use common\models\User;
    use frontend\models\Event;
    use frontend\models\EventType;
    use frontend\models\SickLeave;
    use frontend\models\MaternityLeave;
    use frontend\models\MiscellaneousEvent;
    use frontend\models\DisciplinaryAction;
    use frontend\models\EventAttachment;
    
    
    
    class LogController extends Controller
    {
        
        /**
         * View event 
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @param type $eventid
         * @param type $eventtypeid
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 01/05/2016
         * Date Last Modified: 01/05/2016
         */
        public function actionEventDetails($personid, $studentregistrationid, $eventid, $eventtypeid, $recordid)
        {
            
            $event = Event::find()
                    ->where(['eventid' => $eventid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one(); 
            
            if($eventtypeid == 1)
            {
                $event_details = SickLeave::find()
                        ->where(['sickleaveid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            }
            
            elseif($eventtypeid == 2)
            {
                $event_details = MaternityLeave::find()
                        ->where(['maternityleaveid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            }
            
            elseif($eventtypeid == 3)
            {
                $event_details = MiscellaneousEvent::find()
                        ->where(['miscellaneouseventid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            }
            
            elseif($eventtypeid == 4)
            {
                $event_details = DisciplinaryAction::find()
                        ->where(['disciplinaryactionid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            }
            
            $username = User::getUser($personid)->username;
            $saved_documents = Event::getDocuments($username, $studentregistrationid, $eventtypeid, $recordid);
            
            return $this->render('view_event', 
                                [
                                    'event' => $event,
                                    'event_details' => $event_details,
                                    'recordid' => $recordid,
                                    'personid' => $personid,
                                    'studentregistrationid' => $studentregistrationid,
                                    'saved_documents' => $saved_documents,
                                ]);
        }
        
        
        /**
         * Edit an event
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @param type $eventid
         * @param type $eventtypeid
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 01/05/2016
         * Date Last Modified: 01/05/2016
         */
        public function actionEditEvent($personid, $studentregistrationid, $eventid, $eventtypeid, $recordid)
        {
            $load_flag = false;
            $save_flag1 = false;
            $save_flag2 = false;
            
            $event = Event::find()
                    ->where(['eventid' => $eventid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one(); 
            
            if($eventtypeid == 1)
            {
                $event_details = SickLeave::find()
                        ->where(['sickleaveid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                $event_type = " Sick Leave ";
            }
            
            elseif($eventtypeid == 2)
            {
                $event_details = MaternityLeave::find()
                        ->where(['maternityleaveid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                $event_type = " Maternity Leave ";
            }
            
            elseif($eventtypeid == 3)
            {
                $event_details = MiscellaneousEvent::find()
                        ->where(['miscellaneouseventid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                $event_type = " Miscellaneous ";
            }
            
            elseif($eventtypeid == 4)
            {
                $event_details = DisciplinaryAction::find()
                        ->where(['disciplinaryactionid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                $event_type = " Disciplinary Action ";
            }
            
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = $event_details->load($post_data);
                if($load_flag == false)
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when loading record.');
                }
                else 
                {
                    $save_flag1 = $event_details->save();
                    if($save_flag1 == false)
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured when saving record details.');
                    }
                    else
                    {
                        $event->date = date('Y-m-d');
                        $event->summary = $event_details->summary;
                        $save_flag2 = $event->save();
                        if($save_flag2 == false)
                        {
                            Yii::$app->getSession()->setFlash('error', 'Error occured when saving record.');
                        }
                        else
                        {
                            return $this->redirect(['profile/student-profile',
                                                'personid' => $personid,
                                                'studentregistrationid' => $studentregistrationid,
                                            ]);
                        }
                    }
                }
            }
            
            return $this->render('edit_event', 
                                [
                                    'event' => $event,
                                    'event_details' => $event_details,
                                    'event_type' => $event_type,
                                    'recordid' => $recordid,
                                    'personid' => $personid,
                                    'studentregistrationid' => $studentregistrationid,
                                ]);
        }
        
        
        /**
         * Delete an event
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @param type $eventid
         * @param type $eventtypeid
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 01/05/2016
         * Date Last Modified: 01/05/2016
         */
        public function actionDeleteEvent($personid, $studentregistrationid, $eventid, $eventtypeid, $recordid)
        {
            $event = Event::find()
                    ->where(['eventid' => $eventid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one(); 
            
            if($eventtypeid == 1)
            {
                $event_details = SickLeave::find()
                        ->where(['sickleaveid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            }
            
            elseif($eventtypeid == 2)
            {
                $event_details = MaternityLeave::find()
                        ->where(['maternityleaveid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            }
            
            elseif($eventtypeid == 3)
            {
                $event_details = MiscellaneousEvent::find()
                        ->where(['miscellaneouseventid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            }
            
            elseif($eventtypeid == 4)
            {
                $event_details = DisciplinaryAction::find()
                        ->where(['disciplinaryactionid' => $recordid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
            }
            
            $transaction = \Yii::$app->db->beginTransaction();
            try 
            {
                $event_details->isactive = 0;
                $event_details->isdeleted = 1;
                $save_flag1 = $event_details->save();
                if ($save_flag1 == false)
                {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occured when deleting record.');
                }
                else 
                {
                    $event->isactive = 0;
                    $event->isdeleted = 1;
                    $save_flag2 = $event->save();
                    if ($save_flag2 == false)
                    {
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash('error', 'Error occured when deleting record.');
                    }
                    else 
                    {
                        $transaction->commit();
                        return $this->redirect(['profile/student-profile',
                                                'personid' => $personid,
                                                'studentregistrationid' => $studentregistrationid,
                                            ]);
                    }
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
        
        
        /**
         * Create an event
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @param type $action
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 01/05/2016
         * Date Last Modified: 01/05/2016
         */
        public function actionCreateEvent($personid, $studentregistrationid, $action)
        {
            $load_flag = false;
            $save_flag1 = false;
            $save_flag2 = false;
            
            $event = new Event(); 
            
            if($action == "sick-leave")
            {
                $event_details = new SickLeave();
                $event_type = " Sick Leave ";
                $event_typeid = 1;
            }
            
            elseif($action == "maternity-leve")
            {
                $event_details = new MaternityLeave();
                $event_type = " Maternity Leave ";
                $event_typeid = 2;
            }
            
            elseif($action == "miscellaneous")
            {
                $event_details = new MiscellaneousEvent();
                $event_type = " Miscellaneous ";
                $event_typeid = 3;
            }
            
            elseif($action == "disciplinary")
            {
                $event_details = new DisciplinaryAction();
                $event_type = " Disciplinary Action ";
                $event_typeid = 4;
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = $event_details->load($post_data);
                if($load_flag == false)
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when loading record.');
                }
                else 
                {
                    $event_details->studentregistrationid = $studentregistrationid;
                    $save_flag1 = $event_details->save();
                    if($save_flag1 == false)
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured when saving record details.');
                    }
                    else
                    {
                        $event->eventtypeid = $event_typeid;
                        $event->studentregistrationid = $studentregistrationid;
                        $event->recordid = $event_details->getPrimaryKey();
                        $event->date = date('Y-m-d');
                        $event->summary = $event_details->summary;
                        $save_flag2 = $event->save();
                        if($save_flag2 == false)
                        {
                            Yii::$app->getSession()->setFlash('error', 'Error occured when saving record.');
                        }
                        else
                        {
                            
                            /*
                             * create directory for attachments
                             */
                            $username = User::getUser($personid)->username;
                            
                            if ($event_typeid == 1)
                                $event_type = "sick_leave";
                            elseif ($event_typeid == 2)
                                $event_type = "maternity_leave";
                            elseif ($event_typeid == 3)
                                $event_type = "miscellaneous";
                            elseif ($event_typeid == 4)
                                $event_type = "disciplinary_action";
                            
                            $dir =  Yii::getAlias('@frontend') . "/files/student_records/" . $username . "/" . $studentregistrationid . "/events/" . $event_type . "/" . $event_details->getPrimaryKey();
                            
                            $package_success = false;
                            $file = new FileHelper();
                            
                            $package_success = $file->createDirectory($dir, 509, true);
                            if ($package_success == false) 
                                Yii::$app->getSession()->setFlash('error', 'Error creating package folder. Please contact system administrator.');
                            
                            return $this->redirect(['profile/student-profile',
                                                'personid' => $personid,
                                                'studentregistrationid' => $studentregistrationid,
                                            ]);
                        }
                    }
                }
            }
            
            return $this->render('create_event', 
                                [
                                    'event' => $event,
                                    'event_type' => $event_type,
                                    'event_details' => $event_details,
                                    'personid' => $personid,
                                    'studentregistrationid' => $studentregistrationid,
                                ]);
        }
        
        
        /**
         * Renders attachment upload view
         * 
         * @param type $recordid
         * @param type $count
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 02/05/2016
         * Date Last Modified: 02/05/2016
         */
        public function actionAttachDocuments($personid, $studentregistrationid, $eventid, $recordid)
        {
            $username = User::getUser($personid)->username;
            
            $eventtypeid = Event::find()
                    ->where(['eventid' => $eventid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one()
                    ->eventtypeid;
            
            $model = new EventAttachment();
            $model->username = $username;
            $model->studentregistrationid = $studentregistrationid;
            $model->eventtypeid = $eventtypeid;
            $model->record_id = $recordid;
            
            $saved_documents = Event::getDocuments($username, $studentregistrationid, $eventtypeid, $recordid);
            
            if (Yii::$app->request->isPost) 
            {
                $model->files = UploadedFile::getInstances($model, 'files');
              
                if ($model->upload())   // file is uploaded successfully
                {
                    return self::actionEventDetails($personid, $studentregistrationid, $eventid, $eventtypeid, $recordid);
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'You have exceeded you stipulated attachment count.');              
                }
            }

            return $this->render('attach_document', 
                                [
                                    'model' => $model,
                                    'recordid' => $recordid,
                                    'saved_documents' => $saved_documents,
                                    'personid' => $personid, 
                                    'studentregistrationid' => $studentregistrationid, 
                                    'eventid' => $eventid, 
                                    'eventtypeid' => $eventtypeid,
                                    'recordid' => $recordid,
                                ]
            );
        }
        
        
        /**
         * Deletes an document attached to an event
         * 
         * @param type $recordid
         * @param type $count
         * @param type $index
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 02/05/2016
         * Date Last Modified: 02/05/2016
         */
        public function actionDeleteAttachment($index, $personid, $studentregistrationid, $eventid, $eventtypeid, $recordid)
        {
            $username = User::getUser($personid)->username;
            
            $files = Event::getDocuments($username, $studentregistrationid, $eventtypeid, $recordid);
            
            foreach ($files as $key => $file)
            {
                if ($key == $index)
                {
                    unlink($file);
                }
                
            }
            return self::actionEventDetails($personid, $studentregistrationid, $eventid, $eventtypeid, $recordid);
        }
        
        
        /**
         * Downlaod event attachment
         * 
         * @param type $personid
         * @param type $studentregistrationid
         * @param type $eventid
         * @param type $eventtypeid
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 02/05/2016
         * Date Last Modified: 02/05/2016
         */
        public function actionDownloadEventAttachment($index, $personid, $studentregistrationid, $eventid, $eventtypeid, $recordid)
        {   
            $username = User::getUser($personid)->username;
            
            $files = Event::getDocuments($username, $studentregistrationid, $eventtypeid, $recordid);
            
            foreach ($files as $key => $file)
            {
                if ($key == $index)
                {
                    Yii::$app->response->sendFile($file, "Download");
                    Yii::$app->response->send();
                }
                
            }
            
//            $username = User::getUser($personid)->username;
//            
//            if ($event_typeid == 1)
//                $event_type = "sick_leave";
//            elseif ($event_typeid == 2)
//                $event_type = "maternity_leave";
//            elseif ($event_typeid == 3)
//                $event_type = "miscellaneous";
//            elseif ($event_typeid == 4)
//                $event_type = "disciplinary_action";
//                            
//            $dir = Yii::getAlias('@frontend') . "/files/student_records/" . $username . "/" . $studentregistrationid . "/events/" . $event_type . "/" . $recordid . "/";
//            
//            
//            
//            
//            $path = Url::to('../../common/files/application/dne');
//            
//            $path = Url::to('../../common/files/application/dne');
//            
//            $file = $path . '/medical_card1.pdf';
//            
//            if(file_exists($file))
//            {
//                Yii::$app->response->sendFile($file, "Download");
//                Yii::$app->response->send();
//            }
//            else
//            {
//                Yii::$app->getSession()->setFlash('error', 'Error occrued when downloading Medical Card 1....Please try again.');
//            }
            
            return self::actionEventDetails($personid, $studentregistrationid, $eventid, $eventtypeid, $recordid);
        }
        
        
        
    }