<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "application_capesubject".
 *
 * @property string $applicationcapesubjectid
 * @property string $applicationid
 * @property string $capesubjectid
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Application $application
 * @property CapeSubject $capesubject
 */
class ApplicationCapesubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application_capesubject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applicationid', 'capesubjectid'], 'required'],
            [['applicationid', 'capesubjectid'], 'integer'],
            [['isactive', 'isdeleted'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applicationcapesubjectid' => 'Applicationcapesubjectid',
            'applicationid' => 'Applicationid',
            'capesubjectid' => 'Capesubjectid',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplication()
    {
        return $this->hasOne(Application::className(), ['applicationid' => 'applicationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapesubject()
    {
        return $this->hasOne(CapeSubject::className(), ['capesubjectid' => 'capesubjectid']);
    }
}
