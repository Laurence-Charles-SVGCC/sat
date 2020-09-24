<?php

namespace frontend\models;

class ReferenceModel
{
    public static function getReferencesByPersonId($id)
    {
        return Reference::find()
        ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
        ->all();
    }
}
