<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "registration_type".
 *
 * @property integer $registrationtypeid
 * @property string $name
 * @property string $description
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer $creator_id
 * @property integer $modifier_id
 * @property string $created_at
 * @property string $modified_at
 *
 * @property Person $creator
 * @property Person $modifier
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
            [['isactive', 'isdeleted', 'creator_id', 'modifier_id'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
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
            'creator_id' => 'Creator ID',
            'modifier_id' => 'Modifier ID',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(Person::className(), ['personid' => 'creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifier()
    {
        return $this->hasOne(Person::className(), ['personid' => 'modifier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentRegistrations()
    {
        return $this->hasMany(StudentRegistration::className(), ['registrationtypeid' => 'registrationtypeid']);
    }
}
