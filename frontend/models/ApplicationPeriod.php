<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "application_period".
 *
 * @property string $applicationperiodid
 * @property string $divisionid
 * @property string $personid
 * @property string $academicyearid
 * @property string $name
 * @property string $onsitestartdate
 * @property string $onsiteenddate
 * @property string $offsitestartdate
 * @property string $offsiteenddate
 * @property boolean $isactive
 * @property boolean $isdeleted
 */
class ApplicationPeriod extends \yii\db\ActiveRecord
{    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application_period';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['divisionid', 'personid', 'academicyearid', 'name', 'onsitestartdate', 'offsitestartdate'], 'required'],
            [['divisionid', 'personid', 'academicyearid'], 'integer'],
            [['onsitestartdate', 'onsiteenddate', 'offsitestartdate', 'offsiteenddate'], 'safe'],
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
            'divisionid' => 'Division',
            'academicyearid' => 'Academic Year',
            'name' => 'Application Period Name',
            'onsitestartdate' => 'On-Campus Start Date',
            'onsiteenddate' => 'On-Campus End Date',
            'offsitestartdate' => 'Off-Campus Start Date',
            'offsiteenddate' => 'Off-Campus End Date',
        ];
    }
}
