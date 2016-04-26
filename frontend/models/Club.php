<?php

namespace frontend\models;

use Yii;
use frontend\models\ClubMember;

/**
 * This is the model class for table "club".
 *
 * @property integer $clubid
 * @property string $name
 * @property string $description
 * @property string $motto
 * @property string $yearfounded
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property ClubDivision[] $clubDivisions
 * @property Division[] $divisions
 * @property ClubMember[] $clubMembers
 * @property ClubMemberHistory[] $clubMemberHistories
 */
class Club extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'club';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'yearfounded'], 'required'],
            [['description', 'motto'], 'string'],
            [['yearfounded'], 'safe'],
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
            'clubid' => 'Clubid',
            'name' => 'Name',
            'description' => 'Description',
            'motto' => 'Motto',
            'yearfounded' => 'Yearfounded',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClubDivisions()
    {
        return $this->hasMany(ClubDivision::className(), ['clubid' => 'clubid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDivisions()
    {
        return $this->hasMany(Division::className(), ['divisionid' => 'divisionid'])->viaTable('club_division', ['clubid' => 'clubid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClubMembers()
    {
        return $this->hasMany(ClubMember::className(), ['clubid' => 'clubid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClubMemberHistories()
    {
        return $this->hasMany(ClubMemberHistory::className(), ['clubid' => 'clubid']);
    }
    
    
    /**
     * Returns true if club has ever had any members
     * 
     * @param type $awardid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 26/04/2016
     * Date Last Modified: 26/04/2016
     */
    public static function hasMembers($clubid)
    {
        $assignment = ClubMember::find()
                    ->where(['clubid' => $clubid])
                    ->one();
        if($assignment)
            return true;
        return false;
    }
    
    
    /**
     * Returns the 'clubdivision' records for a particular club
     * 
     * @param type $clubid
     * @return boolean|string
     * 
     * Author: Laurence Charles
     * Date Created: 26/04/2016
     * Date Last Modified: 26/04/2016
     */
    public static function getDivision($clubid)
    {
        $club_division = ClubDivision::find()
                    ->where(['clubid' => $clubid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        if($club_division)
        {
            if ($club_division->divisionid == 1)
                return "Cross Divisional";
            if ($club_division->divisionid == 4)
                return "DASGS";
            if ($club_division->divisionid == 5)
                return "DTVE";
            if ($club_division->divisionid == 6)
                return "DTE";
            if ($club_division->divisionid == 7)
                return "DNE";
        }
        return false;
    }
    
    
    
}
