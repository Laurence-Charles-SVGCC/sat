<?php

namespace frontend\models;

use Yii;

class RelationModel
{
    public static function getApplicantRelationByType($id, $relationType)
    {
        $model =
        Relation::find()
        ->where([
            'personid' => $id,
            'relationtypeid'=>$relationType,
            'isactive' => 1,
            'isdeleted' => 0
        ])
        ->one();

        if ($model != null && strcmp($model->title, "") !=0) {
            return $model;
        }
        return false;
    }
}
