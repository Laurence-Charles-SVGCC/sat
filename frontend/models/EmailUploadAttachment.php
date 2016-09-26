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
                [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'csv', 'maxFiles' => 1],
            ];
        }

        public function upload()
        {
            if ($this->validate()) { 
                foreach ($this->files as $file) {
                    $dir =  Yii::getAlias('@frontend') . "/files/student_emails/";
                    $file->saveAs($dir . $file->baseName . '.' . $file->extension);
                }
                return true;
            } 
            else 
            {
                return false;
            }
        }
    }

