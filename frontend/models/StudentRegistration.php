<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "student_registration".
 *
 * @property string $studentregistrationid
 * @property string $personid
 * @property string $academicofferingid
 * @property string $registrationtypeid
 * @property string $currentlevel
 * @property string $registrationdate
 * @property boolean $receivedpicture
 * @property boolean $cardready
 * @property boolean $cardcollected
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Person $person
 * @property AcademicOffering $academicoffering
 * @property RegistrationType $registrationtype
 */
class StudentRegistration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_registration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'academicofferingid', 'registrationtypeid', 'currentlevel', 'registrationdate'], 'required'],
            [['personid', 'academicofferingid', 'registrationtypeid', 'currentlevel'], 'integer'],
            [['registrationdate'], 'safe'],
            [['receivedpicture', 'cardready', 'cardcollected', 'isactive', 'isdeleted'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'studentregistrationid' => 'Studentregistrationid',
            'personid' => 'Personid',
            'academicofferingid' => 'Academicofferingid',
            'registrationtypeid' => 'Registrationtypeid',
            'currentlevel' => 'Currentlevel',
            'registrationdate' => 'Registrationdate',
            'receivedpicture' => 'Receivedpicture',
            'cardready' => 'Cardready',
            'cardcollected' => 'Cardcollected',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
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
    public function getAcademicoffering()
    {
        return $this->hasOne(AcademicOffering::className(), ['academicofferingid' => 'academicofferingid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrationtype()
    {
        return $this->hasOne(RegistrationType::className(), ['registrationtypeid' => 'registrationtypeid']);
    }
}
