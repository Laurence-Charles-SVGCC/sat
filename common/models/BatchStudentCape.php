<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "batch_student_cape".
 *
 * @property integer $studentregistrationid
 * @property integer $batchcapeid
 * @property string $courseworktotal
 * @property string $examtotal
 * @property integer $final
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property BatchCape $batchcape
 * @property StudentRegistration $studentregistration
 */
class BatchStudentCape extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'batch_student_cape';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['studentregistrationid', 'batchcapeid'], 'required'],
            [['studentregistrationid', 'batchcapeid', 'final', 'isactive', 'isdeleted'], 'integer'],
            [['courseworktotal', 'examtotal'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'studentregistrationid' => 'Studentregistrationid',
            'batchcapeid' => 'Batchcapeid',
            'courseworktotal' => 'Courseworktotal',
            'examtotal' => 'Examtotal',
            'final' => 'Final',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchcape()
    {
        return $this->hasOne(BatchCape::class, ['batchcapeid' => 'batchcapeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentregistration()
    {
        return $this->hasOne(StudentRegistration::class, ['studentregistrationid' => 'studentregistrationid']);
    }
}
