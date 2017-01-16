<?php
    namespace app\subcomponents\students\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\base\Model;
    use yii\helpers\FileHelper;
    use yii\web\UploadedFile;
    use yii\data\ArrayDataProvider;
    
    use common\models\User;
    use frontend\models\EmailUploadAttachment;
    use frontend\models\Student;
    use frontend\models\Employee;
    
    class EmailUploadController extends Controller
    {
        
        /**
         * Renders index page
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 25/09/2016
         * Date Last Modified:25/09/2016
         */
        public function actionIndex()
        {
            return $this->render('index');
        }
        
        
        /**
         * Upload email file
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 26/06/2016
         * Date Last Modified: 26/09/2016
         */
        public function actionUploadEmailFile()
        {
            $model = new EmailUploadAttachment();
            
            if (Yii::$app->request->isPost) 
            {
                $model->files = UploadedFile::getInstances($model, 'files');
                
                if ($model->upload())   // file is uploaded successfully
                {
                    Yii::$app->getSession()->setFlash('success', 'File uploaded successfully.');    
                    return self::actionIndex();
                }
                else
                {
                   Yii::$app->getSession()->setFlash('error', 'Error occured uploading file; ' . $count . ' files uploaded');   
                }
            }
            return $this->render('upload_email_file', ['model' => $model]);
        }
        
        
        /**
         * Renders email file listing
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 26/06/2016
         * Date Last Modified: 26/09/2016
         */
        public function actionViewEmailFiles()
        {
             $dir = Yii::getAlias('@frontend') . "/files/student_emails";
             $files = FileHelper::findFiles($dir);
            
             return $this->render('email_file_listing', [
                 'files' => $files,
                 'count' => count($files)
                 ]
            );
        }
        
        
        /**
         * Deletes and email from lisiting
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 27/06/2016
         * Date Last Modified: 27/09/2016
         */
        public function actionDownloadFile($index)
        {
            $dir = Yii::getAlias('@frontend') . "/files/student_emails";
            $files = FileHelper::findFiles($dir);
            
           $target_file = $files[$index];
           $target_file_name = substr($target_file,52);
           
           Yii::$app->response->sendFile($target_file, $target_file_name);
           Yii::$app->response->send();
           
            return self::actionViewEmailFiles();
        }
        
        
        /**
         * Deletes and email from lisiting
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 27/06/2016
         * Date Last Modified: 27/09/2016 | 28/09/2016  | 16/01/2017   
         */
        public function actionProcessFile($index)
        {
            $total = 0;
            $successful = 0;
            $target_file = NULL;
            $new_filename = NULL;
            $dataProvider = NULL;
            $data = array();
            
            $dir = Yii::getAlias('@frontend') . '/files/student_emails';
            $files = FileHelper::findFiles($dir);
            
            $target_file = $files[$index];
            $new_filename = str_replace("/", "\\", $target_file );
            
            $file_validation = $this->validateFile($new_filename);
            if ($file_validation == -1)
            {
                Yii::$app->getSession()->setFlash('error', 'Error.  File not found.');  
                return self::actionViewEmailFiles();
            }
            elseif ($file_validation == -2)
            {
                Yii::$app->getSession()->setFlash('error', 'Error.  The column count for the title row is invalid.  Column Count= ' . count(fgetcsv(fopen($new_filename,"r"), 1000, ",")));  
                return self::actionViewEmailFiles();
            }
            elseif ($file_validation == -3)
            {
                Yii::$app->getSession()->setFlash('error', 'Error.  The column arrangement for title row of file is invalid.');  
                return self::actionViewEmailFiles();
            }
            elseif ($file_validation == -4)
            {
                Yii::$app->getSession()->setFlash('error', 'Error.  All rows dont have required number of columns.');  
                return self::actionViewEmailFiles();
            }
            elseif ($file_validation == -5)
            {
                Yii::$app->getSession()->setFlash('error', 'Error.  File exceeds maximum record limit.');  
                return self::actionViewEmailFiles();
            }
            
            /***************  if validation is successful  *******************/
            $file_handler = fopen($new_filename,"r");
            
            if ($file_handler == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Error opening file');  
                return self::actionViewEmailFiles();
            }
            
            //reads row with column titles
            $title_row = fgetcsv($file_handler, 1000, ",");
            
            while (($row = fgetcsv($file_handler, 1000, ",")) !== false) 
            {
                $total++;
                $info = array();
                
                $username = $row[0];
                $email = $row[1];
                
                $user = User::find()
                        ->where(['username' => $username, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                if ($user == false)
                {
                    $info['username'] = $username;
                    $info['error'] = "User record not found";
                    $data[] = $info;
                    continue;
                }
                
                $user->email = $email;
                
                $student = Student::find()
                        ->where(['personid' => $user->personid, 'isdeleted' => 0])
                        ->one();
                 if ($student == false)
                {
                    $info['username'] = $username;
                    $info['error'] = "Student record not found";
                    $data[] = $info;
                     continue;
                }
                
                $student->email = $email;
                
                $student_save_flag = false;
                $user_save_flag = false;
                
                $student_save_flag = $student->save();
                if ($student_save_flag == false)
                {
                    $info['username'] = $username;
                    $info['error'] = "Error saving student record";
                    $data[] = $info;
                    continue;
                }

                $user_save_flag = $user->save();
                if ($user_save_flag == false)
                {
                    $info['username'] = $username;
                    $info['error'] = "Error saving user record";
                    $data[] = $info;
                    continue;
                }
                
                $successful++;
            }
            
            $dataProvider = new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => [
                    'pageSize' => 25,
                ],
                 'sort' => [
                        'defaultOrder' => ['username' => SORT_ASC],
                        'attributes' => ['username', 'error'],
                    ],
            ]);
            
            fclose($file_handler);
            
            $title = " Title: File Upload Results";
            $date = " Date: " . date('Y-m-d') . "   ";
            $employeeid = Yii::$app->user->identity->personid;
            $generating_officer = " Generated By: " . Employee::getEmployeeName($employeeid);
            $filename = $title . $date . $generating_officer;
            
            $percentage = round(($successful/$total) * 100);
            
            return $this->render('upload_results', [
                    'dataProvider' => $dataProvider,
                    'filename' => $filename,
                    'total' => $total,
                    'successful' => $successful,
                    'percentage' => $percentage,
                    'filename' => $new_filename
                ]);
        }
        
        
        /**
         * Deletes and email from lisiting
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 26/06/2016
         * Date Last Modified: 26/09/2016
         */
        public function actionDeleteFile($index)
        {
            $dir = Yii::getAlias('@frontend') . "/files/student_emails";
            $files = FileHelper::findFiles($dir);
            
            foreach ($files as $key => $file)
            {
                if ($key == $index)
                {
                    unlink($file);
                }
            }
            return self::actionViewEmailFiles();
        }
        
        
         /**
         * Return true if email file meets validation criteria
         * 
         * @param type $row
         * @return boolean
         * 
         * Author: Laurence Charles
         * Date Created: 28/09/2016
         * Date Last Modified: 28/09/2016 | 16/01/2017     
         */
        private function validateFile($filename)
        {
            $count = 0;
            $handler = fopen($filename, "r");
            
            if ($handler == false)
                return -1;
            
            $title_row = fgetcsv($handler, 1000, ",");
            $validate_title_count = $this->validateTitleRowCount($title_row);
            if ($validate_title_count == false)
            {
                fclose($handler);
                return -2;
            }
            
            $validate_title_arrangement = $this->validateTitleRowArrangement($title_row);
            if ($validate_title_arrangement == false)
            {
                fclose($handler);
                return -3;
            }
            
            while (($row = fgetcsv($handler, 1000, ",")) !== false) 
            {
                $count++;
                if ( $this->validateDataRow($row) == false)
                {
                    fclose($handler);
                    return -4;
                }
            }
            
            if ($count > 100)
            {
                fclose($handler);
                return -5;
            }
            
            fclose($handler);
            return 1;
        }
//        private function validateFile($filename)
//        {
//            $count = 0;
//            $handler = fopen($filename, "r");
//            
//            if ($handler == false)
//                return -1;
//            
//            $title_row = fgetcsv($handler, 1000, ",");
//            $validate_title_count = $this->validateTitleRowCount($title_row);
//            if ($validate_title_count == false)
//            {
//                fclose($handler);
//                return -2;
//            }
//            
//            $validate_title_arrangement = $this->validateTitleRowArrangement($title_row);
//            if ($validate_title_arrangement == false)
//            {
//                fclose($handler);
//                return -3;
//            }
//            
//            while (($row = fgetcsv($handler, 1000, ",")) !== false) 
//            {
//                $count++;
//                if ( $this->validateDataRow($row) == false)
//                {
//                    fclose($handler);
//                    return -4;
//                }
//            }
//            
//            if ($count > 100)
//            {
//                fclose($handler);
//                return -5;
//            }
//            
//            fclose($handler);
//            return 1;
//        }
        
        
        /**
         * Validate title row has correct numbers of columns
         * 
         * @param type $row
         * @return boolean
         * 
         * Author: Laurence Charles
         * Date Created: 29/09/2016
         * Date Last Modified: 29/09/2016  | 16/01/2017   
         */
        private function validateTitleRowCount($row)
        {
            if (count($row) != 2)
            {
                return false;
            }
            return true;
        }
        
        
        /**
         * Validate title row are in the correct order.
         * 
         * @param type $row
         * @return boolean
         * 
         * Author: Laurence Charles
         * Date Created: 29/09/2016
         * Date Last Modified: 29/09/2016  | 16/01/2017
         */
        private function validateTitleRowArrangement($row)
        {
            $username = $row[0];
            $email = $row[1];

            if ($username == "username" && $email == "email")
            {
                return true;
            }
            return false;
        }
        
        
        /**
         * Validate general row has correct numbers of columns and all columns 
         * have data.
         * 
         * @param type $row
         * @return boolean
         * 
         * Author: Laurence Charles
         * Date Created: 29/09/2016
         * Date Last Modified: 29/09/2016    
         */
         private function validateDataRow($row)
        {
            if (count($row) == 2)
            {
                $username = $row[0];
                $email = $row[1];

                if ($username == true && $email == true)
                {
                    return true;
                }
            }
            return false;
        }
        
        
    }

