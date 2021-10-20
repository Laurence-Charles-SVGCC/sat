<?php

namespace app\subcomponents\bursary\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\AuthorizationModel;
use common\models\HoldTypeModel;
use common\models\StudentHoldForm;
use common\models\StudentHoldModel;
use common\models\StudentHoldNotificationForm;
use common\models\StudentHoldViewModel;
use common\models\StudentRegistrationModel;
use common\models\UserModel;

class HoldsController extends \yii\web\Controller
{
    public function actionViewHold($id)
    {
        $hold = StudentHoldModel::getStudentHoldByID($id);
        $formattedHold = new StudentHoldViewModel($hold);

        $studentRegistration =
            StudentRegistrationModel::getStudentRegistrationByID(
                $hold->studentregistrationid
            );

        $customer = UserModel::findUserByID($studentRegistration->personid);

        return $this->render(
            "view-hold",
            [
                "hold" => $formattedHold,
                "username" => $customer->username,
                "userFullname" => UserModel::getUserFullname($customer)
            ]
        );
    }


    public function actionAddFinancialHold($username)
    {
        $user = Yii::$app->user->identity;
        $hold = new StudentHoldForm();
        $customer = UserModel::findUserByUsername($username);
        $userFullname = UserModel::getUserFullname($customer);

        $studentRegistrations =
            StudentRegistrationModel::getStudentRegistrationsByPersonID(
                $customer->personid
            );

        $formattedStudentRegistrationsListing =
            ArrayHelper::map(
                StudentRegistrationModel::formatStudentRegistrationsIntoAssociativeArray(
                    $studentRegistrations
                ),
                'id',
                'name'
            );

        $holdTypes =
            ArrayHelper::map(
                HoldTypeModel::getAllFinancialHoldTypes(),
                'holdtypeid',
                'name'
            );

        if ($postData = Yii::$app->request->post()) {
            if ($hold->load($postData) == true) {
                $studentHold = $hold->generateFinancialHold($user->personid);
                if ($studentHold->save() == true) {
                    Yii::$app->getSession()->setFlash(
                        "success",
                        "Hold creation successful"
                    );
                    return $this->redirect([
                        "profiles/redirect-to-customer-profile",
                        "username" => $customer->username
                    ]);
                } else {
                    Yii::$app->getSession()->setFlash(
                        "error",
                        "Hold creation failed."
                    );
                }
            }
        }

        return $this->render(
            "add-financial-hold",
            [
                "hold" => $hold,
                "holdTypes" => $holdTypes,
                "studentRegistrations" => $formattedStudentRegistrationsListing,
                "username" => $customer->username,
                "userFullname" => $userFullname
            ]
        );
    }


    public function actionResolveHold($id)
    {
        $user = Yii::$app->user->identity;
        $studentHold = StudentHoldModel::getStudentHoldByID($id);

        if (
            $studentHold == true
            && StudentHoldModel::resolveHold($studentHold, $user->personid) == true
        ) {
            $studentRegistration =
                StudentRegistrationModel::getStudentRegistrationByID(
                    $studentHold->studentregistrationid
                );

            $customer = UserModel::findUserByID($studentRegistration->personid);

            Yii::$app->getSession()->setFlash(
                "success",
                "Hold resolution successful"
            );
        } else {
            Yii::$app->getSession()->setFlash(
                "error",
                "Error occured resolving hold record."
            );
        }

        return $this->redirect([
            "profiles/redirect-to-customer-profile",
            "username" => $customer->username
        ]);
    }


    public function actionReactivateHold($id)
    {
        $user = Yii::$app->user->identity;
        $studentHold = StudentHoldModel::getStudentHoldByID($id);

        if (
            $studentHold == true
            && StudentHoldModel::reactivateHold($studentHold, $user->personid)
            == true
        ) {
            $studentRegistration =
                StudentRegistrationModel::getStudentRegistrationByID(
                    $studentHold->studentregistrationid
                );

            $customer = UserModel::findUserByID($studentRegistration->personid);

            Yii::$app->getSession()->setFlash(
                "success",
                "Hold reactivation successful"
            );
        } else {
            Yii::$app->getSession()->setFlash(
                "error",
                "Error occured reactivating hold record."
            );
        }

        return $this->redirect([
            "profiles/redirect-to-customer-profile",
            "username" => $customer->username
        ]);
    }


    public function actionDeleteHold($id)
    {
        $user = Yii::$app->user->identity;
        $studentHold = StudentHoldModel::getStudentHoldByID($id);

        if (
            $studentHold == true
            && StudentHoldModel::deleteHold($studentHold, $user->personid)
            == true
        ) {
            $studentRegistration =
                StudentRegistrationModel::getStudentRegistrationByID(
                    $studentHold->studentregistrationid
                );

            $customer = UserModel::findUserByID($studentRegistration->personid);

            Yii::$app->getSession()->setFlash(
                "success",
                "Hold removal successful"
            );
        } else {
            Yii::$app->getSession()->setFlash(
                "error",
                "Error occured removing hold record."
            );
        }

        return $this->redirect([
            "profiles/redirect-to-customer-profile",
            "username" => $customer->username
        ]);
    }


    public function actionPublishHoldNotification($id)
    {
        $model = new StudentHoldNotificationForm();
        $hold = StudentHoldModel::getStudentHoldByID($id);
        $holdType = HoldTypeModel::getHoldTypeByID($hold->holdtypeid);

        $studentRegistration =
            StudentRegistrationModel::getStudentRegistrationByID(
                $hold->studentregistrationid
            );

        $customer = UserModel::findUserByID($studentRegistration->personid);

        $userFullname = UserModel::getUserFullname($customer);

        $holdDescription =
            StudentRegistrationModel::generateRegistrationDescription(
                $studentRegistration->studentregistrationid
            );

        if ($postData = Yii::$app->request->post()) {
            if ($model->load($postData) == true) {
                if (
                    StudentHoldModel::processPublishingRequest($hold, $model)
                    == true
                ) {
                    Yii::$app->getSession()->setFlash(
                        "success",
                        "Hold sent successful"
                    );
                    return $this->redirect(["view-hold", "id" => $id]);
                } else {
                    Yii::$app->getSession()->setFlash(
                        "error",
                        "Hold publishing failed."
                    );
                }
            }
        }

        return $this->render(
            "publish-hold-notification",
            [
                "model" => $model,
                "hold" => $hold,
                "holdType" => $holdType,
                "holdDescription" => $holdDescription,
                "username" => $customer->username,
                "userFullname" => $userFullname
            ]
        );
    }
}
