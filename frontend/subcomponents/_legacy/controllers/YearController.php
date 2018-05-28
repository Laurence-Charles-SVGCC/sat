<?php
    namespace app\subcomponents\legacy\controllers;
  
    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\web\Controller;
    use yii\data\ArrayDataProvider;
    use yii\helpers\Json;
    
    use frontend\models\LegacySubject;
    use frontend\models\LegacySubjectType;
    use frontend\models\LegacyYear;
    use frontend\models\LegacyTerm;
    use frontend\models\LegacyBatch;
    use frontend\models\LegacyLevel;
    use frontend\models\Employee;


    class YearController extends Controller
    {

        /**
         * Renders academic year listings
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 10/07/2016
         * Date LAst Modified: 10/07/2016 | 22/0/2017
         */
        public function actionIndex()
        {
            if (Yii::$app->user->can('manageLegacyYears') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
           
            $dataProvider = NULL;
            $year_container = array();
            $year_info = array();

            $years = LegacyYear::find()
                    ->where(['isactive' => 1, 'isdeleted' => 0])
                    ->orderBy('name ASC')
                    ->all();

            foreach ($years as $year)
            {
                $year_info['yearid'] = $year->legacyyearid;
                $year_info['name'] = $year->name;
                $year_info['createdby'] = Employee::getEmployeeName($year->createdby);
                $year_info['datecreated'] = $year->datecreated;
                $year_info['lastmodifiedby'] = Employee::getEmployeeName($year->lastmodifiedby);
                $year_info['datemodified'] = $year->datemodified;
               
                $year_container[] = $year_info;
            }

            $dataProvider = new ArrayDataProvider([
                        'allModels' => $year_container,
                        'pagination' => [
                            'pageSize' => 25,
                        ],
                        'sort' => [
                            'defaultOrder' => ['name' => SORT_ASC],
                            'attributes' => [ 'name'],
                        ]
                ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        }
        
        
        
        /**
         * Creates a 'LegacyYear" record and the associated "LegacyBatch" records
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 10/07/2016
         * Date LAst Modified: 10/07/2016 | 22/03/2017
         */
        public function actionCreate()
        {
            if (Yii::$app->user->can('manageLegacyYears') == false)
            {
                 Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $years = LegacyYear::find()
                    ->where(['isactive' => 1, 'isdeleted' => 0])
                    ->orderBy('name ASC')
                    ->all();
            
            $saved_years = array();
            foreach ($years as $record)
            {
                $saved_years[] = $record->name;
            }
            
            $year = new LegacyYear();
            
            if ($post_data = Yii::$app->request->post())
            {
                $operation_success = true;
                $transaction = \Yii::$app->db->beginTransaction();
                try 
                {
                    $year_load_flag = false;
                    $year_save_flag = false;
                
                    $load_flag = $year->load($post_data);
                    if($load_flag == true)
                    {
                        $employeeid = Yii::$app->user->identity->personid;
                        $date = date('Y-m-d');
                        
                        $year->createdby = $employeeid;
                        $year->datecreated = $date;
                        $year->lastmodifiedby =$employeeid ;
                        $year->datemodified = $date;
                        $save_flag = $year->save();
                        
                        if($save_flag == true)
                        {
                            $terms = array();
                            
                            $term1  = new LegacyTerm();
                            $term1->legacyyearid = $year->legacyyearid;
                            $term1->name = "Term1"; 
                            $term1->ordering = 1;
                            $term1_save_flag = $term1->save();
                            if($term1_save_flag == false)
                            {
                                $operation_success = false;
                                $transaction->rollback();
                                Yii::$app->getSession()->setFlash('error', 'Error occured saving term1 record.');
                            }
                            else
                            {
                                $terms[] = $term1;
                                $term2  = new LegacyTerm();
                                $term2->legacyyearid = $year->legacyyearid;
                                $term2->name = "Term2"; 
                                $term2->ordering = 2;
                                $term2_save_flag = $term2->save();
                                if($term2_save_flag == false)
                                {
                                    $operation_success = false;
                                    $transaction->rollback();
                                    Yii::$app->getSession()->setFlash('error', 'Error occured saving term2 record.');
                                }
                                else
                                {
                                    $terms[] = $term2;
                                    $term3  = new LegacyTerm();
                                    $term3->legacyyearid = $year->legacyyearid;
                                    $term3->name = "Term3"; 
                                    $term3->ordering = 3;
                                    $term3_save_flag = $term3->save();
                                    if($term3_save_flag == false)
                                    {
                                        $operation_success = false;
                                        $transaction->rollback();
                                        Yii::$app->getSession()->setFlash('error', 'Error occured saving term3 record.');
                                    }
                                    else
                                    {
                                        $terms[] = $term3;
                                        //create legacy batch
                                        foreach($terms as $term)
                                        {
                                            $subjects = LegacySubject::find()
                                                    ->where(['isactive' => 1, 'isdeleted' => 0])
                                                    ->all();
                                            foreach($subjects as $subject)
                                            {
                                                $levels = LegacyLevel::find()
                                                        ->where(['isactive' => 1, 'isdeleted' => 0])
                                                        ->all();
                                                foreach($levels as $level)
                                                {
                                                    $batch = new LegacyBatch();
                                                    $batch->legacytermid = $term->legacytermid;
                                                    $batch->legacysubjectid = $subject->legacysubjectid;
                                                    $batch->legacylevelid = $level->legacylevelid;
                                                    $batch->name = $term->name . "-" . $subject->name . "-" . $level->name . " Batch";
                                                    $batch->createdby = $employeeid;
                                                    $batch->datecreated = $date;
                                                    $batch->lastmodifiedby =$employeeid ;
                                                    $batch->datemodified = $date;
                                                    $batch_save_flag = $batch->save();
                                                    if($batch_save_flag == false)
                                                    {
                                                        $operation_success = false;
                                                        break;
                                                        $transaction->rollback();
                                                        Yii::$app->getSession()->setFlash('error', 'Error occured saving term batch record.');
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        else
                        {
                             $operation_success = false;
                            $transaction->rollback();
                            Yii::$app->getSession()->setFlash('error', 'Error occured saving year record.');
                        }
                        if( $operation_success = true)
                        {
//                            Yii::$app->getSession()->setFlash('success', 'Test pass.');
                            $transaction->commit();
                            return self::actionIndex();
                        }
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured loading year record.');
                    }  
                } catch (Exception $ex) {
                    Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                }
            }
            
            return $this->render('create_year',
                    [
                        'year' => $year,
                         'saved_years' => $saved_years,
                    ]);
        }
        
        
        /**
         * 'Soft' deletes LegacyBatch record
         * 
         * @return type
         * 
         * Author: Laurence Chrles
         * Date Created: 09/07/2016
         * Date Last Modified: 10/07/2016 | 22/03/2017
         */
        public function actionDeleteYear($id)
        {
            if (Yii::$app->user->can('manageLegacyYears') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                $year = LegacyYear::find()
                        ->where(['legacyyearid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                $year->isactive = 0;
                $year->isdeleted = 1;
                $save_flag = $year->save();
                if($save_flag == false)
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when deleting year.');
                }
            }
            return self::actionIndex();  
        }
        
        
        /**
         * Returns a JSON formatted listing of LegacyTerm records
         * 
         * @param type $subjecttypeid
         * 
         * Author: Laurence Charles
         * Date Created: 12/07/2016
         * Date Last Modified: 12/07/2016
         */
        public function actionGetListing($yearid) 
        {
            $terms = LegacyTerm::find()
                    ->where(['legacyyearid' => $yearid, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
           
            $listing = array();
            foreach ($terms as $term) 
            {
                $combined = array();
                $keys = array();
                $values = array();
                array_push($keys, "id");
                array_push($keys, "name");
                $k1 = strval($term->legacytermid);
                $k2 = strval($term->name);
                array_push($values, $k1);
                array_push($values, $k2);
                $combined = array_combine($keys, $values);
                array_push($listing, $combined);
                $combined = NULL;
                $keys = NULL;
                $values = NULL;
            }
            
            if ($listing) 
            {
                $found = 1;
                echo Json::encode(['found' => $found, 'terms' => $listing]);
            } 
            else 
            {
                $found = 0;
                echo Json::encode(['found' => $found]);
            }
        }
    }
 

