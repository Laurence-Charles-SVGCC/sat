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
    
    use frontend\models\Event;
    use frontend\models\EventType;
    use frontend\models\SickLeave;
    use frontend\models\MaternityLeave;
    use frontend\models\MiscellaneousEvent;
    use frontend\models\DisciplinaryAction;
    
    
    
    class LogController extends Controller
    {
        
        
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
            
            
            if ($post_data = Yii::$app->request->post())
            {
                
            }
            
            return $this->render('view_event', 
                                [
                                    'event' => $event,
                                    'event_details' => $event_details,
                                    'recordid' => $recordid,
                                    'personid' => $personid,
                                    'studentregistrationid' => $studentregistrationid,
                                ]);
        }
        
        
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
//                            return self::actionStudentProfile($personid, $studentregistrationid);
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
//                        return self::actionStudentProfile($personid, $studentregistrationid);
                    }
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
        
        
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
//                            return self::actionStudentProfile($personid, $studentregistrationid);
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
        
        
        
    }