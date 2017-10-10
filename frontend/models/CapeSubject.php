<?php
    namespace frontend\models;

    use Yii;
    
    use frontend\models\CapeSubjectGroup;

    /**
     * This is the model class for table "cape_subject".
     *
     * @property string $capesubjectid
     * @property string $cordinatorid
     * @property string $academicofferingid
     * @property string $subjectname
     * @property integer $unitcount
     * @property integer $capacity
     * @property boolean $isactive
     * @property boolean $isdeleted
     *
     * @property ApplicationCapesubject[] $applicationCapesubjects
     * @property Person $cordinator
     * @property AcademicOffering $academicoffering
     * @property CapeSubjectGroup[] $capeSubjectGroups
     * @property CapeGroup[] $capegroups
     * @property CapeUnit[] $capeUnits
     */
    class CapeSubject extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'cape_subject';
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['academicofferingid', 'subjectname'], 'required'],
                [['cordinatorid', 'academicofferingid', 'unitcount', 'capacity'], 'integer'],
                [['isactive', 'isdeleted'], 'boolean'],
                [['subjectname'], 'string', 'max' => 100]
            ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'capesubjectid' => 'Capesubjectid',
                'cordinatorid' => 'Cordinatorid',
                'academicofferingid' => 'Academicofferingid',
                'subjectname' => 'Subjectname',
                'unitcount' => 'Unitcount',
                'capacity' => 'Capacity',
                'isactive' => 'Isactive',
                'isdeleted' => 'Isdeleted',
            ];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getApplicationCapesubjects()
        {
            return $this->hasMany(ApplicationCapesubject::className(), ['capesubjectid' => 'capesubjectid']);
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getCordinator()
        {
            return $this->hasOne(Person::className(), ['personid' => 'cordinatorid']);
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getAcademicoffering()
        {
            return $this->hasOne(AcademicOffering::className(), ['academicofferingid' => 'academicofferingid']);
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getCapeSubjectGroups()
        {
            return $this->hasMany(CapeSubjectGroup::className(), ['capesubjectid' => 'capesubjectid']);
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getCapegroups()
        {
            return $this->hasMany(CapeGroup::className(), ['capegroupid' => 'capegroupid'])->viaTable('cape_subject_group', ['capesubjectid' => 'capesubjectid']);
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getCapeUnits()
        {
            return $this->hasMany(CapeUnit::className(), ['capesubjectid' => 'capesubjectid']);
        }


        /**
         *Returns key=>value array of capesubjectid=>subjectname
         *  
         * @param type $subjects
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 09/01/2016
         * Date Last Modified: 09/01/2016
         */
        public static function processGroup($subjects)
        {
            $combined = array();
            $keys = array();
            $values = array();
            array_push($keys, '0');
            array_push($values, 'None');
            foreach($subjects as $subject)
            {
                $target = CapeSubject::find()
                        ->where(['capesubjectid' => $subject->capesubjectid])
                        ->one();
                $k = strval($target->capesubjectid);
                $v = strval($target->subjectname);
                array_push($keys, $k);
                array_push($values, $v);
            }
            $combined = array_combine($keys, $values);
            return $combined;
        }


        /**
        * Retrives all cape_subject records related to the given CAPE academic offering
        * 
        * @param type $academicofferingid
        * @return boolean
        * 
        * Author: Laurence Charles
        * Date Created: 15/02/2016
        * Date Last Modified: 15/02/2016
        */
       public static function getCapeSubjects($academicofferingid)
       {
           $records = CapeSubject::find()
                   ->innerJoin('academic_offering', '`cape_subject`.`academicofferingid`=`academic_offering`.`academicofferingid`')
                   ->where(['cape_subject.academicofferingid' => $academicofferingid, 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0])
                   ->all();
            if (count($records) > 0)
                return $records;
            return false;
        }


        /**
         * Creates backup of a collection of CapeSubjects records
         * 
         * @param type $subjects
         * @return array
         * 
         * Author: Laurence Charles
         * Date Created: 15/02/2016
         * Date Last Modified: 15/02/2016
         */
        public static function backUp($subjects)
        {
            $saved = array();

            foreach ($subjects as $subject)
            {
                $temp = NULL;
                $temp = new CapeSubject();
                $temp->cordinatorid = $subject->cordinatorid;
                $temp->academicofferingid = $subject->academicofferingid;
                $temp->subjectname = $subject->subjectname;
                $temp->unitcount = $subject->unitcount;
                $temp->capacity = $subject->capacity;
                $temp->isactive = $subject->isactive;
                $temp->isdeleted = $subject->isdeleted;
                array_push($saved, $temp);      
            }
            return $saved;
        }


        /**
         * Restores the backed up CapeSubjects to the database
         * 
         * @param type $subjects
         * 
         * Author: Laurence Charles
         * Date Created: 15/02/2016
         * Date Last Modified: 15/02/2016
         */
        public static function restore($subjects)
        {
            foreach ($subjects as $subject)
            {
                $subject->save();     
            }
        }
        

        /**
         * Returns an associative array of ['capesubjectid' => 'subject_name']
         * 
         * @param type $academicyearid
         * @return array
         * 
         * Author: Laurence Charles
         * Date Created: 24/06/2016
         * Date Last Modified: 24/06/2016
         */
        public static function prepareCapeSubjectListing($academicyearid)
        {
             $records = CapeSubject::find()
                      ->innerJoin('academic_offering', '`cape_subject`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                     ->where([ 'cape_subject.isactive' => 1, 'cape_subject.isdeleted' => 0,
                                    'academic_offering.academicyearid' => $academicyearid, 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0
                                    ])
                     ->all();

            $listing = array();

            foreach ($records as $record) 
            {
                $combined = array();
                $keys = array();
                $values = array();
                array_push($keys, "id");
                array_push($keys, "name");
                $k1 = strval($record->capesubjectid);
                $name = $record->subjectname;
                $k2 = strval($name);
                array_push($values, $k1);
                array_push($values, $k2);
                $combined = array_combine($keys, $values);
                array_push($listing, $combined);
                $combined = NULL;
                $keys = NULL;
                $values = NULL;
            }
            return $listing;
        }

        
        /**
         * Returns collection of Applicants that have been accepted to pursue this CapeSubject
         * 
         * @return [Applicant] | []
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2017_09_08
         * Modified: 2017_09_08
         */
        public function getApplicantIntake()
        {
            return  Applicant::find()
                    ->innerJoin('application' , '`applicant`.`personid` = `application`.`personid`')
                    ->innerJoin('offer' , '`application`.`applicationid` = `offer`.`applicationid`')
                    ->innerJoin('application_capesubject' , '`application`.`applicationid` = `application_capesubject`.`applicationid`')
                    ->where(['applicant.isactive' => 1, 'applicant.isdeleted' => 0,
                        'application.isactive' => 1, 'application.isdeleted' => 0,
                        'offer.ispublished' => 1, 'offer.isactive' => 1, 'offer.isdeleted' => 0,
                        'application_capesubject.capesubjectid' => $this->capesubjectid, 'application_capesubject.isactive' => 1, 
                        'application_capesubject.isdeleted' => 0,])
                    ->all();
        }
        
        
        /**
         * Returns the group CapeSubject belongs to
         * 
         * @return string
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2017_09_08
         * Modified: 2017_09_08
         */
        public function getGroup()
        {
            $group = CapeGroup::find()
                    ->innerJoin('cape_subject_group' , '`cape_group`.`capegroupid` = `cape_subject_group`.`capegroupid`')
                    ->where([ 'cape_subject_group.isactive' => 1, 'cape_subject_group.isdeleted' => 0,
                        'cape_subject_group.capesubjectid' => $this->capesubjectid, 'cape_subject_group.isactive' => 1, 
                        'cape_subject_group.isdeleted' => 0])
                    ->one();
            return $group == true ? $group->name : "??";
        }

        
        /**
         * Soft deletes the 'cape_subject' record
         * 
         * @return boolean
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2017_10_02
         * Modified: 2017_10_02
         */
        public function softDelete()
        {
            $this->isactive = 0;
            $this->isdeleted = 1;
            return $this->save();
        }


    }
