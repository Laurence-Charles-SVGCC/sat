<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "pass_fail_type".
 *
 * @property string $passfailtypeid
 * @property string $description
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property CourseOffering[] $courseOfferings
 */
class PassFailType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pass_fail_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['description'], 'string'],
            [['isactive', 'isdeleted'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'passfailtypeid' => 'Passfailtypeid',
            'description' => 'Description',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseOfferings()
    {
        return $this->hasMany(CourseOffering::className(), ['passfailtypeid' => 'passfailtypeid']);
    }
}
