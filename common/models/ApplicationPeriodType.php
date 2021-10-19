<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "application_period_type".
 *
 * @property int $applicationperiodtypeid
 * @property string $name
 * @property string $description
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property ApplicationPeriod[] $applicationPeriods
 */
class ApplicationPeriodType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application_period_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['description'], 'string'],
            [['isactive', 'isdeleted'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'applicationperiodtypeid' => 'Applicationperiodtypeid',
            'name' => 'Name',
            'description' => 'Description',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationPeriods()
    {
        return $this->hasMany(ApplicationPeriod::class, ['applicationperiodtypeid' => 'applicationperiodtypeid']);
    }
}
