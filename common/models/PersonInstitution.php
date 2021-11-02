<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "person_institution".
 *
 * @property integer $personinstitutionid
 * @property integer $personid
 * @property integer $institutionid
 * @property string $startdate
 * @property string $enddate
 * @property integer $hasgraduated
 * @property string $year_of_graduation
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer $unverifiedinstitutionid
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
            [['personid', 'institutionid'], 'required'],
            [['personid', 'institutionid', 'hasgraduated', 'isactive', 'isdeleted', 'unverifiedinstitutionid'], 'integer'],
            [['startdate', 'enddate'], 'safe'],
            [['year_of_graduation'], 'string', 'max' => 4]
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
            'year_of_graduation' => 'Year Of Graduation',
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
}
