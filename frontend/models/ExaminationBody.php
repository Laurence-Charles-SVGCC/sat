<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "examination_body".
 *
 * @property string $examinationbodyid
 * @property string $levelid
 * @property string $name
 * @property string $alias
 * @property string $abbreviation
 * @property integer $isactive
 * @property integer $isdeleted
 */
class ExaminationBody extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'examination_body';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['levelid', 'name', 'alias', 'abbreviation'], 'required'],
            [['levelid'], 'integer'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['name', 'alias'], 'string', 'max' => 45],
            [['abbreviation'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'examinationbodyid' => 'Examinationbodyid',
            'levelid' => 'Levelid',
            'name' => 'Name',
            'alias' => 'Alias',
            'abbreviation' => 'Abbreviation',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
    
    
    /**
     * Returns array of currently active examination bodies
     * 
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 04/01/2016
     * Date Last Modified: 03/11/2016
     */
    public static function processExaminationBodies()
    {
        $records = ExaminationBody::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();
        
        if(count($records)==0)
        {
            return false;
        }
        else        //if examination body records found
        {   
            $keys = array();        
            $values = array();        
            $combined = array();
            
            array_push($keys, '');     //set to '1000' to ensure default value passes validation
            array_push($values, 'Select Exam');
            
            foreach($records as $record)
            {
                $k = strval($record->examinationbodyid);
                array_push($keys, $k);
                $v = strval($record->abbreviation);
                array_push($values, $v);
            }
            $combined = array_combine($keys, $values);
            return $combined; 
        }
    }
    
    
}
