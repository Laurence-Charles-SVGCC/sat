<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "maternity_leave".
 *
 * @property integer $maternityleaveid
 * @property integer $studentregistrationid
 * @property string $summary
 * @property string $description
 * @property string $startdate
 * @property string $enddate
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property StudentRegistration $studentregistration
 */
class MaternityLeave extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'maternity_leave';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['studentregistrationid', 'summary', 'description'], 'required'],
            [['studentregistrationid', 'isactive', 'isdeleted'], 'integer'],
            [['summary', 'description'], 'string'],
            [['startdate', 'enddate'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'maternityleaveid' => 'Maternityleaveid',
            'studentregistrationid' => 'Studentregistrationid',
            'summary' => 'Summary',
            'description' => 'Description',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistration()
    {
        return $this->hasOne(StudentRegistration::className(), ['studentregistrationid' => 'studentregistrationid']);
    }
}
