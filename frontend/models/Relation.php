<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "relation".
 *
 * @property string $relationid
 * @property string $relationtypeid
 * @property string $personid
 * @property string $title
 * @property string $firstname
 * @property string $lastname
 * @property string $occupation
 * @property string $homephone
 * @property string $cellphone
 * @property string $workphone
 * @property integer $receivemail
 * @property string $email
 * @property string $country
 * @property string $constituency
 * @property string $town
 * @property string $addressline
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property RelationType $relationtype
 * @property Person $person
 */
class Relation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'relationtypeid', 'title', 'firstname', 'lastname'], 'required'],
            [['relationtypeid', 'personid', 'receivemail', 'isactive', 'isdeleted'], 'integer'],
            [['title'], 'string', 'max' => 3],
            [['address'], 'string'],
            [['firstname', 'lastname', 'occupation', 'email', 'country', 'constituency', 'town', 'addressline'], 'string', 'max' => 45],
            [['homephone', 'cellphone', 'workphone'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'relationid' => 'Relationid',
            'relationtypeid' => 'Relationtypeid',
            'personid' => 'Personid',
            'title' => 'Title',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'occupation' => 'Occupation',
            'homephone' => 'Homephone',
            'cellphone' => 'Cellphone',
            'workphone' => 'Workphone',
            'address' => "Address",
            'receivemail' => 'Receivemail',
            'email' => 'Email',
            'country' => 'Country',
            'constituency' => 'Constituency',
            'town' => 'Town',
            'addressline' => 'Addressline',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationtype()
    {
        return $this->hasOne(RelationType::className(), ['relationtypeid' => 'relationtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['personid' => 'personid']);
    }
    
    
    /**
     * Aims to retrieve a relation of a particular type
     * 
     * @param type $id
     * @param type $relationType
     * @return boolean
     * 
     * Date Created: 22/12/2015
     * Date Last Modified: 22/12/2015
     */
    public static function getRelationRecord($id, $relationType)
    {
        $model = Relation::find()
                ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0, 'relationtypeid'=>$relationType])
                ->one();
        if ($model != NULL && strcmp($model->title,"") !=0) 
        {
            return $model;
        } 
        return false;
    }
    
    
    /**
     * Checks if town field is populated
     * For conditional appearance of town
     * 
     * @param type $id
     * @param type $type
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 30/12/2015
     * Date Last Modified: 30/12/2015
     */
    public function checkTown()
    {
        if(strcmp($this->town,"")!=0  && is_null($this->town) == false)
            return true;
        return false;
    }
    

    /**
     * Checks if town field is populated
     * For conditional appearance of town
     * 
     * @param type $id
     * @param type $type
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 30/12/2015
     * Date Last Modified: 30/12/2015
     */
    public function checkAddressline()
    {
        if(strcmp($this->addressline,"")!=0  && is_null($this->addressline) == false)
            return true;
        return false;
    }
    
    
    /**
     * Creates a blank Relation model
     * 
     * @param type $personid
     * @param type $type
     * @return \frontend\models\Relation
     * 
     * Author: Laurence Charles
     * Date Created: 04/01/2015
     * Date Last Modified: 04/01/2015
     */
    public static function getDumyRelation($personid, $type)
    {
        $temp = new Relation();
        $temp->personid = $personid;
        $temp->relationtypeid = $type;
        $temp->title = "Mr.";
        $temp->firstname = "FirstName";
        $temp->lastname = "LastName";
//        $temp->address = "";
        return $temp;
    }
}
