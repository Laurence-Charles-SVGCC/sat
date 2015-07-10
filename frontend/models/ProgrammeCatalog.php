<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "programme_catalog".
 *
 * @property string $programmecatalogid
 * @property string $examinationbodyid
 * @property string $qualificationtypeid
 * @property string $departmentid
 * @property string $creationdate
 * @property string $speciialisation
 * @property integer $duration
 * @property string $name
 * @property boolean $isactive
 * @property boolean $isdeleted
 */
class ProgrammeCatalog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'programme_catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['examinationbodyid', 'qualificationtypeid', 'departmentid', 'creationdate', 'speciialisation', 'duration', 'name'], 'required'],
            [['examinationbodyid', 'qualificationtypeid', 'departmentid', 'duration'], 'integer'],
            [['creationdate'], 'safe'],
            [['isactive', 'isdeleted'], 'boolean'],
            [['speciialisation', 'name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'programmecatalogid' => 'Programmecatalogid',
            'examinationbodyid' => 'Examinationbodyid',
            'qualificationtypeid' => 'Qualificationtypeid',
            'departmentid' => 'Departmentid',
            'creationdate' => 'Creationdate',
            'speciialisation' => 'Speciialisation',
            'duration' => 'Duration',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
}
