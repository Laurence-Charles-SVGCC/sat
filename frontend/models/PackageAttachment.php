<?php

    namespace frontend\models;

    use Yii;
    use yii\base\Model;
    use yii\web\UploadedFile;

    class PackageAttachment extends Model
    {
        /**
         * @var UploadedFile[]
         */
        public $files;
        public $package_id;
        public $package_name;
        public $limit;

        public function rules()
        {
            return [
                [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, pdf, doc, docx', 'maxFiles' => 10],
            ];
        }

        public function upload()
        {
            if ($this->validate()) 
            { 
                foreach ($this->files as $file) 
                {
                    $dir =  Yii::getAlias('@frontend') . "/files/packages/" . $this->package_id . "_" . $this->package_name . "/";
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

