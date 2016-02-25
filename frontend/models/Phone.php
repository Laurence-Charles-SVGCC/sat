<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "phone".
 *
 * @property string $phoneid
 * @property string $personid
 * @property string $homephone
 * @property string $cellphone
 * @property string $workphone
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 */
class Phone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'phone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid'], 'required'],
            [['personid', 'isactive', 'isdeleted'], 'integer'],
            [['homephone', 'cellphone', 'workphone'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phoneid' => 'Phoneid',
            'personid' => 'Personid',
            'homephone' => 'Homephone',
            'cellphone' => 'Cellphone',
            'workphone' => 'Workphone',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['personid' => 'personid']);
    }
    
    
    /**
     * Retruns a phone record
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 23/02/2016
     * Date Lastt Modified: 23/02/2016
     */
    public static function findPhone($id)
    {
        $model = Phone::find()
                ->where(['personid' => $id])
                ->one();
        if ($model) 
            return $model;
        return false;
    }
}
