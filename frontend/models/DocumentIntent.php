<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "document_intent".
 *
 * @property string $documentintentid
 * @property string $description
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property DocumentSubmitted[] $documentSubmitteds
 */
class DocumentIntent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document_intent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['description'], 'string'],
            [['isactive', 'isdeleted'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'documentintentid' => 'Documentintentid',
            'description' => 'Description',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentSubmitteds()
    {
        return $this->hasMany(DocumentSubmitted::className(), ['documentintentid' => 'documentintentid']);
    }
}
