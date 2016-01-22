<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "student_status".
 *
 * @property integer $studentstatusid
 * @property string $name
 * @property string $description
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property StatusHistory[] $statusHistories
 * @property StudentRegistration[] $studentRegistrations
 */
class StudentStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['description'], 'string'],
            [['isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'studentstatusid' => 'Studentstatusid',
            'name' => 'Name',
            'description' => 'Description',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusHistories()
    {
        return $this->hasMany(StatusHistory::className(), ['studentstatusid' => 'studentstatusid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentRegistrations()
    {
        return $this->hasMany(StudentRegistration::className(), ['studentstatusid' => 'studentstatusid']);
    }
    
    
    /**
     * Returns array prepared for dropdownlist
     * 
     * @param type $examination_body_id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 05/01/2016
     * Date Last Modified: 05/01/2016
     */
    public static function getStatuses()
    {
        $statuses = StudentStatus::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();

        $keys = array();
        array_push($keys, '');
        $values = array();
        array_push($values, 'Select...');
        $combined = array();

        if(count($statuses)==0)
        {
            $combined = array_combine($keys, $values);
            return $combined;
        }
        else
        {   //if centre records found
            foreach($statuses as $status)
            {
                $k = strval($status->studentstatusid);
                array_push($keys, $k);
                $v = strval($status->name);
                array_push($values, $v);
            }
            $combined = array_combine($keys, $values);
            return $combined;
        }
    }
}
