<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "registration_type".
 *
 * @property string $registrationtypeid
 * @property string $name
 * @property string $description
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property StudentRegistration[] $studentRegistrations
 */
class RegistrationType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'registration_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['description'], 'string'],
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
            'registrationtypeid' => 'Registrationtypeid',
            'name' => 'Name',
            'description' => 'Description',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentRegistrations()
    {
        return $this->hasMany(StudentRegistration::className(), ['registrationtypeid' => 'registrationtypeid']);
    }
}
