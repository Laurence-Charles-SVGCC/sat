<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "csec_centre".
 *
 * @property string $cseccentreid
 * @property string $name
 * @property string $cseccode
 * @property integer $isactive
 * @property integer $isdeleted
 */
class CsecCentre extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'csec_centre';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'cseccode'], 'required'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['name', 'cseccode'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cseccentreid' => 'Cseccentreid',
            'name' => 'Centre Name',
            'cseccode' => 'CSEC Centre Code',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
    
    
    /**
     * Returns array cseccentre data
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 04/01/2016
     * Date Last Modified: 04/01/2016
     */
    public static function processCentres()
    {
        $records = CsecCentre::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();
        
        $keys = array();
        array_push($keys, '');
        $values = array();
        array_push($values, 'Select...');
        $combined = array();
        
        if(count($records)==0)
        {
            return false;
        }
        else    //if centre records found
        {   
            foreach($records as $record)
            {
                $k = strval($record->cseccentreid);
                array_push($keys, $k);
                $v = strval($record->name);
                array_push($values, $v);
            }
            $combined = array_combine($keys, $values);
            return $combined;
        }
    }
    
    
 
    /**
     * Gets the CSEC Centres relevant to active application periods
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 18/02/2016
     * Date Last Modified: 18/02/2016
     */
    public static function getCurrentCentres()
    {
        $centres = CsecCentre::find()
                    ->innerJoin('csec_qualification', '`csec_centre`.`cseccentreid` = `csec_qualification`.`cseccentreid`')
                    ->innerJoin('application', '`csec_qualification`.`personid` = `application`.`personid`')
                    ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                    ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                    ->innerJoin('academic_year', '`academic_year`.`academicyearid` = `application_period`.`academicyearid`')
                    ->where(['application_period.isactive' => 1, 'application_period.applicationperiodstatusid' => 5,
                                'csec_centre.isdeleted' => 0, 'csec_centre.isactive' => 1,
                                'application.isdeleted' => 0, 'application.applicationstatusid' => [2,3,4,5,6,7,8,9],
                                'csec_qualification.isdeleted' => 0,
                                'academic_offering.isdeleted' => 0
                            ])
                    ->all();
        if (count($centres) > 0)
            return $centres;
        return false;
    }
    
    
    
}
