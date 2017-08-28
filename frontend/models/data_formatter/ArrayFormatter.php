<?php
    namespace frontend\models\data_formatter;

    use Yii;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\ApplicationCapeSubject;
    use frontend\models\Institution;
    use yii\custom\ModelNotFoundException;

    class ArrayFormatter extends \yii\base\Model
    { 
        
        /**
        * Formats array into ordanized string
        * 
        * @return [String] | []
        * 
        * Author: Laurence Charles
        * Date Created: 2017_12_27
        * Date Last Modified: 2017_08_27
        */
        public static function OrdanizeArray($input)
        {
            $ordanized_listing = " ";

            foreach ($input as $key=>$entry)
            {
                if((count($input)-1) == $key)
                {
                    $ordanized_listing.= " " . "(" . ($key+1) . ") " . $entry;
                }
                else
                {
                    $ordanized_listing.= " " . "(" . ($key+1) . ") " . $entry . ",";
                }
            }
            return $ordanized_listing;
        }
        
        
        /**
         * Returns programme choices in the format [key][full_programme_name]
         * 
         * @param type $applications
         * @return string
         * @throws ModelNotFoundException
         * 
         * Author: Laurence Charles
         * Date Created: 2017_12_27
         * Date Last Modified: 2017_08_27
         */
        public static function FormatProgrammesChoices($applications)
        {
            $programme_listing = "";
            
            if (empty($applications) == true)
            {
               return $programme_listing;
            }

            foreach ( $applications as $key=>$application )
            {
                
                $programme_record = ProgrammeCatalog::findOne(['programmecatalogid' => $application->getAcademicoffering()->one()->programmecatalogid]);
                if (empty($programme_record) == true)
                {
                    $error_message = "ProgrammeCatalog record for  Application->ApplicationID= " . $application->applicationid . "not found.";
                    throw new ModelNotFoundException($error_message);
                }

                $cape_subjects = array();
                $cape_subjects_names = array();
                $cape_subjects = ApplicationCapesubject::find()
                            ->innerJoin('application', '`application_capesubject`.`applicationid` = `application`.`applicationid`')
                            ->where(['application.applicationid' => $application->applicationid, 'application.isactive' => 1, 'application.isdeleted' => 0])
                            ->all();
                foreach ($cape_subjects as $cs) 
                { 
                    $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname; 
                }
                $programme_name = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
                
                if((count($applications)-1) == $key)
                {
                    $programme_listing .= " " . "(" . ($key+1) . ") " . $programme_name;
                }
                else
                {
                    $programme_listing .= " " . "(" . ($key+1) . ") " . $programme_name . ",";
                }
            }
            return $programme_listing;
        }
        
        
    }


