<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "package".
 *
 * @property integer $packageid
 * @property integer $applicationperiodid
 * @property integer $packagetypeid
 * @property integer $packageprogressid
 * @property integer $createdby
 * @property integer $lastmodifiedby
 * @property string $name
 * @property string $emailtitle
 * @property string $emailcontent
 * @property string $datestarted
 * @property string $datecompleted
 * @property integer $documentcount
 * @property integer $waspublished
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $lastmodifiedby0
 * @property ApplicationPeriod $applicationperiod
 * @property PackageType $packagetype
 * @property PackageProgress $packageprogress
 * @property Person $createdby0
 * @property PackageDocument[] $packageDocuments
 */
class Package extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'package';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applicationperiodid', 'packagetypeid', 'packageprogressid', 'createdby', 'lastmodifiedby', 'name', 'datestarted', 'emailtitle', 'emailcontent', 'documentcount'], 'required'],
            [['applicationperiodid', 'packagetypeid', 'packageprogressid', 'createdby', 'lastmodifiedby', 'documentcount', 'isactive', 'isdeleted'], 'integer'],
            [['datestarted', 'datecompleted'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['emailtitle', 'emailcontent'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'packageid' => 'Packageid',
            'applicationperiodid' => 'Applicationperiodid',
            'packagetypeid' => 'Packagetypeid',
            'packageprogressid' => 'Packageprogressid',
            'createdby' => 'Createdby',
            'lastmodifiedby' => 'Lastmodifiedby',
            'name' => 'Name',
            'emailtitle' => 'Emailtitle',
            'emailcontent' => 'Emailcontent',
            'datestarted' => 'Datestarted',
            'datecompleted' => 'Datecompleted',
            'documentcount' => 'Documentcount',
            'waspublished' => 'Has Been Published',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastmodifiedby0()
    {
        return $this->hasOne(Person::className(), ['personid' => 'lastmodifiedby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationperiod()
    {
        return $this->hasOne(ApplicationPeriod::className(), ['applicationperiodid' => 'applicationperiodid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackagetype()
    {
        return $this->hasOne(PackageType::className(), ['packagetypeid' => 'packagetypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageprogress()
    {
        return $this->hasOne(PackageProgress::className(), ['packageprogressid' => 'packageprogressid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedby0()
    {
        return $this->hasOne(Person::className(), ['personid' => 'createdby']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageDocuments()
    {
        return $this->hasMany(PackageDocument::className(), ['packageid' => 'packageid']);
    }
    
    
    /**
     * Returns an array of active packages
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 09/04/2016
     * Date Last Modified: 09/04/2016
     */
    public static function getPackages()
    {
        $db = Yii::$app->db;
        
        $packages = $db->createCommand(
                " SELECT package.applicationperiodid AS 'id',"
                . " package.name AS 'package_name',"
                . " application_period.name AS 'period_name',"
                . " division.abbreviation AS 'division',"
                . " academic_year.title AS 'year',"
                . " package_type.name AS 'type',"
                . " package_progress.name AS 'progress',"
                . " package.createdby AS 'created_by',"
                . " package.lastmodifiedby AS 'last_modified_by',"
                . " package.datestarted AS 'start_date'," 
                . " package.datecompleted AS 'completion_date'," 
                . " package.documentcount AS 'document_count'"
                . " FROM package"
                . " JOIN application_period"
                . " ON package.applicationperiodid = application_period.applicationperiodid"
                . " JOIN division"
                . " ON application_period.divisionid = division.divisionid"
                . " JOIN academic_year"
                . " ON application_period.academicyearid = academic_year.academicyearid"
                . " JOIN package_type"
                . " ON package.packagetypeid = package_type.packagetypeid"
                . " JOIN package_progress"
                . " ON package.packageprogressid = package_progress.packageprogressid"
                . " JOIN person"
                . " ON package.createdby = person.personid"
//                . " JOIN user"
//                . " ON package.lastmodifiedby = user.personid"
                . " WHERE package.isactive = 1"
                . " AND package.isdeleted = 0;"
            )
            ->queryAll();

        return $packages;
    }
    
    
    /**
     * Returns the 'applicationperiodid' of the incomplete period
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 10/04/2016
     * Date Last Modified: 10/04/2016
     */
    public static function getIncompletePackageID()
    {
        $id = Yii::$app->user->identity->personid;
        $package = Package::find()
                ->where(['isactive' => 1, 'isdeleted' => 1 ,'lastmodifiedby' => $id, 'packageprogressid' => [1,2,4]])
                ->one();
        if ($package)
            return $package->packageid;
        return false;
    }
    
    
    /**
     * Returns true if package has never been used.
     * 
     * @param type $recordid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 10/04/2016
     * Date Last Modified: 10/04/2016
     */
    public static function safeToDelete($recordid)
    {
        $package = Package::find()
                ->where(['packageid' => $recordid]);
        if ($package==true  && $package->waspublished == 0)
            return true;
        return false;
    }
}
