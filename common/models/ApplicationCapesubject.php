<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "application_capesubject".
 *
 * @property int $applicationcapesubjectid
 * @property int $applicationid
 * @property int $capesubjectid
 * @property int $isactive
 * @property int $isdeleted
 *
 * @property Application $application
 * @property CapeSubject $capesubject
 */
class ApplicationCapesubject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application_capesubject';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicationid', 'capesubjectid'], 'required'],
            [['applicationid', 'capesubjectid', 'isactive', 'isdeleted'], 'integer'],
            [['applicationid'], 'exist', 'skipOnError' => true, 'targetClass' => Application::class, 'targetAttribute' => ['applicationid' => 'applicationid']],
            [['capesubjectid'], 'exist', 'skipOnError' => true, 'targetClass' => CapeSubject::class, 'targetAttribute' => ['capesubjectid' => 'capesubjectid']],
        ];
    }

    /**
     * {@inheritdoc}
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
        return $this->hasOne(Application::class, ['applicationid' => 'applicationid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapesubject()
    {
        return $this->hasOne(CapeSubject::class, ['capesubjectid' => 'capesubjectid']);
    }
}
