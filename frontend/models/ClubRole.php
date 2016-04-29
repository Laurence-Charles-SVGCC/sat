<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "club_role".
 *
 * @property integer $clubroleid
 * @property string $name
 * @property string $description
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property ClubMember[] $clubMembers
 * @property ClubMemberHistory[] $clubMemberHistories
 * @property ClubMemberHistory[] $clubMemberHistories0
 */
class ClubRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'club_role';
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
            'clubroleid' => 'Clubroleid',
            'name' => 'Name',
            'description' => 'Description',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClubMembers()
    {
        return $this->hasMany(ClubMember::className(), ['clubroleid' => 'clubroleid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClubMemberHistories()
    {
        return $this->hasMany(ClubMemberHistory::className(), ['newclubroleid' => 'clubroleid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClubMemberHistories0()
    {
        return $this->hasMany(ClubMemberHistory::className(), ['oldclubroleid' => 'clubroleid']);
    }
}
