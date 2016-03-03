<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "post_secondary_qualification".
 *
 * @property integer $postsecondaryqualificationid
 * @property integer $personid
 * @property string $name
 * @property string $awardinginstitution
 * @property string $yearawarded
 * @property integer $isverified
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer isqueried
 *
 * @property Person $person
 */
class PostSecondaryQualification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_secondary_qualification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'name', 'awardinginstitution', 'yearawarded'], 'required'],
            [['personid', 'isverified', 'isactive', 'isdeleted', 'isqueried'], 'integer'],
            [['name', 'awardinginstitution'], 'string'],
            [['yearawarded'], 'string', 'max' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'postsecondaryqualificationid' => 'Postsecondaryqualificationid',
            'personid' => 'Personid',
            'name' => 'Name',
            'awardinginstitution' => 'Awardinginstitution',
            'yearawarded' => 'Yearawarded',
            'isverified' => 'Isverified',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'isqueried' => 'Isqueried',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['personid' => 'personid']);
    }
    
    
    /**
     * Returns an array of the qualification
     * 
     * @param type $personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 02/03/2016
     * Date Last Modified: 02/03/2016
     */
    public static function getPostSecondaryQualifications($personid)
    {
        $qualification = PostSecondaryQualification::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        if ($qualification)
            return $qualification;
        return false;
    }
}
