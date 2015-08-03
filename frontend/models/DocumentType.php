<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "document_type".
 *
 * @property string $documenttypeid
 * @property string $name
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property DocumentSubmitted[] $documentSubmitteds
 */
class DocumentType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
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
            'documenttypeid' => 'Documenttypeid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentSubmitteds()
    {
        return $this->hasMany(DocumentSubmitted::className(), ['documenttypeid' => 'documenttypeid']);
    }
}
