<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "cape_subject_group".
 *
 * @property string $capegroupid
 * @property string $capesubjectid
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property CapeGroup $capegroup
 * @property CapeSubject $capesubject
 */
class CapeSubjectGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cape_subject_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['capegroupid', 'capesubjectid'], 'required'],
            [['capesubjectid'], 'required'],
            [['capegroupid', 'capesubjectid', 'isactive', 'isdeleted'], 'integer'],
            [['isactive', 'isdeleted'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'capegroupid' => 'Capegroupid',
            'capesubjectid' => 'Capesubjectid',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapegroup()
    {
        return $this->hasOne(CapeGroup::className(), ['capegroupid' => 'capegroupid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapesubject()
    {
        return $this->hasOne(CapeSubject::className(), ['capesubjectid' => 'capesubjectid']);
    }
    
    
    /**
     * Returns an array of cape subjects associated with a particular CapeGroup
     * 
     * @param type $groupid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 09/01/2016
     * Date Last Modified: 09/01/2016
     */
    public static function getSubjects($groupid)
    {
        $subjects = CapeSubjectGroup::find()
            ->where(['capegroupid' => $groupid, 'isactive' => 1, 'isdeleted' => 0])
            ->all();
        return $subjects;
    }
    
    /**
     * Returns an array of cape subjects associated with a particular CapeGroup for active period
     * 
     * @param type $groupid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 26/08/2016
     * Date Last Modified: 26/08/2016
     */
    public static function getActiveSubjects($groupid)
    {
        $subjects = CapeSubjectGroup::find()
                ->innerJoin('cape_subject', '`cape_subject_group`.`capesubjectid` = `cape_subject`.`capesubjectid`')
                ->innerJoin('academic_offering', '`cape_subject`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                ->where(['cape_subject_group.capegroupid' => $groupid, 'cape_subject_group.isactive' => 1, 'cape_subject_group.isdeleted' => 0,
                                'cape_subject.isactive' => 1, 'cape_subject.isdeleted' => 0,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                'application_period.iscomplete' => 0,  'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                              ])
            ->all();
        return $subjects;
    }
    
    
    /**
     * Returns most recent cape subjects
     * 
     * @param type $groupid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 17/09/2016
     * Date Last Modified: 17/09/2016
     */
    public static function getMostRecentCapeSubjects($groupid)
    {
        $subjects = CapeSubjectGroup::find()
                ->innerJoin('cape_subject', '`cape_subject_group`.`capesubjectid` = `cape_subject`.`capesubjectid`')
                ->innerJoin('academic_offering', '`cape_subject`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                ->innerJoin('academic_year', '`application_period`.`academicyearid` = `academic_year`.`academicyearid`')
                ->where(['cape_subject_group.capegroupid' => $groupid, 'cape_subject_group.isactive' => 1, 'cape_subject_group.isdeleted' => 0,
                                'cape_subject.isactive' => 1, 'cape_subject.isdeleted' => 0,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                'application_period.iscomplete' => 0,  'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                              ])
            ->all();
        return $subjects;
    }
    
    
    /**
     * Returns an array of the associated CapeSubjectGroup records
     * 
     * @param type $applicationperiodid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 09/01/2016
     * Date Last Modified: 09/01/2016
     */
    public static function getAssociatedCapeGroups($applicationperiodid)
    {
        $records = CapeSubjectGroup::find()
                ->innerJoin('cape_subject', '`cape_subject_group`.`capesubjectid` = `cape_subject`.`capesubjectid`')
                ->innerJoin('academic_offering', '`cape_subject`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                ->innerJoin('programme_catalog', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                ->where(['academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 'programme_catalog.name' => 'CAPE', 'academic_offering.applicationperiodid' => $applicationperiodid])
                ->all();
        if (count($records) > 0)
            return $records;
        return false;
    }
    
    
    /**
     * Creates backup of a collection of CapeSubjectGroups records
     * 
     * @param type $groups
     * @return array
     * 
     * Author: Laurence Charles
     * Date Created: 16/02/2016
     * Date Last Modified: 16/02/2016
     */
    public static function backup($groups)
    {
        $saved = array();
         
        foreach ($groups as $group)
        {
            $temp = NULL;
            $temp = new CapeSubjectGroup();
            $temp->capegroupid = $group->capegroupid;
            $temp->capesubjectid = $group->capesubjectid;
            $temp->isactive = $group->isactive;
            $temp->isdeleted = $group->isdeleted;
            array_push($saved, $temp);      
        }
        return $saved;
    }
    
    
    /**
     * Saves a collection records
     * 
     * @param type $groups
     * 
     * Author: Laurence Charles
     * Date Created: 16/02/2016
     * Date Last Modified: 16/02/2016
     */
    public static function restore($groups)
    {
        foreach($groups as $group)
        {
            $group->save();
        }
    }
    
    
    /**
     * Delete collection of capesubjectgroups
     * 
     * @param type $groups
     * 
     * Author: Laurence Charles
     * Date Created: 16/02/2016
     * Date Last Modified: 16/02/2016
     */
    public static function deleteGroups($groups)
    {
        foreach($groups as $group)
        {
            $group->delete();
        }
    }
    
    
    
}
