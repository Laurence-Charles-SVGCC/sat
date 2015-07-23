<?php

namespace app\subcomponents\admissions\controllers;

class ReviewApplicationsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $amt_received = self::divisionApplicationsReceivedCount($division_id, 1);
        /*$amt_verified = self::centreApplicantsVerifiedCount($centre_id);
        $amt_pending = $amt_received - $amt_verified;
        $amt_queried = self::centreApplicantsQueriedCount($centre_id);*/

        return $this->render('index',
                [
                    //'centre_name' => $centre_name,
                    'all' => $amt_received,
                    /*'pending' => $amt_pending,
                    'verified' => $amt_verified,
                    'queried' => $amt_queried,
                    'total' => $amt_received,*/
                ]);
    }

    public function actionViewBorderline()
    {
        return $this->render('view-borderline');
    }

    public function actionViewOffers()
    {
        return $this->render('view-offers');
    }

    public function actionViewPending()
    {
        return $this->render('view-pending');
    }

    public function actionViewRejected()
    {
        return $this->render('view-rejected');
    }

    public function actionViewShortlist()
    {
        return $this->render('view-shortlist');
    }
    
    /*
    * Purpose: Gets counts of the Applications to a particular Division relevant to active application periods
     *          who have already been fully verified
    * Created: 23/07/2015 by Gamal Crichton
    * Last Modified: 23/07/2015 by Gamal Crichton
    */
    public function divisionApplicationsReceivedCount($division_id, $order)
    {
        return DatabaseWrapperController::divisionApplicationsReceivedCount($division_id, $order);
    }

}
