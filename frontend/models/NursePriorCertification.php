<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "nurse_prior_certification".
 *
 * @property integer $nursepriorcertificationid
 * @property integer $personid
 * @property string $certification
 * @property string $datesoftraining
 * @property string $lengthoftraining
 * @property string $institutionname
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 */
class NursePriorCertification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nurse_prior_certification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'certification', 'datesoftraining', 'lengthoftraining', 'institutionname'], 'required'],
            [['personid', 'isactive', 'isdeleted'], 'integer'],
            [['certification', 'datesoftraining', 'lengthoftraining', 'institutionname'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nursepriorcertificationid' => 'Nursepriorcertificationid',
            'personid' => 'Personid',
            'certification' => 'Certification',
            'datesoftraining' => 'Datesoftraining',
            'lengthoftraining' => 'Lengthoftraining',
            'institutionname' => 'Institutionname',
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
     * Returns a count of an applicant nursing certifications
     * 
     * @param type $personid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 05/02/2016
     * Date Last Modified: 05/02/2016
     */
    public static function getCertificationCount($personid)
    {
        $certifications = NursePriorCertification::find()
                ->where(['personid'=> $personid, 'isactive' => 1, 'isdeleted' => 0])
                ->all();
        
        return count($certifications);
    }
    
    /**
     * Retrieves all the NursePriorCertification records associated with a personid
     * 
     * @param type $id
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 05/02/2016
     * Date Last Modified: 05/02/2016
     */
    public static function getCertifications($id)
    {
        $models = NursePriorCertification::find()
                 ->where(['personid'=> $id, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();
        return $models;
    }
    
    
    /**
     * Creates backup of a collection of NursePriorCertification records
     * 
     * @param type $certificates
     * @return array
     * 
     * Author: Laurence Charles
     * Date Created 05/02/2016
     * Date Last Modified: 05/02/2016
     */
    public static function backUp($certificates)
    {
        $saved = array();
         
        foreach ($certificates as $certificate)
        {
            $temp = NULL;
            $temp = new NursePriorCertification();
            $temp->personid = $certificate->personid;
            $temp->certification = $certificate->certification;
            $temp->datesoftraining = $certificate->datesoftraining;
            $temp->lengthoftraining = $certificate->lengthoftraining;
            $temp->institutionname = $certificate->institutionname;
            array_push($saved, $temp);      
        }
        return $saved;
    }
    
    
    /**
     * Saves the backed up NursePriorCertification to the databases
     * 
     * @param type $experiences
     * 
     * Author: Laurence Charles
     * Date Created 05/02/2016
     * Date Last Modified: 05/02/2016
     */
    public static function restore($certificates)
    {
        foreach ($certificates as $certificate)
        {
            $certificate->save();     
        }
    }
    
    
    /**
     * Determines if record is blank
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created 05/02/2016
     * Date Last Modified: 05/02/2016
     */
    public function isValid()
    {
        if (
            ($this->certification == NULL || strcmp($this->certification,"")==0 || strcmp($this->certification,"blank")==0) 
            && ($this->datesoftraining == NULL || strcmp($this->datesoftraining,"")==0 || strcmp($this->datesoftraining,"blank")==0)
            && ($this->lengthoftraining == NULL || strcmp($this->lengthoftraining,"")==0 || strcmp($this->lengthoftraining,"blank")==0) 
            && ($this->institutionname == NULL || strcmp($this->institutionname,"")==0 || strcmp($this->institutionname,"blank")==0) 
           )
                return false;
        return true;    
    }
    
    
    /**
     * Returns true if nursing midwifery applicant has certification records
     * 
     * @param type $id
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created 06/02/2016
     * Date Last Modified: 06/02/2016
     */
    public static function hasNursePriorCertification($id)
    {
        $models = NursePriorCertification::find()
                 ->where(['personid'=> $id, 'isactive' => 1, 'isdeleted' => 0])
                 ->all();
        if (count($models) > 0)
            return true;
        return false;
    }
    
}
