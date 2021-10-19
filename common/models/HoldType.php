<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hold_type".
 *
 * @property int $holdtypeid
 * @property int $holdcategoryid
 * @property string $name
 * @property string $description
 * @property string $displaymessage
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property HoldCategory $holdcategory
 * @property StudentHold[] $studentHolds
 */
class HoldType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hold_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['holdcategoryid', 'name', 'description', 'displaymessage'], 'required'],
            [['holdcategoryid', 'isactive', 'isdeleted'], 'integer'],
            [['description', 'displaymessage'], 'string'],
            [['name'], 'string', 'max' => 45],
            [['holdcategoryid'], 'exist', 'skipOnError' => true, 'targetClass' => HoldCategory::class, 'targetAttribute' => ['holdcategoryid' => 'holdcategoryid']],
        ];
    }

    /**
     * {@inheritdoc}
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
    public function getHoldcategory()
    {
        return $this->hasOne(HoldCategory::class, ['holdcategoryid' => 'holdcategoryid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentHolds()
    {
        return $this->hasMany(StudentHold::class, ['holdtypeid' => 'holdtypeid']);
    }
}
