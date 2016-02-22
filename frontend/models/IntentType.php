<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "intent_type".
 *
 * @property integer $intenttypeid
 * @property string $name
 * @property string $description
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property ApplicantIntent[] $applicantIntents
 * @property ProgrammeCatalog[] $programmeCatalogs
 */
class IntentType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'intent_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['description'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'intenttypeid' => 'Intenttypeid',
            'name' => 'Name',
            'description' => 'Description',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantIntents()
    {
        return $this->hasMany(ApplicantIntent::className(), ['intenttypeid' => 'intenttypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgrammeCatalogs()
    {
        return $this->hasMany(ProgrammeCatalog::className(), ['programmetypeid' => 'intenttypeid']);
    }
}
