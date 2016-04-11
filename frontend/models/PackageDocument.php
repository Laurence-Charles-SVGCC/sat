<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "package_document".
 *
 * @property integer $packagedocumentid
 * @property integer $packageid
 * @property integer $uploadedby
 * @property string $description
 * @property string $location
 * @property string $dateuploaded
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Package $package
 * @property Person $uploadedby0
 */
class PackageDocument extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'package_document';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['packageid', 'uploadedby', 'description', 'location', 'dateuploaded'], 'required'],
            [['packageid', 'uploadedby', 'isactive', 'isdeleted'], 'integer'],
            [['description', 'location'], 'string'],
            [['dateuploaded'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'packagedocumentid' => 'Packagedocumentid',
            'packageid' => 'Packageid',
            'uploadedby' => 'Uploadedby',
            'description' => 'Description',
            'location' => 'Location',
            'dateuploaded' => 'Dateuploaded',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackage()
    {
        return $this->hasOne(Package::className(), ['packageid' => 'packageid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUploadedby0()
    {
        return $this->hasOne(Person::className(), ['personid' => 'uploadedby']);
    }
    
    
    /**
     * Returns an array of documents
     * 
     * @param type $packageid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 11/04/2016
     * Date Last Modified: 11/04/2016
     */
    public static function getDocuments($packageid)
    {
        $documents = PackageDocument::find()
                ->where(['packageid' => $packageid, 'isactive' => 1, 'isdeleted' => 0])
                ->all();
        if (count($documents) > 0)
            return $documents;
        return false;
    }
    
}
