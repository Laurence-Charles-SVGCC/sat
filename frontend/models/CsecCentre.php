<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "csec_centre".
 *
 * @property string $cseccentreid
 * @property string $name
 * @property string $cseccode
 * @property boolean $isactive
 * @property boolean $isdeleted
 */
class CsecCentre extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'csec_centre';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'cseccode'], 'required'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['name', 'cseccode'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cseccentreid' => 'Cseccentreid',
            'name' => 'Centre Name',
            'cseccode' => 'CSEC Centre Code',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
}
