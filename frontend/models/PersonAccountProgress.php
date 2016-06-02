<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "person_account_progress".
 *
 * @property integer $personaccountprogressid
 * @property integer $personid
 * @property integer $accountprogressid
 * @property integer $isactive
 * @property integer $isdeleted
 */
class PersonAccountProgress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person_account_progress';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'accountprogressid'], 'required'],
            [['personid', 'accountprogressid', 'isactive', 'isdeleted'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'personaccountprogressid' => 'Personaccountprogressid',
            'personid' => 'Personid',
            'accountprogressid' => 'Accountprogressid',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
    
    
    /**
     * Returns collection of incomplete accounts
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 24/05/2016
     * Date Last Modified: 24/05/2016
     */
    public static function getIncompleteAccounts()
    {
        $records = PersonAccountProgress::find()
                ->where(['accountprogressid' => [1,2,3,4,5], 'isative' => 1, 'isdeleted' => 0])
                ->all();
        return $records;
    }
}
