<?php

namespace common\models;

class OfferModel
{
    public static function getOfferById($id)
    {
        return Offer::find()->where(["offerid" => $id])->one();
    }

    public static function getApplication($offer)
    {
        return Application::find()->where(["applicationid" => $offer->applicationid])->one();
    }
}
