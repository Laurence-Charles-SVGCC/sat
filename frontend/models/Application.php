<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "application".
 *
 * @property string $applicationid
 * @property string $personid
 * @property string $divisionid
 * @property string $academicofferingid
 * @property string $applicationstatusid
 * @property string $applicationtimestamp
 * @property string $submissiontimestamp
 * @property integer $ordering
 * @property string $ipaddress
 * @property string $browseragent
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property ApplicationStatus $applicationstatus
 * @property Person $person
 * @property AcademicOffering $academicoffering
 * @property Division $division
 * @property ApplicationCapesubject[] $applicationCapesubjects
 * @property ApplicationHistory[] $applicationHistories
 * @property Offer[] $offers
 */
class Application extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'divisionid', 'academicofferingid', 'applicationstatusid', 'applicationtimestamp', 'ordering'], 'required'],
            [['personid', 'divisionid', 'academicofferingid', 'applicationstatusid', 'ordering', 'isactive', 'isdeleted'], 'integer'],
            [['applicationtimestamp', 'submissiontimestamp'], 'safe'],
            [['ipaddress', 'browseragent'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applicationid' => 'Applicationid',
            'personid' => 'Personid',
            'divisionid' => 'Divisionid',
            'academicofferingid' => 'Academicofferingid',
            'applicationstatusid' => 'Applicationstatusid',
            'applicationtimestamp' => 'Applicationtimestamp',
            'submissiontimestamp' => 'Submissiontimestamp',
            'ordering' => 'Ordering',
            'ipaddress' => 'Ipaddress',
            'browseragent' => 'Browseragent',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationstatus()
    {
        return $this->hasOne(ApplicationStatus::className(), ['applicationstatusid' => 'applicationstatusid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['personid' => 'personid']);
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
    public function getDivision()
    {
        return $this->hasOne(Division::className(), ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationCapesubjects()
    {
        return $this->hasMany(ApplicationCapesubject::className(), ['applicationid' => 'applicationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationHistories()
    {
        return $this->hasMany(ApplicationHistory::className(), ['applicationid' => 'applicationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offer::className(), ['applicationid' => 'applicationid']);
    }
    
    public static function isCapeApplication($academicofferingid)
    {
        $ao = AcademicOffering::findOne(['academicofferingid' => $academicofferingid]);
        $cape_prog = ProgrammeCatalog::findOne(['name' => 'cape']);
        return $cape_prog ? $ao->programmecatalogid == $cape_prog->programmecatalogid : False;
    }
    
    
    /**
     * Returns all applicant applications
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 23/12/2015
     * Date LAt Modified: 23/12/2015
     */
    public static function getApplications($id)
    {
        $applications = Application::find()
//                ->where(['personid' => $id, 'isactive' => 1, 'isdeleted'=> 0])
                ->where(['personid' => $id, 'isdeleted'=> 0])
                ->all();
        if (count($applications) > 0)
        {
            return $applications;
        }
        return false;
    }
    
    
    /**
     * Determines if application relates to a CAPE programme
     * 
     * @param type $academicofferingid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 23/12/2015
     * Date Last Modified: 23/12/2015
     */
    public static function isCape($academicofferingid)
    {
        $db = Yii::$app->db;
        $records = $db->createCommand(
                "SELECT academic_offering.academicofferingid AS 'academicofferingid',"
                . " programme_catalog.name AS 'name'"
                . " FROM academic_offering"
                . " JOIN programme_catalog"
                . " ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
                . " WHERE academic_offering.academicofferingid = ". $academicofferingid
                . ";"
                )
                ->queryAll();
        
        $name = $records[0]["name"];
        if (strcmp($name, "CAPE") == 0)     //if application is for CAPE programme     
            return true;
        return false;
    }
    
    
    /**
     * Returns all applications that were suggested by Deans/Deputy Deans
     * 
     * @param type $id
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 23/12/2015
     * Date Last Modified: 23/12/2015
     */
    public static function getSpecialApplication($id)
    {
        $applications = Applincation::find()
                    ->where(['personid' => $id, 'isactive' =>1, 'isdeleted'=> 0])
                    ->andWhere(['>', 'ordering', 3])
                    ->all();
        if (count($applications)>0)
            return $applications;
        return false;
    }
    
    
    /**
     * Return the fully qualified name of an application
     * 
     * @param type $academicofferingid
     * @return boolean|string
     * 
     * Author: Laurence Charles
     * Date Created: 23/12/2015
     * Date Last Modified: 23/12/2015
     */
    public static function getApplicationDetails($academicofferingid)
    {
        $db = Yii::$app->db;
        $p = $db->createCommand(
            "SELECT academic_offering.academicofferingid AS 'academicofferingid',"
            . " programme_catalog.name AS 'name',"
            . " programme_catalog.specialisation AS 'specialisation',"
            . " qualification_type.abbreviation AS 'qualificationtype'"
            . " FROM  academic_offering "
            . " JOIN programme_catalog"
            . " ON programme_catalog.programmecatalogid = academic_offering.programmecatalogid"
            . " JOIN qualification_type"
            . " ON programme_catalog.qualificationtypeid = qualification_type.qualificationtypeid"
            . " WHERE academic_offering.academicofferingid = " . $academicofferingid . " ;"
            )
            ->queryAll();

        if (count($p)>0)
        {
            $specialization = $p[0]["specialisation"];
            $qualification = $p[0]["qualificationtype"];
            $programme = $p[0]["name"];
            $fullname = $qualification . " " . $programme . " " . $specialization;
            return $fullname;
        }
        else 
            return false;
    }
    
    
    /**
     * Returns the appropriate ordering for a new institution created application
     * 
     * @param type $personid
     * @return int
     * 
     * Author: Laurence Charles
     * Date Created: 09/01/2016
     * Date Last Modified: 11/01/2016
     */
    public static function getNextApplicationID($personid)
    {
        $custom_applications = Application::find()
                    ->where(['personid' => $personid])
                    ->andWhere(['>', 'ordering', 3])
                    ->all();
        $count = count($custom_applications);
        if($count > 0)
        {
            $last_id = $applications[($count-1)];
            $new_id = $last_id + 1;
        }
        else
        {
            $student_applications = Application::find()
                    ->where(['personid' => $personid])
                    ->andWhere(['<', 'ordering', 4])
                    ->all();
            $count2 = count($student_applications);
            if ($count2 == 1)
                $new_id = 2;
            elseif ($count2 == 2)
                $new_id = 3;
            elseif ($count2 == 3)
                $new_id = 4;
        }
        return $new_id;
    }
    
}
