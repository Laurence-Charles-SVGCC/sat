<?php

    namespace frontend\models;

    use Yii;
    use yii\base\Model;
    use yii\web\UploadedFile;

    class EmailUploadAttachment extends Model
    {
        /**
         * @var UploadedFile[]
         */
        public $files;

        public function rules()
        {
            return [
                [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'csv, xlsx', 'maxFiles' => 5],
            ];
        }

        public function upload()
        {
            $dir =  Yii::getAlias('@frontend') . "/files/student_emails/";
            foreach ($this->files as $file) 
            {
                $file->saveAs($dir . $file->baseName . '.' . $file->extension);
            }
            return true;
        }
        
        
        
    }

