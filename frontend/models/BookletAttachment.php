<?php

    namespace frontend\models;

    use Yii;
    use yii\base\Model;
    use yii\web\UploadedFile;
    use yii\helpers\FileHelper;

    class BookletAttachment extends Model
    {
        /**
         * @var UploadedFile[]
         */
        public $files;
        public $divisionid;
        public $programmecatalogid;
        public $academicofferingid;

        public function rules()
        {
            return [
                [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, pdf, doc, txt', 'maxFiles' => 2],
            ];
        }

        public function upload()
        {
            if ($this->validate()) 
            {
                if($this->divisionid == 4)
                    $division = "dasgs";
                elseif($this->divisionid == 5)
                    $division = "dtve";
                elseif($this->divisionid == 6)
                    $division = "dte";
                elseif($this->divisionid == 7)
                    $division = "dne";
                
                foreach ($this->files as $file) 
                {
                    $dir =  Yii::getAlias('@frontend') . "/files/programme_booklets/" . $division . "/" . $this->programmecatalogid . "_" . $this->academicofferingid . "/";
                    
                    $folder  = new FileHelper();
                    $folder_status = $folder->createDirectory($dir, 509, true);
                    if($folder_status)
                        $file->saveAs($dir . $file->baseName . '.' . $file->extension);
                    else
                        return false;
                }
                return true;
            } 
            return false;
        }
    }

