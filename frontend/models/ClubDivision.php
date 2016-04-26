<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "club_division".
 *
 * @property integer $clubid
 * @property integer $divisionid
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Club $club
 * @property Division $division
 */
class ClubDivision extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'club_division';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clubid', 'divisionid'], 'required'],
            [['clubid', 'divisionid', 'isactive', 'isdeleted'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'clubid' => 'Clubid',
            'divisionid' => 'Divisionid',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClub()
    {
        return $this->hasOne(Club::className(), ['clubid' => 'clubid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDivision()
    {
        return $this->hasOne(Division::className(), ['divisionid' => 'divisionid']);
    }
}
