<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "csec_qualification".
 *
 * @property string $csecqualificationid
 * @property string $cseccentreid
 * @property string $personid
 * @property string $examinationbodyid
 * @property string $subjectid
 * @property string $examinationproficiencytypeid
 * @property boolean $isverified
 * @property boolean $isqueried
 * @property boolean $isactive
 * @property boolean $isdeleted
 */
class CsecQualification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'csec_qualification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cseccentreid', 'personid', 'examinationbodyid', 'subjectid', 'examinationproficiencytypeid'], 'required'],
            [['cseccentreid', 'personid', 'examinationbodyid', 'subjectid', 'examinationproficiencytypeid'], 'integer'],
            [['isverified', 'isqueried', 'isactive', 'isdeleted'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'csecqualificationid' => 'Csecqualificationid',
            'cseccentreid' => 'Cseccentreid',
            'personid' => 'Personid',
            'examinationbodyid' => 'Examinationbodyid',
            'subjectid' => 'Subjectid',
            'examinationproficiencytypeid' => 'Examinationproficiencytypeid',
            'isverified' => 'Isverified',
            'isqueried' => 'Isqueried',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
}
