<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "programme_catalog".
 *
 * @property string $programmecatalogid
 * @property string $examinationbodyid
 * @property string $qualificationtypeid
 * @property string $departmentid
 * @property string $creationdate
 * @property string $specialisation
 * @property integer $duration
 * @property string $name
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property AcademicOffering[] $academicOfferings
 * @property ExaminationBody $examinationbody
 * @property QualificationType $qualificationtype
 * @property Department $department
 */
class ProgrammeCatalog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'programme_catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['examinationbodyid', 'qualificationtypeid', 'departmentid', 'creationdate', 'duration', 'name'], 'required'],
            [['examinationbodyid', 'qualificationtypeid', 'departmentid', 'duration'], 'integer'],
            [['creationdate'], 'safe'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['specialisation', 'name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'programmecatalogid' => 'Programmecatalogid',
            'examinationbodyid' => 'Examination Body',
            'qualificationtypeid' => 'Qualification Type',
            'departmentid' => 'Department',
            'creationdate' => 'Date Created',
            'specialisation' => 'Specialisation',
            'duration' => 'Duration (Years)',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicOfferings()
    {
        return $this->hasMany(AcademicOffering::className(), ['programmecatalogid' => 'programmecatalogid']);
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
    public function getQualificationtype()
    {
        return $this->hasOne(QualificationType::className(), ['qualificationtypeid' => 'qualificationtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::className(), ['departmentid' => 'departmentid']);
    }
}
