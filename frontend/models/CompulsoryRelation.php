<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "compulsory_relation".
 *
 * @property integer $compulsoryrelationid
 * @property integer $relationtypeid
 * @property integer $personid
 * @property string $relationdetail
 * @property string $title
 * @property string $firstname
 * @property string $lastname
 * @property string $occupation
 * @property string $homephone
 * @property string $cellphone
 * @property string $workphone
 * @property integer $receivemail
 * @property string $email
 * @property string $address
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 * @property RelationType $relationtype
 */
class CompulsoryRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'compulsory_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['relationtypeid', 'personid', 'title', 'firstname', 'lastname', 'relationdetail'], 'required'],
            [['relationtypeid', 'personid', 'receivemail', 'isactive', 'isdeleted'], 'integer'],
            [['address'], 'string'],
            [['relationdetail', 'firstname', 'lastname', 'occupation', 'email'], 'string', 'max' => 45],
            [['title'], 'string', 'max' => 3],
            [['homephone', 'cellphone', 'workphone'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'compulsoryrelationid' => 'Compulsoryrelationid',
            'relationtypeid' => 'Relationtypeid',
            'personid' => 'Personid',
            'relationdetail' => 'Relationdetail',
            'title' => 'Title',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'occupation' => 'Occupation',
            'homephone' => 'Homephone',
            'cellphone' => 'Cellphone',
            'workphone' => 'Workphone',
            'receivemail' => 'Receivemail',
            'email' => 'Email',
            'address' => 'Address',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
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
     * @return \yii\db\ActiveQuery
     */
    public function getRelationtype()
    {
        return $this->hasOne(RelationType::className(), ['relationtypeid' => 'relationtypeid']);
    }
    
    /**
     * Determines if a particular relation exists
     * 
     * @param type $id
     * @param type $relationType
     * @return boolean
     * 
     * Date Created: 11/11/2015
     * Date Last Modified: 11/11/2015
     */
    public static function relationExists($id, $relationType)
    {
        $model = CompulsoryRelation::find()
                ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0, 'relationtypeid'=>$relationType])
                ->one();
        if ($model !== null) {
            return true;
        } 
        return false;
    }
    
    
    /**
     * Returns all optional relation records
     * 
     * @param type $id          Applicant's personid
     * @return type             CompulsoryRelation
     * 
     * Author: Laurence Charles
     * Date Created: 11/11/2015
     * Date Last Modified: 11/11/2015
     */
    public static function getAllRelations($id)
    {
        $relations = CompulsoryRelation::find()
                ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                ->all();
        return $relations;
    }
    
    
    /**
     * Aims to retrieve a relation of a particular type
     * 
     * @param type $id
     * @param type $relationType
     * @return boolean
     * 
     * Date Created: 11/11/2015
     * Date Last Modified: 11/11/2015
     */
    public static function getRelationRecord($id, $relationType)
    {
        $model = CompulsoryRelation::find()
                ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0, 'relationtypeid'=>$relationType])
                ->one();
        if ($model != NULL) 
        {
            return $model;
        } 
        return false;
    }
    
    
    /**
     * Creates backup of a collection of CompulsoryRelation records
     * 
     * @param type $relations
     * @return array[Realtion]
     * 
     * Author: Laurence Charles
     * Date Created: 11/11/2015
     * Date Last Modified: 11/11/2015
     */
    public static function backUp($relations)
    {
        $saved = array();
         
        foreach ($relations as $relation)
        {
            $temp = NULL;
            $temp = new Relation();
            $temp->personid = $relation->personid;
            $temp->relationtypeid = $relation->relationtypeid;
            $temp->relationdetail = $relation->relationdetail;
            $temp->title = $relation->title;
            $temp->firstname = $relation->firstname;
            $temp->lastname = $relation->lastname;
            $temp->occupation = $relation->occupation;
            $temp->homephone = $relation->homephone;
            $temp->cellphone = $relation->cellphone;
            $temp->workphone = $relation->workphone;
            $temp->receivemail = $relation->receivemail;
            $temp->email = $relation->email;
            $temp->address = $relation->address;
            array_push($saved, $temp);      
        }
        return $saved;
    }

      
    /**
     * Saves the backed up CompulsoryRelation to the databases
     * 
     * @param type $relations
     * 
     * Date Created: 11/11/2015
     * Date Last Modified: 11/11/2015
     */
    public static function restore($relations)
    {
        foreach ($relations as $relation)
        {
            $relation->save();     
        }
    }
    
    
    /**
     * Checks if relation has at least one phone number
     * 
     * @param type $relation
     * @return boolean
     * 
     * Date Created: 11/11/2015
     * Date Last Modified: 11/11/2015
     */
    public function phoneValid(){
        if (    (strcmp($this->homephone,"") == 0 || strcmp($this->homephone,"blank") == 0 || is_null($this->homephone) == true)
                && (strcmp($this->cellphone,"") == 0  || strcmp($this->cellphone,"blank") == 0 || is_null($this->cellphone) == true)
                && (strcmp($this->workphone,"") == 0  || strcmp($this->workphone,"blank") == 0 || is_null($this->workphone) == true)
            ){
            return false;
        }
        return true;
    }
    
    
    /**
     * Determines if CompulsoryRelation record is blank
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 11/11/2015
     * Date Last Modified: 11/11/2015
     */
    public function isValid()
    {
        $phones = $this->phoneValid();
        
        if ( 
            ($this->title  == NULL || strcmp($this->title ,"Mr") == 0)
            && ($this->relationdetail == NULL || strcmp($this->relationdetail,"mother") == 0 )
            && ($this->firstname == NULL || strcmp($this->firstname,"") == 0 || strcmp($this->firstname,"blank") == 0) 
            && ($this->lastname == NULL || strcmp($this->lastname,"") == 0 || strcmp($this->lastname,"blank") == 0) 
            && ($this->address == NULL || strcmp($this->address,"") == 0 || strcmp($this->address,"blank") == 0)
            && $phones == false   
           )
                return false;
        return true;    
    }
    
    /**
     * Returns true if relation has an email
     * 
     * @param type $id
     * @param type $relationName
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 12/11/2015
     * Date Last Modified: 12/11/2015
     */
    public static function hasEmail($id, $relationName)
    {
        $relationTypeRecord = RelationType::find()
                ->where(['name' => $relationName])
                ->one();
        if ($relationTypeRecord)
        {
            $relation = CompulsoryRelation::find()
                ->where(['personid' => $id, 'relationtypeid' => $relationTypeRecord->relationtypeid])
                ->one();
            if ($relation)
            {
                if(strcmp($relation->email,"") != 0 && is_null($relation->email) == false){
                    return true;
                }
            }
        }
        return false;
    }
    
    
    /**
     * Returns true if all copmulsory relations have been entered
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 12/11/2015
     * Date Last Modified: 12/11/2015
     */
    public static function checkCompulsoryRelations($id)
    {
        $relations = CompulsoryRelation::find()
                ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                ->all();
        if (count($relations) == 2)
            return true;
        return false;
    }
    
    
     /**
     * Creates a blank CompulsoryRelation model
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
        $temp = new CompulsoryRelation();
        $temp->personid = $personid;
        $temp->relationtypeid = $type;
        $temp->title = "Mr.";
        $temp->firstname = "FirstName";
        $temp->lastname = "LastName";
//        $temp->address = "";
        $temp->relationdetail = "RelationDetail";
        return $temp;
    }

    
    
}
