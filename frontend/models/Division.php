<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "division".
 *
 * @property string $divisionid
 * @property string $name
 * @property string $abbreviation
 * @property string $phone
 * @property string $website
 * @property string $email
 * @property string $country
 * @property string $constituency
 * @property string $town
 * @property string $addressline
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Application[] $applications
 * @property ApplicationPeriod[] $applicationPeriods
 * @property ClubDivision[] $clubDivisions
 * @property Club[] $clubs
 * @property DeanDivision[] $deanDivisions
 * @property Department[] $departments
 */
class Division extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'division';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'abbreviation', 'phone'], 'required'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['name'], 'string', 'max' => 100],
            [['abbreviation', 'phone'], 'string', 'max' => 15],
            [['website', 'email', 'country', 'constituency', 'town', 'addressline'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'divisionid' => 'Divisionid',
            'name' => 'Name',
            'abbreviation' => 'Abbreviation',
            'phone' => 'Phone',
            'website' => 'Website',
            'email' => 'Email',
            'country' => 'Country',
            'constituency' => 'Constituency',
            'town' => 'Town',
            'addressline' => 'Addressline',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::className(), ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationPeriods()
    {
        return $this->hasMany(ApplicationPeriod::className(), ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClubDivisions()
    {
        return $this->hasMany(ClubDivision::className(), ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClubs()
    {
        return $this->hasMany(Club::className(), ['clubid' => 'clubid'])->viaTable('club_division', ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeanDivisions()
    {
        return $this->hasMany(DeanDivision::className(), ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartments()
    {
        return $this->hasMany(Department::className(), ['divisionid' => 'divisionid']);
    }
}
