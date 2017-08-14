<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "application_settings".
 *
 * @property string $id
 * @property integer $is_online
 * @property integer $allow_administrator
 */
class ApplicationSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_online', 'allow_administrator'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'is_online' => 'Is Online',
            'allow_administrator' => 'Allow Administrator',
        ];
    }
    
    
    public static function getApplicationSettings()
    {
        return ApplicationSettings::find()
                ->where(['id' => 1])
                ->one();
    }
}
