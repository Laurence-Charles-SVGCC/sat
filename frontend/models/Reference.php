<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "reference".
 *
 * @property integer $referenceid
 * @property integer $personid
 * @property string $title
 * @property string $firstname
 * @property string $lastname
 * @property string $address
 * @property string $occupation
 * @property string $contactnumber
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 */
class Reference extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reference';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'title', 'firstname', 'lastname', 'contactnumber', 'occupation', 'address'], 'required'],
            [['personid', 'isactive', 'isdeleted'], 'integer'],
            [['title'], 'string', 'max' => 3],
            [['firstname', 'lastname', 'address', 'occupation'], 'string', 'max' => 45],
            [['contactnumber'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'referenceid' => 'Referenceid',
            'personid' => 'Personid',
            'title' => 'Title',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'address' => 'Address',
            'occupation' => 'Occupation',
            'contactnumber' => 'Contactnumber',
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
     * Returns instance of model 
     * 
     * @param type $id          An applicant's personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 30/09/2015
     * Last Date Modified: 31/01/2016
     */
    public static function getReferences($id)
    {
        $references = Reference::find()
                 ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();
        if (count($references) > 0)
            return $references;
        return false;
    }
    
    
    /**
     * Determines if all manadatory field of the model have data.
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 30/09/2015
     * Date Last Modified: 30/0/2015
     */
    public static function checkReference($id){
        $model= Reference::find()
                ->where(['personid' => $id])
                ->one();
        if ($model){
            if($model->title != NULL && strcmp($model->title,"") != 0
               && $model->firstname != NULL  && strcmp($model->firstname,"") != 0 
               && $model->lastname != NULL && strcmp($model->lastname,"") != 0  
               && $model->address != NULL && strcmp($model->address,"") != 0
               && $model->occupation != NULL && strcmp($model->occupation,"") != 0
               && $model->contactnumber != NULL && strcmp($model->contactnumber,"") != 0)
            {
                return true;
            }
        }    
        return false;
    }
    
    
    /**
     * Determines if all manadatory field of both references have data.
     * 
     * @param type $id
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 14/10/2015
     * Date Last Modified: 14/10/2015
     */
    public static function checkReferences($id){
        
        $reference1 = false;
        $reference2 = false;
        
        $references= Reference::find()
                ->where(['personid' => $id])
                ->all();
        
        if (count($references)>0){           
            //Reference 1 check
            if($references[0]->title != NULL && strcmp($references[0]->title,"") != 0
               && $references[0]->firstname != NULL  && strcmp($references[0]->firstname,"") != 0 
               && $references[0]->lastname != NULL && strcmp($references[0]->lastname,"") != 0  
               && $references[0]->address != NULL && strcmp($references[0]->address,"") != 0
               && $references[0]->occupation != NULL && strcmp($references[0]->occupation,"") != 0
               && $references[0]->contactnumber != NULL && strcmp($references[0]->contactnumber,"") != 0)
            {
                $reference1 = true;
            }
            
            //Reference 2 check
            if($references[1]->title != NULL && strcmp($references[1]->title,"") != 0
               && $references[1]->firstname != NULL  && strcmp($references[1]->firstname,"") != 0 
               && $references[1]->lastname != NULL && strcmp($references[1]->lastname,"") != 0  
               && $references[1]->address != NULL && strcmp($references[1]->address,"") != 0
               && $references[1]->occupation != NULL && strcmp($references[1]->occupation,"") != 0
               && $references[1]->contactnumber != NULL && strcmp($references[1]->contactnumber,"") != 0)
            {
                $reference2 = true;
            }           
        }   
        
        if ($reference1 == true  && $reference2 == true)
            return true;
        
        return false;
    }
    
    
    public static function getReferencesNameAndQualification($personid)
    {
        $refs = "";
        $references = Reference::find()
                ->where(['personid' => $personid, 'isdeleted' => 0])
                ->all();
        if ($references)
        {
            foreach($references as $key=>$reference)
            {
                $name = $reference->title . ". " . $reference->firstname . " " .  $reference->lastname;
                $occupation = $reference->occupation;
                if ($key != count($references) - 1)
                {
                    $refs.= $name . " - " . $occupation . ",";
                }
                else
                {
                    $refs.= $name . " - " . $occupation;
                }
            }
        }
        return $refs;
    }
}
