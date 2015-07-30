<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "employee_title".
 *
 * @property string $employeetitleid
 * @property string $employeecategoryid
 * @property string $name
 * @property boolean $issenior
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Employee[] $employees
 * @property EmployeeCategory $employeecategory
 */
class EmployeeTitle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee_title';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employeecategoryid', 'name'], 'required'],
            [['employeecategoryid'], 'integer'],
            [['issenior', 'isactive', 'isdeleted'], 'boolean'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'employeetitleid' => 'Employeetitleid',
            'employeecategoryid' => 'Employeecategoryid',
            'name' => 'Name',
            'issenior' => 'Issenior',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['employeetitleid' => 'employeetitleid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeecategory()
    {
        return $this->hasOne(EmployeeCategory::className(), ['employeecategoryid' => 'employeecategoryid']);
    }
}
