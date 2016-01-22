<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "academic_status".
 *
 * @property integer $academicstatusid
 * @property string $name
 * @property string $description
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property AcademicStatusHistory[] $academicStatusHistories
 * @property StudentRegistration[] $studentRegistrations
 */
class AcademicStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'academic_status';
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
            'academicstatusid' => 'Academicstatusid',
            'name' => 'Name',
            'description' => 'Description',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicStatusHistories()
    {
        return $this->hasMany(AcademicStatusHistory::className(), ['academicstatusid' => 'academicstatusid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentRegistrations()
    {
        return $this->hasMany(StudentRegistration::className(), ['academicstatusid' => 'academicstatusid']);
    }
    
    
    public static function getStatus($academicstatusid)
    {
        $status = AcademicStatus::find()
                ->where(['academicstatusid' => $academicstatusid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        if ($status)
            return $status->name;
        return false;
    }
}
