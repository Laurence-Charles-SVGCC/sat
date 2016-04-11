<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "package_type".
 *
 * @property integer $packagetypeid
 * @property string $name
 * @property string $description
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Package[] $packages
 */
class PackageType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'package_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['description'], 'string'],
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
            'packagetypeid' => 'Packagetypeid',
            'name' => 'Name',
            'description' => 'Description',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackages()
    {
        return $this->hasMany(Package::className(), ['packagetypeid' => 'packagetypeid']);
    }
}
