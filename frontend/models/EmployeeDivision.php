<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "employee_division".
 *
 * @property integer $employeedivisionid
 * @property integer $employeeid
 * @property integer $divisionid
 * @property string $startdate
 * @property string $enddate
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $employee
 * @property Division $division
 */
class EmployeeDivision extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee_division';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employeeid', 'divisionid'], 'required'],
            [['employeeid', 'divisionid', 'isactive', 'isdeleted'], 'integer'],
            [['startdate', 'enddate'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'employeedivisionid' => 'Employeedivisionid',
            'employeeid' => 'Employeeid',
            'divisionid' => 'Divisionid',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Person::className(), ['personid' => 'employeeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDivision()
    {
        return $this->hasOne(Division::className(), ['divisionid' => 'divisionid']);
    }
    
    
    
}
