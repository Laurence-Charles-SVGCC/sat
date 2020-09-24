<?php

namespace frontend\models;

use Yii;

class CompulsoryRelationModel
{
    public static function getApplicantRelationByType($id, $relationType)
    {
        return CompulsoryRelation::find()
        ->where([
            'personid' => $id,
            'relationtypeid'=>$relationType,
            'isactive' => 1,
            'isdeleted' => 0
        ])
        ->one();
    }
}
