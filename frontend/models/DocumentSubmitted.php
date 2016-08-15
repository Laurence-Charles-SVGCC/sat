<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "document_submitted".
 *
 * @property string $documentsubmittedid
 * @property string $documentintentid
 * @property string $documenttypeid
 * @property string $personid
 * @property string $recepientid
 * @property string $documentpath
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property DocumentIntent $documentintent
 * @property DocumentType $documenttype
 * @property Person $person
 * @property Person $recepient
 */
class DocumentSubmitted extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document_submitted';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documentintentid', 'documenttypeid', 'personid', 'recepientid'], 'required'],
            [['documentintentid', 'documenttypeid', 'personid', 'recepientid', 'isactive', 'isdeleted'], 'integer'],
            [['documentpath'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'documentsubmittedid' => 'Documentsubmittedid',
            'documentintentid' => 'Documentintentid',
            'documenttypeid' => 'Documenttypeid',
            'personid' => 'Personid',
            'recepientid' => 'Recepientid',
            'documentpath' => 'Documentpath',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentintent()
    {
        return $this->hasOne(DocumentIntent::className(), ['documentintentid' => 'documentintentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumenttype()
    {
        return $this->hasOne(DocumentType::className(), ['documenttypeid' => 'documenttypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['personid' => 'personid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecepient()
    {
        return $this->hasOne(Person::className(), ['personid' => 'recepientid']);
    }
}
