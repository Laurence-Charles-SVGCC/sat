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
    
    
    /**
     * Returns an array containing the education departments of a particular division
     * 
     * @param type $divisionid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 07/12/2015
     * Date Last Modified: 07/12/2015
     */
    public static function getDepartments($divisionid)
    {
        if ($divisionid == 4)      //if DASGS
        {
            $departments = Department::find()
                        ->where(['divisionid' => $divisionid, 'isactive' => 1, 'isdeleted' => 0])
                        ->andWhere(['not', ['name' => 'DASGS Senior']])
                        ->andWhere(['not', ['name' => 'Administrative (DASGS)']])
                        ->andWhere(['not', ['name' => 'Library (DASGS)']])
                        ->all();             
        }
        elseif ($divisionid == 5)      //if DTVE
        {
            $departments = Department::find()
                         ->where(['divisionid' => $divisionid, 'isactive' => 1, 'isdeleted' => 0])
                        ->andWhere(['not', ['name' => 'DTVE Senior']])
                        ->andWhere(['not', ['name' => 'Administrative (DTVE)']])
                        ->andWhere(['not', ['name' => 'Library (DTVE)']])
                        ->all();
        }
        elseif ($divisionid == 6)      //if DTE
        {
            $departments = Department::find()
                         ->where(['divisionid' => $divisionid, 'isactive' => 1, 'isdeleted' => 0])
                        ->andWhere(['not', ['name' => 'DTE Senior']])
                        ->andWhere(['not', ['name' => 'Administrative (DTE)']])
                        ->andWhere(['not', ['name' => 'Library (DTE)']])
                        ->all();  
        }
        elseif ($divisionid == 7)      //if DNE
        {
            $departments = Department::find()
                        ->where(['divisionid' => $divisionid, 'isactive' => 1, 'isdeleted' => 0])
                        ->andWhere(['not', ['name' => 'DNE Senior']])
                        ->andWhere(['not', ['name' => 'Administrative (DNE)']])
                        ->andWhere(['not', ['name' => 'Library (DNE)']])
                        ->all(); 
        }
        
        if (count($departments) > 0)
            return $departments;
        else
            return false;
    }
    
    
}
