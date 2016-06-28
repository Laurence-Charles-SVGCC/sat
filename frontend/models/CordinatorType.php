<?php

namespace frontend\models;

use Yii;
use frontend\models\Cordinator;

/**
 * This is the model class for table "cordinator_type".
 *
 * @property string $cordinatortypeid
 * @property string $name
 * @property string $description
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Cordinator[] $cordinators
 */
class CordinatorType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cordinator_type';
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
            'cordinatortypeid' => 'Cordinatortypeid',
            'name' => 'Name',
            'description' => 'Description',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCordinators()
    {
        return $this->hasMany(Cordinator::className(), ['cordinatortypeid' => 'cordinatortypeid']);
    }
    
    
    /**
     * Returns and array of cordinator types associated with employee
     * 
     * @param type $employee_personid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 28/06/2016
     * Date Last Modified: 28/06/2016
     */
    public static function getCordinatorTypes($employee_personid)
    {
        $cordinator_type_names = array();
        
        $active_cordinator_records = Cordinator::find()
                ->where(['personid' => $employee_personid, 'isactive' => 1, 'isdeleted' => 0,  'isserving' => 1 ])
                ->all();
        
        if($active_cordinator_records)
        {
            $cordinator_type_ids = array();
            foreach ($active_cordinator_records as $cordinator)
            {
                $cordinator_type_ids[] = $cordinator->cordinatortypeid;
            }

            $unique_ids = array();
            foreach ($cordinator_type_ids as $id)
            {
                $cordinator_type = CordinatorType::find()
                        ->where(['cordinatortypeid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                if(in_array($cordinator_type, $unique_ids) == false)
                {
                    $cordinator_type_names[] = $cordinator_type->name;
                }
            }
        }
 
        return $cordinator_type_names;
    }
    
    
    
    
    
    
        
}
