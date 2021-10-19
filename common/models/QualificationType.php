<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "qualification_type".
 *
 * @property int $qualificationtypeid
 * @property int $levelid
 * @property string $name
 * @property string $abbreviation
 * @property string $description
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property ProgrammeCatalog[] $programmeCatalogs
 * @property Level $level
 */
class QualificationType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'qualification_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['levelid'], 'required'],
            [['levelid', 'isactive', 'isdeleted'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['abbreviation'], 'string', 'max' => 45],
            [['levelid'], 'exist', 'skipOnError' => true, 'targetClass' => Level::class, 'targetAttribute' => ['levelid' => 'levelid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'qualificationtypeid' => 'Qualificationtypeid',
            'levelid' => 'Levelid',
            'name' => 'Name',
            'abbreviation' => 'Abbreviation',
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
        return $this->hasMany(ProgrammeCatalog::class, ['qualificationtypeid' => 'qualificationtypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(Level::class, ['levelid' => 'levelid']);
    }
}
