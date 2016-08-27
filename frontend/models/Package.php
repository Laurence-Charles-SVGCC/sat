<?php

namespace frontend\models;

use Yii;
use yii\helpers\FileHelper;

use frontend\models\Offer;

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
 * @property string commencementdate
 * @property string $emailtitle
 * @property string $emailcontent
 * @property string $datestarted
 * @property string $datecompleted
 * @property integer $documentcount
 * @property integer $disclaimer
 * @property integer $publishcount
 * @property integer $waspublished
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $lastmodifiedby0
 * @property ApplicationPeriod $applicationperiod
 * @property PackageType $packagetype
 * @property PackageProgress $packageprogress
 * @property Person $createdby0
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
            [['applicationperiodid', 'packagetypeid', 'packageprogressid', 'createdby', 'lastmodifiedby', 'documentcount', 'isactive', 'isdeleted', 'waspublished', 'publishcount'], 'integer'],
            [['datestarted', 'datecompleted'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['commencementdate'], 'string', 'max' => 100],
            [['emailtitle', 'emailcontent', 'disclaimer'], 'string'],
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
            'disclaimer' => 'Disclaimer',
            'publishcount' => 'PublishCount',
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
                " SELECT package.packageid AS 'id',"
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
                . " WHERE package.isdeleted = 0"
                . " AND package.packageprogressid = 4;"
//                . " AND application_period.iscomplete=0;"
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
                ->where(['isactive' => 0, 'isdeleted' => 0, 'packageprogressid' => [1,2,3]])
                ->one();
        if ($package)
            return $package->packageid;
        return false;
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
    public static function getIncompletePackage()
    {
        $id = Yii::$app->user->identity->personid;
        $package = Package::find()
                ->where(['isactive' => 0, 'isdeleted' => 0, 'packageprogressid' => [1,2,3]])
                ->one();
        if ($package)
            return $package;
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
     * Date Last Modified: 10/04/2016 | 11/04/2016
     */
    public static function safeToDelete($recordid)
    {
        $package = Package::find()
                ->where(['packageid' => $recordid])
                ->one();
        if ($package==true  && $package->waspublished == 0)
            return true;
        return false;
    }
    
    
    /**
     * if $packageif == NULL;
     *  -> get the documents of the current outstanding package
     * else
     *  -> get the documents of specified package
     * 
     * @param type $packageid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 11/04/2016
     * Date Last Modified: 12/04/2016
     */
    public static function getDocuments($packageid = NULL)
    {
        if ($packageid == NULL)
            $package = self::getIncompletePackage();
        else
        {
            $package = Package::find()
                ->where(['packageid' => $packageid, 'isdeleted' => 0])
                ->one();
        }

        $dir = Yii::getAlias('@frontend') . "/files/packages/" . $package->packageid . "_" . $package->name;

        $files = FileHelper::findFiles($dir);

        return $files;
    }


    /**
     * Returns true if document limit for package is met
     *
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 12/04/2016
     * Date Last Modified: 12/04/2016
     */
    public static function hasAllDocuments($packageid = NULL)
    {
        if ($packageid == NULL)
            $package = self::getIncompletePackage();
        else
        {
            $package = Package::find()
                ->where(['packageid' => $packageid])
                ->one();
        }

        $dir = Yii::getAlias('@frontend') . "/files/packages/" . $package->packageid . "_" . $package->name;

        $files = FileHelper::findFiles($dir);
        
        if (count($files) == $package->documentcount)
            return true;
        return false;
    }


    /**
     * Assess number of documents associated with a particular package
     * if < package's documentcount, return -1;
     * elseif == package's documentcount return 0
     * elseif > package's documentcount return 1
     *
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 12/04/2016
     * Date Last Modified: 12/04/2016
     */
    public static function assessDocuments($packageid = NULL)
    {
        if ($packageid == NULL)
            $package = self::getIncompletePackage();
        else
        {
            $package = Package::find()
                ->where(['packageid' => $packageid])
                ->one();
        }

//        $dir = "frontend/files/packages/" . $package->packageid . "_" . $package->name;
        $dir = Yii::getAlias('@frontend') . "/files/packages/" . $package->packageid . "_" . $package->name;

        $files = FileHelper::findFiles($dir);
        if (count($files) < $package->documentcount)
            return -1;
        elseif (count($files) == $package->documentcount)
            return 0;
        else
            return 1;
    }
    
    
    /**
     * Returns true if package has a document upload requirement
     * 
     * @param type $recordid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 15/04/2016
     * Date Last Modified: 15/04/2016
     */
    public static function needsToUpload($recordid)
    {
        $package = Package::find()
                ->where(['packageid' => $recordid])
                ->one();
        if ($package && $package->documentcount>0)
            return true;
        return false;
    }
    
    
    /**
     * Returns tru if the package in question hase been used by a published offer
     * 
     * @param type $packageid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 17/04/2016
     * Date Last Modified: 17/04/2016
     */
    public static function hasBeenPublished($packageid)
    {
        $package = Package::find()
                ->where(['packageid' => $packageid, 'waspublished'=> 1])
                ->one();
        if($package)
            return true;
        return false;
    }
    
    
    /**
     * Returns true if a package of the same type currently exists.
     * 
     * @param type $packagetypeid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 19/04/2016
     * Date Last Modified: 19/04/2016
     */
    public static function currentPackageTypeExists($packagetypeid, $divisionid)
    {
        $package = Package::find()
                ->innerJoin('`application_period`', '`package`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                ->where(['package.packagetypeid' => $packagetypeid, 'package.isactive' => 1, 'package.isdeleted' => 0, 
                                'application_period.divisionid' => $divisionid, 'application_period.iscomplete' => 0, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                            ])
                ->one();
        if ($package)
            return true;
        return false;
    }
    
    
    
    /**
     * Returns true if 'complete' package exists
     * 
     * @param type $category
     * @param type $type
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 10/04/2016
     * Date Last Modified: 10/04/2016
     */
    public static function hasCompletePackage($divisionid, $category = NULL, $type = NULL)
    {
        /*
         * if offertype or rejectiontype is not specified,
         * look for the presence of any complete packages of that category
         */
        if($type == NULL)   
        {
            if($category === 0)  // if dealing with rejects
            {
                $packagetypeids = [1,2];
            }
            elseif($category === 1)  // if dealing with offers
            {
                $packagetypeids = [3,4];
            }
        }
        /**
         * package search is now contrained to offertype/rejectiontype
         */
        else
        {
            if ($category==0 && $type == 1) 
                $packagetypeids = 1;
            
            elseif ($category==0 && $type == 2) 
                $packagetypeids = 2;
            
            elseif ($category==1 && $type == 1) 
                $packagetypeids = 4;
            
            elseif ($category==1 && $type == 2) 
                $packagetypeids = 3;
        } 
            
        if($divisionid == 1)
        {
            $package = Package::find()
                        ->innerJoin('application_period', '`package`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->where(['package.isactive' => 1, 'package.isdeleted' => 0, 'package.packageprogressid' => 4, 'package.packagetypeid' => $packagetypeids,
                                        'application_period.iscomplete' => 0, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0])
                        ->one();    
        }
        else
        {
            $package = Package::find()
                        ->innerJoin('application_period', '`package`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                        ->where(['package.isactive' => 1, 'package.isdeleted' => 0, 'package.packageprogressid' => 4, 'package.packagetypeid' => $packagetypeids,
                                        'application_period.divisionid' => $divisionid, 'application_period.iscomplete' => 0, 'application_period.isactive' => 1, 'application_period.isdeleted' => 0])
                        ->one();    
        }
        
        if ($package)
            return true;
        return false;
    }
    
    
    
    
}
