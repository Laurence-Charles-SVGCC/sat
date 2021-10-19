<?php

namespace common\models;

class RelationModel
{
    /**
     * Returns search for Relation model of type Beneficiary, by personid field
     *
     * @param User $user
     * @return Relation|null
     * 
     * Test command:
     * Untested
     */
    public static function getBeneficiaryRelation($user)
    {
        return Relation::find()
            ->where([
                "personid" => $user->personid,
                "relationtypeid" => 6,
                "isactive" => 1,
                "isdeleted" => 0,
            ])
            ->one();
    }


    public static function getRelationFullname($relation)
    {
        return `{$relation->title} {$relation->firstname} {$relation->lastname}`;
    }


    public static function getRelationContactDetails($relation)
    {
        if ($relation == null) {
            return null;
        }

        $contactInfo = ``;
        if ($relation->homephone == true) {
            $contactInfo .= `Home Phone - {$relation->homephone}\n`;
        }
        if ($relation->homephone == true) {
            $contactInfo .= `Cellphone - {$relation->cellphone}\n`;
        }
        if ($relation->homephone == true) {
            $contactInfo .= `Work Phone - {$relation->workphone}`;
        }
        return $contactInfo;
    }


    public static function getBeneficiarySummary($beneficiary)
    {
        if ($beneficiary == null) {
            return null;
        } else {
            return  self::getRelationFullname($beneficiary)
                . "\n"
                . self::getRelationContactDetails($beneficiary);
        }
    }
}
