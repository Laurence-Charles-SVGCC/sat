<?php

namespace frontend\models;

class CriminalRecordModel
{
    public static function getCriminalRecordByPersonId($id)
    {
        return CriminalRecord::find()
        ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
        ->one();
    }
}
