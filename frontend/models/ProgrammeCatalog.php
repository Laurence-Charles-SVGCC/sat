<?php

namespace frontend\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\web\Response;
use yii\base\ErrorException;
use yii\base\InvalidParamException;


/**
 * This is the model class for table "programme_catalog".
 *
 * @property string $programmecatalogid
 * @property string $programmetypeid
 * @property string $examinationbodyid
 * @property string $qualificationtypeid
 * @property string $departmentid
 * @property string $creationdate
 * @property string $specialisation
 * @property integer $duration
 * @property string $name
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property AcademicOffering[] $academicOfferings
 * @property ExaminationBody $examinationbody
 * @property QualificationType $qualificationtype
 * @property Department $department
 */
class ProgrammeCatalog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'programme_catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['programmetypeid', 'examinationbodyid', 'qualificationtypeid', 'departmentid', 'creationdate', 'duration', 'name'], 'required'],
            [['programmetypeid', 'examinationbodyid', 'qualificationtypeid', 'departmentid', 'duration', 'isactive', 'isdeleted'], 'integer'],
            [['creationdate'], 'safe'],
            [['specialisation', 'name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'programmecatalogid' => 'Programmecatalogid',
            'programmetypeid' => 'Programmetypeid',
            'examinationbodyid' => 'Examination Body',
            'qualificationtypeid' => 'Qualification Type',
            'departmentid' => 'Department',
            'creationdate' => 'Date Created',
            'specialisation' => 'Specialisation',
            'duration' => 'Duration (Years)',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicOfferings()
    {
        return $this->hasMany(AcademicOffering::className(), ['programmecatalogid' => 'programmecatalogid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExaminationbody()
    {
        return $this->hasOne(ExaminationBody::className(), ['examinationbodyid' => 'examinationbodyid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQualificationtype()
    {
        return $this->hasOne(QualificationType::className(), ['qualificationtypeid' => 'qualificationtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::className(), ['departmentid' => 'departmentid']);
    }
    
    
    public function getFullName()
    {
        $qual = $this->getQualificationtype()->one();
        if ($qual)
        {
            return $qual->abbreviation . ' ' . $this->name . ' ' . $this->specialisation;
        }
        return $this->name . ' ' . $this->specialisation;
    }
    
    
    /**
     * Return a list of programmes by division
     * 
     * @param type $divisionid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 06/12/2015
     * Date Last Modified: 08/12/2015
     */
    public static function getProgrammes($divisionid)
    {
        $programmes = ProgrammeCatalog::find()
                ->joinWith('department')
                ->innerJoin('`division`', '`division`.`divisionid` = `department`.`divisionid`')
                ->where(['division.divisionid' => $divisionid, 'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0])
//                ->andWhere(['not', ['programme_catalog.name' => "Cape"]])
                ->all();
        
        if (count($programmes)>0)
            return $programmes;
        else
            return false;       
    }
    
    
    /**
     * Returns a list of programmes based on $departmentid
     * 
     * @param type $departmentid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 08/12/2015
     * Date Last Modified: 08/12/2015
     */
    public static function getProgrammesByDepartment($departmentid)
    {   
        $programmes = ProgrammeCatalog::find()
                ->where(['departmentid' => $departmentid, 'isactive' => 1, 'isdeleted' => 0])
                ->all();
        if (count($programmes) > 0)
            return $programmes;
        else
            return false;       
    }
    
    
    /**
     * Returns an associated array programes
     * 
     * @param type $divisionid
     * @param type $applicationperiodtypeid
     * 
     * Author: Laurence Charles
     * Date Created: 12/02/2016
     * Date Last Modified: 12/02/2016
     */
    public static function getProgrammeListing($divisionid, $applicationperiodtypeid)
    {
        $db = Yii::$app->db;
        $records = $db->createCommand(
                "SELECT programme_catalog.programmecatalogid AS 'id',"
                . " intent_type.name AS 'programme_type',"
                . " qualification_type.abbreviation AS 'qualification',"
                . " programme_catalog.name AS 'name',"
                . " programme_catalog.specialisation AS 'specialisation'"
                . " FROM programme_catalog" 
                . " JOIN intent_type"
                . " ON programme_catalog.programmetypeid = intent_type.intenttypeid"
                . " JOIN qualification_type"
                . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                . " JOIN department"
                . " ON programme_catalog.departmentid= department.departmentid"
                . " WHERE programme_catalog.isactive = 1"
                . " AND programme_catalog.isdeleted = 0"
                . " AND department.divisionid = " . $divisionid
                . " AND programme_catalog.programmetypeid = " . $applicationperiodtypeid
                . " AND programme_catalog.name <> 'CAPE'"
                . ";"
            )
            ->queryAll();
        if (count($records) > 0)
            return $records;
        return false;
    }
    
    
    /**
     * Returns an associated array all programes
     * 
     * @param type $divisionid
     * @param type $applicationperiodtypeid
     * 
     * Author: Laurence Charles
     * Date Created: 14/02/2016
     * Date Last Modified: 14/02/2016
     */
    public static function getFullProgrammeListing($divisionid, $applicationperiodtypeid)
    {
        $db = Yii::$app->db;
        $records = $db->createCommand(
                "SELECT programme_catalog.programmecatalogid AS 'id',"
                . " intent_type.name AS 'programme_type',"
                . " qualification_type.abbreviation AS 'qualification',"
                . " programme_catalog.name AS 'name',"
                . " programme_catalog.specialisation AS 'specialisation'"
                . " FROM programme_catalog" 
                . " JOIN intent_type"
                . " ON programme_catalog.programmetypeid = intent_type.intenttypeid"
                . " JOIN qualification_type"
                . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
                . " JOIN department"
                . " ON programme_catalog.departmentid= department.departmentid"
                . " WHERE programme_catalog.isactive = 1"
                . " AND programme_catalog.isdeleted = 0"
                . " AND department.divisionid = " . $divisionid
                . " AND programme_catalog.programmetypeid = " . $applicationperiodtypeid
                . ";"
            )
            ->queryAll();
        if (count($records) > 0)
            return $records;
        return false;
    }
    
    
    
    /**
     * Returns an array of all programmes associated with open application periods
     * 
     * @param type $divisionid
     * 
     * Author: Laurence Charles
     * Date Created: 19/02/2016
     * Date Last Modified: 19/02/2016
     */
    public static function getCurrentProgrammes($division_id)
    {
        $prog_cond = null;
         if ($division_id == 1)
             $prog_cond = array('application_period.iscomplete' => 0, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0);
         else
             $prog_cond = array('application_period.iscomplete' => 0, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 'application_period.divisionid' => $division_id); 
        
        $records = ProgrammeCatalog::find()
                    ->innerJoin('academic_offering', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                    ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                    ->where($prog_cond)
                    ->all();
        
        return $records;
    }
    
    
    /**
     * Returns programme_catalog record based on applicationid
     * 
     * @param type $applicationid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 28/02/2016
     * Date Last Modified: 28/02/2016 | 27/08/2016
     */
    public static function getApplicantProgramme($applicationid)
    {
        $programme = ProgrammeCatalog::find()
                ->innerJoin('academic_offering', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                ->innerJoin('application', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->where(['programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0, 
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 
                                'application.isactive' => 1, 'application.isdeleted' => 1, 'application.applicationid' => $applicationid
                            ])
                ->one();
        if ($programme)
            return $programme;
        return false;
    }
    
    
    /**
     * Returns the full name of a programme
     * 
     * @param type $academicofferingid
     * @return string
     * 
     * Author: Laurence Charles
     * Date Created: 28/04/2016
     * Date Last Modified: 28/04/2016
     */
    public static function getProgrammeName($academicofferingid)
    {
        $programme = ProgrammeCatalog::find()
                ->innerJoin('academic_offering', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                ->where(['academic_offering.academicofferingid' => $academicofferingid, 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0])
                ->one();
        $fullname = "";
        
        $qualificationtype = QualificationType::find()
                                ->where(['qualificationtypeid' => $programme->qualificationtypeid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one()->abbreviation;
        $name = $programme->name;
        $specialisation = $programme->specialisation;
        
        if($specialisation != false && $specialisation != NULL  && $specialisation != ""  && $specialisation != " " )
            $fullname = $qualificationtype . ". " . $name . " (" . $specialisation . ")";
        else
            $fullname = $qualificationtype . ". " . $name;
        
        return $fullname;       
    }
    
    
    /**
     * Returns the full name of a programme given the programme cataalogid
     * 
     * @param type $academicofferingid
     * @return string
     * 
     * Author: Laurence Charles
     * Date Created: 28/04/2016
     * Date Last Modified: 28/04/2016
     */
    public static function getProgrammeFullName($programmecatalogid)
    {
        $programme = ProgrammeCatalog::find()
                ->where(['programmecatalogid' =>$programmecatalogid])
                ->one();
        
        $fullname = "";
        
        $qualificationtype = QualificationType::find()
                                ->where(['qualificationtypeid' => $programme->qualificationtypeid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one()->abbreviation;
        $name = $programme->name;
        $specialisation = $programme->specialisation;
        
        if($specialisation != false && $specialisation != NULL  && $specialisation != ""  && $specialisation != " " )
            $fullname = $qualificationtype . ". " . $name . " (" . $specialisation . ")";
        else
            $fullname = $qualificationtype . ". " . $name;
        
        return $fullname;       
    }
    
    
    /**
     * Returns instances of programme booklets
     * 
     * @param type $divisionid
     * @param type $programmecatalogid
     * @param type $academicofferingid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 10/06/2016
     * Date Last Modified: 10/06/2016
     */
    public static function getBooklets($divisionid, $programmecatalogid, $academicofferingid)
    {
        $files = false;
        
        if($divisionid == 4)
            $division = "dasgs";
        elseif($divisionid == 5)
            $division = "dtve";
        elseif($divisionid == 6)
            $division = "dte";
        elseif($divisionid == 7)
            $division = "dne";
        
        $dir =  Yii::getAlias('@frontend') . "/files/programme_booklets/" . $division . "/" . $programmecatalogid . "_" . $academicofferingid . "/";
        try
        {
             $files = FileHelper::findFiles($dir);
        } catch (InvalidParamException $e) {
            return false;
        }
        
        return $files;
    }
    
    
    
    
    
}
