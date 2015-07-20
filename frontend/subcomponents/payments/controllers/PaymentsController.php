<?php

namespace app\subcomponents\payments\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Transaction;
use frontend\models\Applicant;
use yii\data\ArrayDataProvider;

class PaymentsController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionManagePayments()
    {
        return $this->render('manage-payments');
    }
    
    public function actionSearchApplicant()
    {
        $dataProvider = NULL;
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            $app_id = $request->post('id');
            $firstname = $request->post('firstname');
            $lastname = $request->post('lastname');
            
            if ($app_id)
            {
                 $cond_arr['personid'] = $app_id;
            }
            if ($firstname)
            {
                $cond_arr['firstname'] = $firstname;
            }
            if ($lastname)
            {
                $cond_arr['lastname'] = $lastname;
            }
            
            if (empty($cond_arr))
            {
                Yii::$app->getSession()->setFlash('error', 'A search criteria must be entered.');
            }
            else
            {
                $cond_arr['isdeleted'] = 0;  
            
                $applicants = Applicant::find()->where($cond_arr)->all();
                if (empty($applicants))
                {
                    Yii::$app->getSession()->setFlash('error', 'No user found matching this criteria.');
                }
                else
                {
                    $app_ids = array();
                    foreach($applicants as $applicant)
                    {
                        $app_ids[] = $applicant['personid']; 
                    }

                    $transactions = $app_ids ? Transaction::find()->where(['persionid' => $app_ids])->all()->groupby('transactionsummaryid') : array();
                    $dataProvider = new ArrayDataProvider([
                        'allModels' => $transactions,
                        'pagination' => [
                            'pageSize' => 20,
                        ],
                    ]);
                }
        }
    }
    return $this->render('search', 
    [
        'type' => 'applicant',
        'results' => $dataProvider,
    ]);
    }
}
