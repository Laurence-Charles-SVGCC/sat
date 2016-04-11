<?php

    namespace app\subcomponents\admissions\controllers;
/* 
 * Controls all actions necessary to create and configure packages
 * for offers and rejections
 * 
 * Author: Laurence Charles
 * Date Created: 09/04/2016
 */

    use Yii;
    use yii\web\Controller;
    use yii\base\Model;
    
    use common\models\User;
    use frontend\models\ApplicationPeriod;
    use frontend\models\Package;
    use frontend\models\PackageDocument;
    use frontend\models\PackageType;
    
    
    class PackageController extends Controller
    {

        /**
         * Renders the Packages Summary which displays a record of all fully-configured
         * packages
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 11/04/2016
         * Date Last Modified: 11/04/2016
         */
        public function actionIndex()
        {
            $packages = Package::getPackages();
        
            if (count($packages) == 0)
                $container = false;
            else
            {
                $container = array();

                $keys = array();
                array_push($keys, 'id');
                array_push($keys, 'package_name');
                array_push($keys, 'period_name');
                array_push($keys, 'division');
                array_push($keys, 'year');
                array_push($keys, 'type');
                array_push($keys, 'progress');
                array_push($keys, 'created_by');
                array_push($keys, 'last_modified_by');
                array_push($keys, 'start_date');
                array_push($keys, 'completion_date');
                array_push($keys, 'document_count');


                foreach ($packages as $package)
                {
                    $values = array();
                    $row = array();
                    array_push($values, $package["id"]);
                    array_push($values, $package["package_name"]);
                    array_push($values, $package["period_name"]);
                    array_push($values, $package["division"]);
                    array_push($values, $package["year"]);
                    array_push($values, $package["type"]);
                    array_push($values, $package["progress"]);
                    array_push($values, $package["created_by"]);
                    array_push($values, $package["last_modified_by"]);
                    array_push($values, $package["start_date"]);
                    array_push($values, $package["completion_date"]);
                    array_push($values, $package["document_count"]);
                    $row = array_combine($keys, $values);
                    array_push($container, $row);

                    $values = NULL;
                    $row = NULL;
                }
            }
            
            return $this->render('packages_summary', 
            [
                'packages' => $container,
            ]);
        }
        
        
        /**
         * Reneders the Package Summary for a pending Package
         * 
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 11/04/2016
         * Date Last Modified: 11/04/2016
         */
        public function actionInitiatePackage($recordid = NULL)
        {
            if ($recordid == NULL)
            {
                $recordid = false;
                return $this->render('package_dashboard',
                    [
                        'recordid' => $recordid,
                    ]);
            }
            else
            {
                $package = Package::find()
                        ->where(['packageid' => $recordid, 'isactive' => 1])
                        ->one();
                
                return $this->render('package_dashboard', 
                [
                    'recordid' => $recordid,
                    'package' => $package,
                ]);
            }
        }
        
        
        /**
         * Processes a package depending on the action parameter;
         * -> if 'recordid==NULL', new package record is created,
         * -> else, current package configurations are edited,
         * 
         * @param type $action
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 11/04/2016
         * Date Last Modified: 11/04/2016
         */
        public function actionInitializePackage($action, $recordid = NULL)
        {
            if ($recordid == NULL)
            {
                $package = new Package();
            }
            else
            {
                $package = Package::find()
                            ->where(['packageid' => $recordid, 'isactive' => 1])
                            ->one();
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                
                $load_flag = $package->load($post_data);
                if($load_flag == true)
                { 
                    /*
                     * If record is new the packageprogressid is set to 1 and creatorby field entered,
                     * else packageprogressid is not changed
                     */
                    if($package->packageid == NULL)
                    {
                        $package->packageprogressid = 1;
                        $package->createdby = Yii::$app->user->getID();
                        $package->datestarted = date('Y-m-d');
                    } 
                    $package->lastmodifiedby = Yii::$app->user->getID();
                    
                    $save_flag = $package->save();
                    if($save_flag == true)
                    { 
                        return $this->redirect(['period-setup-step-three']);
                    }
                    else
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to save package record. Please try again.');
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load package record. Please try again.');              
            }
            
            
            return $this->render('configure_package', 
                                [
                                    'package' => $package,
                                ]);
        }
        
        
        /**
         * Deletes package configuration record
         * 
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 11/04/2016
         * Date Last Modified: 11/04/2016
         */
        public function actionDeletePackage($recordid)
        {
            $package_save_flag = false;
            $document_save_flag = false;
            
            $transaction = \Yii::$app->db->beginTransaction();
            try 
            {
                $package = Package::find()
                        ->where(['packageid' => $recordid, 'isactive' => 1])
                        ->one();
                $package->isactive = 0;
                $package->isdeleted = 1;
                $package_save_flag = $package->save();
                if ($package_save_flag == false)
                {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occurred when deleting package. Please contact system administrator.');
                }  
                else
                {
                    $documents = PackageDocument::getDocuments($recordid);
                    /* 
                     * If documents are already attached to that package,
                     * they must be deleted
                     */
                    if ($documents == true)
                    {
                        foreach($documents as $document)
                        {
                            $document_save_flag = false;
                            $document->isactive = 0;
                            $document->isdeleted = 1;
                            $document_save_flag = $document->save();
                            if ($document_save_flag == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('error', 'Error occurred when deleting documents. Please contact system administrator.');
                            }
                        }
                        if ($document_save_flag == true)
                        {
                            $transaction->commit();
                            return self::actionIndex();
                        }
                    }
                    $transaction->commit();
                    return self::actionIndex();
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
        
        
        /**
         * Edits package configuration record
         * 
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 11/04/2016
         * Date Last Modified: 11/04/2016
         */
        public function actionEditPackage($recordid)
        {
            $package_save_flag = false;
            $document_save_flag = false;
            
            $package = Package::find()
                        ->where(['packageid' => $recordid, 'isactive' => 1])
                        ->one();

            $documents = PackageDocument::getDocuments($recordid);
            $document_count = 0;
            if ($documents)
                return $document_count = count($documents);

            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
            
                
                
                
            }
            
            
            return $this->render('edit_package', 
                            [
                                'package' => $package,
                                'count' => $document_count,
                                'documents' => $documents,
                            ]);
            }
            
            
        
        
        
        /**
         * Confirms package configurations
         * 
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 11/04/2016
         * Date Last Modified: 11/04/2016
         */
        public function actionConfirmPackage($recordid)
        {
            $package = Package::find()
                        ->where(['packageid' => $recordid, 'isactive' => 1])
                        ->one();
            if ($package)
            {
                $package->packageprogressid = 4;
                $package->lastmodifiedby = Yii::$app->user->getID();
                $package->datecompleted = date('Y-m-d');
                $package->isdeleted = 0;
                if (!$package->save())
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying confirm package confgurations. Please contact system administrator.');              
                }
                else
                    return self::actionIndex();
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Package was not found. Please contact system administrator.');              
        }
        
    }

