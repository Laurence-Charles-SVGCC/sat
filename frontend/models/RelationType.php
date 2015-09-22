<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "relation_type".
 *
 * @property string $relationtypeid
 * @property string $name
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Relation[] $relations
 * @property Relative[] $relatives
 */
class RelationType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'relation_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'relationtypeid' => 'Relationtypeid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelations()
    {
        return $this->hasMany(Relation::className(), ['relationtypeid' => 'relationtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatives()
    {
        return $this->hasMany(Relative::className(), ['relationtypeid' => 'relationtypeid']);
    }
}
