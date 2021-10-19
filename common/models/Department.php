<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property int $departmentid
 * @property int $divisionid
 * @property string $name
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property Award[] $awards
 * @property Cordinator[] $cordinators
 * @property Division $division
 * @property EmployeeDepartment[] $employeeDepartments
 * @property ProgrammeCatalog[] $programmeCatalogs
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['divisionid', 'name'], 'required'],
            [['divisionid', 'isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['divisionid'], 'exist', 'skipOnError' => true, 'targetClass' => Division::class, 'targetAttribute' => ['divisionid' => 'divisionid']],
        ];
    }

    /**
     * {@inheritdoc}
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
    public function getAwards()
    {
        return $this->hasMany(Award::class, ['departmentid' => 'departmentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCordinators()
    {
        return $this->hasMany(Cordinator::class, ['departmentid' => 'departmentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDivision()
    {
        return $this->hasOne(Division::class, ['divisionid' => 'divisionid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeDepartments()
    {
        return $this->hasMany(EmployeeDepartment::class, ['departmentid' => 'departmentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgrammeCatalogs()
    {
        return $this->hasMany(ProgrammeCatalog::class, ['departmentid' => 'departmentid']);
    }
}
