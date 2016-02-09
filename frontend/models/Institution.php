<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "institution".
 *
 * @property string $institutionid
 * @property string $levelid
 * @property string $name
 * @property string $formername
 * @property string $country
 * @property string $constituency
 * @property string $town
 * @property string $addressline
 * @property boolean $isactive
 * @property boolean $isdeleted
 * @property string $personid
 *
 * @property Level $level
 * @property Person $person
 * @property PersonInstitution[] $personInstitutions
 */
class Institution extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'institution';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['levelid', 'name'], 'required'],
            [['levelid', 'personid'], 'integer'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['name', 'formername', 'country', 'constituency', 'town', 'addressline'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'institutionid' => 'Institutionid',
            'levelid' => 'Levelid',
            'name' => 'Name',
            'formername' => 'Formername',
            'country' => 'Country',
            'constituency' => 'Constituency',
            'town' => 'Town',
            'addressline' => 'Addressline',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
            'personid' => 'Personid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(Level::className(), ['levelid' => 'levelid']);
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
    public function getPersonInstitutions()
    {
        return $this->hasMany(PersonInstitution::className(), ['institutionid' => 'institutionid']);
    }
    
    
    /**
     * Returns an array of insititutions
     * 
     * @param type $id
     * @param type $index
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 05/01/2016
     * Date Last Modified: 05/01/2016
     */
    public static function initializeSchoolList($levelid)
    {
        $institutions = Institution::find()
                    ->where(['levelid' => $levelid, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
        
        $keys = array();
        array_push($keys, '');
        $values = array();
        array_push($values, 'Select...');
        $combined = array();
        
        if(count($institutions) == 0)
        {
            $combined = array_combine($keys, $values);
            return $combined;   
        }
        else        //if institutions exist
        {
            foreach($institutions as $institution)
            {
                $k = strval($institution->institutionid);
                array_push($keys, $k);
                $v = strval($institution->name);
                array_push($values, $v);
            }  

            $combined = array_combine($keys, $values);
            return $combined;   
        }
    }
}
