<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "hold_type".
 *
 * @property integer $holdtypeid
 * @property integer $holdcategoryid
 * @property string $name
 * @property string $description
 * @property string $displaymessage
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property HoldCategory $holdcategory
 * @property StudentHold[] $studentHolds
 */
class HoldType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hold_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['holdcategoryid', 'name', 'description', 'displaymessage'], 'required'],
            [['holdcategoryid', 'isactive', 'isdeleted'], 'integer'],
            [['description', 'displaymessage'], 'string'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'holdtypeid' => 'Holdtypeid',
            'holdcategoryid' => 'Holdcategoryid',
            'name' => 'Name',
            'description' => 'Description',
            'displaymessage' => 'Displaymessage',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function gethold_category()
    {
        return $this->hasOne(HoldCategory::className(), ['holdcategoryid' => 'holdcategoryid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentHolds()
    {
        return $this->hasMany(StudentHold::className(), ['holdtypeid' => 'holdtypeid']);
    }
}
