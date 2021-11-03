<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "csec_qualification".
 *
 * @property integer $csecqualificationid
 * @property integer $cseccentreid
 * @property string $candidatenumber
 * @property integer $personid
 * @property integer $examinationbodyid
 * @property integer $subjectid
 * @property integer $examinationproficiencytypeid
 * @property string $year
 * @property integer $examinationgradeid
 * @property integer $isverified
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer $isqueried
 *
 * @property CsecCentre $cseccentre
 * @property Person $person
 * @property ExaminationBody $examinationbody
 * @property Subject $subject
 * @property ExaminationProficiencyType $examinationproficiencytype
 * @property ExaminationGrade $examinationgrade
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
            [['cseccentreid', 'personid', 'examinationbodyid', 'subjectid', 'examinationproficiencytypeid', 'examinationgradeid', 'isverified', 'isactive', 'isdeleted', 'isqueried'], 'integer'],
            [['year'], 'safe'],
            [['candidatenumber'], 'string', 'max' => 20]
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
            'candidatenumber' => 'Candidatenumber',
            'personid' => 'Personid',
            'examinationbodyid' => 'Examinationbodyid',
            'subjectid' => 'Subjectid',
            'examinationproficiencytypeid' => 'Examinationproficiencytypeid',
            'year' => 'Year',
            'examinationgradeid' => 'Examinationgradeid',
            'isverified' => 'Isverified',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'isqueried' => 'Isqueried',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCseccentre()
    {
        return $this->hasOne(CsecCentre::className(), ['cseccentreid' => 'cseccentreid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['personid' => 'personid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExaminationbody()
    {
        return $this->hasOne(ExaminationBody::className(), ['examinationbodyid' => 'examinationbodyid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(Subject::className(), ['subjectid' => 'subjectid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExaminationproficiencytype()
    {
        return $this->hasOne(ExaminationProficiencyType::className(), ['examinationproficiencytypeid' => 'examinationproficiencytypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExaminationgrade()
    {
        return $this->hasOne(ExaminationGrade::className(), ['examinationgradeid' => 'examinationgradeid']);
    }
}
