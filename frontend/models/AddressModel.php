<?php

namespace frontend\models;

use Yii;

class AddressModel
{
    public static function getApplicantPermanentAddress($id)
    {
        return Address::find()
        ->where([
            'personid' => $id,
            'addresstypeid' => 1,
            'isactive' => 1 ,
            'isdeleted' => 0
        ])
        ->one();
    }

    public static function getApplicantResidentialAddress($id)
    {
        return Address::find()
        ->where([
            'personid' => $id,
            'addresstypeid' => 2,
            'isactive' => 1 ,
            'isdeleted' => 0
        ])
        ->one();
    }

    public static function getApplicantPostalAddress($id)
    {
        return Address::find()
        ->where([
            'personid' => $id,
            'addresstypeid' => 3,
            'isactive' => 1 ,
            'isdeleted' => 0
        ])
        ->one();
    }
}
