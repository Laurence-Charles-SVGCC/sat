<?php

namespace frontend\models;

use Yii;

class CsecCentreModel
{
    public static function getCsecCentreById($id)
    {
        return CsecCentre::find()->where(['cseccentreid' => $id])->one();
    }
}
