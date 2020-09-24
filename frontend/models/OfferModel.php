<?php

namespace frontend\models;

class OfferModel
{
    public static function getOffersByPersonId($id)
    {
        return Offer::find()
        ->innerJoin(
            'application',
            '`application`.`applicationid` = `offer`.`applicationid`'
        )
        ->where([
            'offer.isactive' => 1,
            'offer.isdeleted' => 0,
            'application.isactive' => 1,
            'application.isdeleted' => 0,
            'application.personid' => $id
        ])
        ->all();
    }


    public static function hasOffers($id)
    {
        $offers = self::getOffersByPersonId($id);
        if (count($offers) > 0) {
            return true;
        }
        return false;
    }


    public static function getPriorityOffer($personid)
    {
        $offers = self::getOffersByPersonId($personid);
        if ($offers == true) {
            $hasFullOffer = false;
            foreach ($offers as $off) {
                if ($off->offertypeid == 1) {
                    return "Applicant has Full Offer";
                }
            }
            return "Applicant has Interview Invitation";
        }
        return null;
    }
}
