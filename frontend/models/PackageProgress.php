<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "package_progress".
 *
 * @property integer $packageprogressid
 * @property string $name
 * @property string $description
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Package[] $packages
 */
class PackageProgress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'package_progress';
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
            'packageprogressid' => 'Packageprogressid',
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
        return $this->hasMany(Package::className(), ['packageprogressid' => 'packageprogressid']);
    }
}
