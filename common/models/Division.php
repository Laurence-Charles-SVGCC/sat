<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "division".
 *
 * @property int $divisionid
 * @property string $name
 * @property string $abbreviation
 * @property string $phone
 * @property string $website
 * @property string $email
 * @property string $country
 * @property string $constituency
 * @property string $town
 * @property string $addressline
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property Application[] $applications
 * @property ApplicationPeriod[] $applicationPeriods
 * @property Award[] $awards
 * @property ClubDivision[] $clubDivisions
 * @property Club[] $clubs
 * @property DeanDivision[] $deanDivisions
 * @property Department[] $departments
 * @property EmployeeDivision[] $employeeDivisions
 */
class Division extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'division';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'abbreviation', 'phone'], 'required'],
            [['isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['abbreviation', 'phone'], 'string', 'max' => 15],
            [['website', 'email', 'country', 'constituency', 'town', 'addressline'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
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
        return $this->hasMany(Application::class, ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationPeriods()
    {
        return $this->hasMany(ApplicationPeriod::class, ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAwards()
    {
        return $this->hasMany(Award::class, ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClubDivisions()
    {
        return $this->hasMany(ClubDivision::class, ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClubs()
    {
        return $this->hasMany(Club::class, ['clubid' => 'clubid'])->viaTable('club_division', ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeanDivisions()
    {
        return $this->hasMany(DeanDivision::class, ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartments()
    {
        return $this->hasMany(Department::class, ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeDivisions()
    {
        return $this->hasMany(EmployeeDivision::class, ['divisionid' => 'divisionid']);
    }
}
