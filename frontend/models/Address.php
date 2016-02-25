<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "address".
 *
 * @property string $addressid
 * @property string $personid
 * @property string $addresstypeid
 * @property string $country
 * @property string $constituency
 * @property string $town
 * @property string $addressline
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 * @property AddressType $addresstype
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personid', 'addresstypeid'], 'required'],
            [['personid', 'addresstypeid', 'isactive', 'isdeleted'], 'integer'],
            [['country', 'constituency', 'town'], 'string', 'max' => 45],
            [['addressline'], 'string', 'max' => 300]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'addressid' => 'Addressid',
            'personid' => 'Personid',
            'addresstypeid' => 'Addresstypeid',
            'country' => 'Country',
            'constituency' => 'Constituency',
            'town' => 'Town',
            'addressline' => 'Addressline',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['personid' => 'personid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresstype()
    {
        return $this->hasOne(AddressType::className(), ['addresstypeid' => 'addresstypeid']);
    }
    
    
    /**
     * Returns an address of a particular type
     * 
     * @param type $id
     * @param type $type
     * @return type
     * @throws NotFoundHttpException
     * 
     * Author: Laurence Charles
     * Date Created: 21/12/2015
     * Date Last Modified: 21/12/2015
     */
    public static function findAddress($id, $type)
    {
        $address = Address::find()
                ->where(['personid' => $id, 'addresstypeid'=>$type, 'isactive' => 1 , 'isdeleted' => 0])
                ->one();
        if ($address)
            return $address;
        return false;
    }

    
    /**
     * Checks is applicant town field is populated
     * For conditional appearance of town
     * 
     * @param type $id
     * @param type $type
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 29/12/2015
     * Date Last Modified: 29/12/2015
     */
    public static function checkTown($id, $type){
        $record = self::findAddress($id, $type);
        if ($record){
            if(strcmp($record->town,"")!=0  && is_null($record->town) == false){
                return true;
            }
        }
        return false;
    }
    

    /**
     * Checks is applicant town field is populated
     * For conditional appearance of town
     * 
     * @param type $id
     * @param type $type
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 29/12/2015
     * Date Last Modified: 29/12/2015
     */
    public static function checkAddressline($id, $type){
        $record = self::findAddress($id, $type);
        if ($record){
            if(strcmp($record->addressline,"")!=0  && is_null($record->addressline) == false){
                return true;
            }
        }
        return false;
    }
    

    /**
     * Returns address record
     * 
     * @param type $id
     * @param type $type
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 29/12/2015
     * Date Last Modified: 29/12/2015
     */
    public static function getAddress($id, $type)
    {
        $address = Address::find()
                ->where(['personid' => $id , 'addresstypeid'=> $type])
                ->one();
        if (is_null($address)== true) 
            return false;
        return $address;
    }
    
}
