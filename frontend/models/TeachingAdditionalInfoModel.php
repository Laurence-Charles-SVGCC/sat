<?php

namespace frontend\models;

class TeachingAdditionalInfoModel
{
    public static function getTeachingInfoByPersonId($id)
    {
        return TeachingAdditionalInfo::find()
        ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
        ->one();
    }

    public static function hasChildren($id)
    {
        $model =
        TeachingAdditionalInfo::find()
        ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
        ->one();
        
        if ($model == true && $model->childcount > 0) {
            return true;
        }
        return false;
    }
}
