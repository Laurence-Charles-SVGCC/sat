<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property string $departmentid
 * @property string $divisionid
 * @property string $name
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Division $division
 * @property EmployeeDepartment[] $employeeDepartments
 * @property ProgrammeCatalog[] $programmeCatalogs
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['divisionid', 'name'], 'required'],
            [['divisionid'], 'integer'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'departmentid' => 'Departmentid',
            'divisionid' => 'Divisionid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
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
    public function getEmployeeDepartments()
    {
        return $this->hasMany(EmployeeDepartment::className(), ['departmentid' => 'departmentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgrammeCatalogs()
    {
        return $this->hasMany(ProgrammeCatalog::className(), ['departmentid' => 'departmentid']);
    }
}
