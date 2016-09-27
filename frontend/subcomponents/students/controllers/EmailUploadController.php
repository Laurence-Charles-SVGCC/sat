<?php
    namespace app\subcomponents\students\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\base\Model;
    use yii\helpers\FileHelper;
    use yii\web\UploadedFile;
    
    use frontend\models\EmailUploadAttachment;
    
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
                   Yii::$app->getSession()->setFlash('error', 'Error occured uploading file.');   
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
        public function actionProcessEmailFile()
        {
             $dir = Yii::getAlias('@frontend') . "/files/student_emails";
             $files = FileHelper::findFiles($dir);
            
             return $this->render('email_file_listing', ['files' => $files, 'count' => count($files)]);
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
            return self::actionProcessEmailFile();
        }
        
        
        
        
        
    }

