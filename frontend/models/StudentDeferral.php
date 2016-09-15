<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "student_deferral".
 *
 * @property string $studentdeferralid
 * @property string $personid
 * @property string $deferralofficer
 * @property string $registrationfrom
 * @property string $registrationto
 * @property string $deferraldate
 * @property integer $iscurrent
 * @property integer $isactive
 * @property integer $isdeleted
 */
class StudentDeferral extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_deferral';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['studentdeferralid', 'personid', 'deferralofficer', 'registrationfrom', 'registrationto', 'deferraldate'], 'required'],
            [['studentdeferralid', 'personid', 'deferralofficer', 'registrationfrom', 'registrationto', 'iscurrent', 'isactive', 'isdeleted'], 'integer'],
            [['deferraldate'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'studentdeferralid' => 'Studentdeferralid',
            'personid' => 'Personid',
            'deferralofficer' => 'Deferralofficer',
            'registrationfrom' => 'Registrationfrom',
            'registrationto' => 'Registrationto',
            'deferraldate' => 'Deferraldate',
            'iscurrent' => 'Iscurrent',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
}
