<?php

    namespace frontend\models;

    use Yii;
    use yii\base\Model;
    use yii\web\UploadedFile;

    class EventAttachment extends Model
    {
        /**
         * @var UploadedFile[]
         */
        public $files;
        public $username;
        public $studentregistrationid;
        public $eventtypeid;
        public $record_id;
        public $limit;

        public function rules()
        {
            return [
                [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, pdf, doc', 'maxFiles' => 5],
            ];
        }

        public function upload()
        {
            if ($this->validate()) { 
                if ($this->eventtypeid == 1)
                    $event_type = "sick_leave";
                elseif ($this->eventtypeid == 2)
                    $event_type = "maternity_leave";
                elseif ($this->eventtypeid == 3)
                    $event_type = "miscellaneous";
                elseif ($this->eventtypeid == 4)
                    $event_type = "disciplinary_action";
                
                foreach ($this->files as $file) {
                    $dir =  Yii::getAlias('@frontend') . "/files/student_records/" . $this->username . "/" . $this->studentregistrationid . "/events/" . $event_type . "/" . $this->record_id . "/";
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

