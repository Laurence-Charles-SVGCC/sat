<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "person_institution".
 *
 * @property string $personinstitutionid
 * @property string $personid
 * @property string $institutionid
 * @property string $startdate
 * @property string $enddate
 * @property boolean $hasgraduated
 * @property boolean $isactive
 * @property boolean $isdeleted
 * @property string $unverifiedinstitutionid
 *
 * @property Institution $institution
 * @property Person $person
 * @property UnverifiedInstitution $unverifiedinstitution
 */
class PersonInstitution extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person_institution';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'institutionid', 'startdate', 'enddate', 'hasgraduated'], 'required'],
            [['personid', 'institutionid', 'unverifiedinstitutionid', 'hasgraduated', 'isactive', 'isdeleted'], 'integer'],
            [['startdate', 'enddate'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'personinstitutionid' => 'Personinstitutionid',
            'personid' => 'Personid',
            'institutionid' => 'Institutionid',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'hasgraduated' => 'Hasgraduated',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'unverifiedinstitutionid' => 'Unverifiedinstitutionid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitution()
    {
        return $this->hasOne(Institution::className(), ['institutionid' => 'institutionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['personid' => 'personid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnverifiedinstitution()
    {
        return $this->hasOne(UnverifiedInstitution::className(), ['unverifiedinstitutionid' => 'unverifiedinstitutionid']);
    }
    
    
    /**
     * Returns a "PersonInstitution" record based on personid and level of school
     * 
     * @param type $id
     * @param type $type
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 23/12/2015
     * Date Last Modified: 23/12/2015
     */
    public static function getPersonInsitutionRecords($id, $type)
    {
        $records = PersonInstitution::find()
               ->joinWith('institution')
               ->where(['person_institution.personid'=> $id, 'institution.levelid'=> $type, 'person_institution.isactive' => 1 , 'person_institution.isdeleted' => 0]) 
               ->all();
        if (count($records) > 0)
            return $records;
        return false;
    }
}
