<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "qualification_type".
 *
 * @property string $qualificationtypeid
 * @property string $levelid
 * @property string $name
 * @property string $description
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property ProgrammeCatalog[] $programmeCatalogs
 * @property Level $level
 */
class QualificationType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qualification_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['levelid', 'name', 'description'], 'required'],
            [['levelid'], 'integer'],
            [['description'], 'string'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'qualificationtypeid' => 'Qualificationtypeid',
            'levelid' => 'Levelid',
            'name' => 'Name',
            'description' => 'Description',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgrammeCatalogs()
    {
        return $this->hasMany(ProgrammeCatalog::className(), ['qualificationtypeid' => 'qualificationtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(Level::className(), ['levelid' => 'levelid']);
    }
    
    /**
     * Returns an abbreviation of qualificationtype
     * 
     * @param type $id
     * @return boolean|string
     * 
     * Author: Laurence Charles
     * Date Created: 08/12/2015
     * Date Last Modified: 08/12/2015
     */
    public static function getQualificationAbbreviation($id)
    {
        $qualification = QualificationType::find()
                    ->where(['qualificationtypeid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
        if ($qualification)
        {
            $name = $qualification->abbreviation . ".  ";
            return $name;
        }
        return false;
    }
}
