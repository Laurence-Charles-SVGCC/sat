<?php

namespace common\models;

use Yii;
use yii\base\Exception;

class UserModel
{
    public static function userLoginSessionExpired()
    {
        if (Yii::$app->user->isGuest == true) {
            return true;
        }
        return false;
    }


    public static function userAccountSuspended()
    {
        $user = Yii::$app->user->identity;
        if ($user == true && ($user->isactive == 0 || $user->isdeleted == 1)) {
            return true;
        }
        return false;
    }


    public static function userFailsAuthorizationCheck($authorization)
    {
        $user = Yii::$app->user->identity;
        if ($user == true && Yii::$app->user->can($authorization) == false) {
            return true;
        }
        return false;
    }


    public static function getUserLayout()
    {
        $user = Yii::$app->user->identity;
        if ($user == true) {
            $roleName = AuthorizationModel::getUserRoleName($user->personid);
            if ($roleName != null) {
                return str_replace(" ", "_", strtolower(trim($roleName)));
            }
        }

        return null;
    }


    public static function validateActionAndSetLayout($controller, $authorization)
    {
        if (self::userLoginSessionExpired() == true) {
            return $controller->goHome();
        } elseif (self::userAccountSuspended() == true) {
            Yii::$app->getSession()->setFlash(
                'error',
                'User access has been temporarily suspended. Please contact the Registrar.'
            );
            return $controller->goHome();
        } elseif (self::userFailsAuthorizationCheck($authorization) == true) {
            Yii::$app->getSession()->setFlash(
                'error',
                'You are not authorized to perform that action. Please contact the Registrar.'
            );
            return $controller->goHome();
        } else {
            return self::getUserLayout();
        }
    }


    public static function findUserByID($id)
    {
        return User::find()
            ->where(['personid' => $id/*, 'isactive' => 1, 'isdeleted' => 0*/])
            ->one();
    }


    /**
     * Searches for User model associated with username
     * 
     * @param String $username
     * @return User|null
     * 
     * Test command:
     * Untested
     */
    public static function findUserByUsername($username)
    {
        return User::find()
            ->where(['username' => $username/*, 'isactive' => 1, 'isdeleted' => 0*/])
            ->one();
    }


    public static function findUserByEmail($email)
    {
        return User::find()
            ->where(['email' => $email/*, 'isactive' => 1, 'isdeleted' => 0*/])
            ->one();
    }


    public static function findUserByResetToken($token)
    {
        return User::find()
            ->where(['resettoken' => $token, 'isactive' => 1, 'isdeleted' => 0])
            ->one();
    }


    public static function getCurrentUsername()
    {
        $user = Yii::$app->user->identity;
        if ($user == true) {
            return $user->username;
        }
        return null;
    }


    public static function isStaff($user)
    {
        return $user->persontypeid == 3;
    }


    public static function getCurrentUserDivision()
    {
        return Division::find()
            ->innerJoin('department', '`department`.`divisionid` = `division`.`divisionid`')
            ->innerJoin('employee_department', '`employee_department`.`departmentid` = `department`.`departmentid`')
            ->where(['employee_department.personid' => Yii::$app->user->identity->personid])
            ->one();
    }


    // might remove
    public static function generateLayout($userID)
    {
        if ($userID == true) {
            $roleName = AuthorizationModel::getUserRoleName($userID);
            if ($roleName != null) {
                return str_replace(" ", "_", strtolower(trim($roleName)));
            }
        }
        // return "main.php";
        // return null;
        return "/role_specific_layout_not_found.php";
    }



    public static function canDeactivateUserAccount($user)
    {
        if ($user == true && $user->isactive == 1 && $user->isdeleted == 0) {
            return true;
        }
        return false;
    }


    public static function deactivateUserAccount($user)
    {
        if ($user == true) {
            $user->isactive = 0;
            $user->isdeleted = 1;
            if ($user->save() == true) {
                return $user;
            }
        }
        return null;
    }


    public static function canReactivateUserAccount($user)
    {
        if ($user == true && $user->isactive == 0 && $user->isdeleted == 0) {
            return true;
        }
        return false;
    }


    public static function reactivateUserAccount($user)
    {
        if ($user == true) {
            $user->isactive = 1;
            $user->isdeleted = 0;
            if ($user->save() == true) {
                return $user;
            }
        }
        return null;
    }


    public static function getUniversalAccountTypes($user)
    {
        return "Pending";
    }


    public static function updateUserRoleAndEmployeeTitle($user, $update)
    {
        if ($user == true && $update == true) {
            if (
                AuthorizationModel::assignRoleToUser($user, $update) == true
                && EmployeeModel::updateEmployeeTitleOfUser($user, $update) == true
            ) {
                return true;
            }
        }

        return false;
    }


    public static function updateUserDepartment($user, $department_name)
    {
        if ($user == true && $department_name == true) {
            $department =
                Department::find()
                ->where(["name" => $department_name])
                ->one();

            if ($department == true) {
                $current_employee_department =
                    EmployeeDepartment::find()
                    ->where(['personid' => $user->personid])
                    ->one();

                if ($current_employee_department == true) {
                    $current_employee_department->departmentid = $department->departmentid;
                    return $current_employee_department->save();
                } else {
                    $employee_department = new EmployeeDepartment();
                    $employee_department->personid = $user->personid;
                    $employee_department->departmentid = $department->departmentid;
                    return $employee_department->save();
                }
            }
        }

        return false;
    }


    public static function getUserDepartmentName($user)
    {
        $current_employee_departments =
            Department::find()
            ->innerJoin('employee_department', '`employee_department`.`departmentid` = `department`.`departmentid`')
            ->where(['employee_department.personid' => $user->personid])
            ->all();

        if (!empty($current_employee_departments)) {
            return $current_employee_departments[0]->name;
        }

        return false;
    }



    public static function getUserDepartmentAssignment($user)
    {
        if ($user == true) {
            return EmployeeDepartment::find()
                ->where(['personid' => $user->personid])
                ->one();
        }
        return null;
    }

    public static function getDepartmentByRole($role)
    {
        $department_name = false;

        switch ($role) {
                // E-College
            case "System Administrator":
                $department_name = "E-College";
                break;
            case "E-College Head":
                $department_name = "E-College";
                break;
            case "E-College Power User":
                $department_name = "E-College";
                break;
            case "E-College Power User":
                $department_name = "E-College";
                break;

                // Directorate
            case "Director":
                $department_name = "Directorate";
                break;
            case "Deputy Director":
                $department_name = "Directorate";
                break;

                // Bursary
            case "Bursar":
                $department_name = "Bursary";
                break;

            case "Bursary Staff":
                $department_name = "Bursary";
                break;

                // Registry
            case "Registrar":
                $department_name = "Registry";
                break;
            case "Assistant Registrar":
                $department_name = "Registry";
                break;
            case "Registry Staff":
                $department_name = "Registry";
                break;

                // Library
            case "Head Librarian":
                $department_name = "Library (SVGCC)";
                break;
            case "Deputy Librarian":
                $department_name = "Library (SVGCC)";
                break;
            case "DASGS Librarian":
                $department_name = "Library (DASGS)";
                break;
            case "DTVE Librarian":
                $department_name = "Library (DTVE)";
                break;
            case "DTE Librarian":
                $department_name = "Library (DTE)";
                break;
            case "DNE Librarian":
                $department_name = "Library (DNE)";
                break;
            case "DASGS Library Staff":
                $department_name = "Library (DASGS)";
                break;
            case "DTVE Library Staff":
                $department_name = "Library (DTVE)";
                break;
            case "DTE Library Staff":
                $department_name = "Library (DTE)";
                break;
            case "DNE Library Staff":
                $department_name = "Library (DNE)";
                break;

                // Faculty
            case "Lecturer":
                $department_name = "Library (DNE)";
                break;

                // Counsellor
            case "Counsellor":
                $department_name = "Administrative (SVGCC)";
                break;

                // DASGS
            case "DASGS Dean":
                $department_name = "DASGS Senior";
                break;
            case "DASGS Deputy Dean":
                $department_name = "DASGS Senior";
                break;
            case "DASGS Secretary":
                $department_name = "Administrative (DASGS)";
                break;
            case "DASGS Clerk":
                $department_name = "Administrative (DASGS)";
                break;

                // DTVE
            case "DTVE Dean":
                $department_name = "DTVE Senior";
                break;
            case "DTVE Deputy Dean":
                $department_name = "DTVE Senior";
                break;
            case "DTVE Secretary":
                $department_name = "Administrative (DTVE)";
                break;
            case "DTVE Clerk":
                $department_name = "Administrative (DTVE)";
                break;

                // DTE
            case "DTE Dean":
                $department_name = "DTE Senior";
                break;
            case "DTE Deputy Dean":
                $department_name = "DTE Senior";
                break;
            case "DTE Secretary":
                $department_name = "Administrative (DTE)";
                break;
            case "DTE Clerk":
                $department_name = "Administrative (DTE)";
                break;

                // DNE
            case "DNE Dean":
                $department_name = "DNE Senior";
                break;
            case "DNE Deputy Dean":
                $department_name = "DNE Senior";
                break;
            case "DNE Secretary":
                $department_name = "Administrative (DNE)";
                break;
            case "DNE Clerk":
                $department_name = "Administrative (DNE)";
                break;

                // Admissions
            case "DTVE Admissions Member":
                $department_name = "Administrative (DTVE)";
                break;
            case "DTE Admissions Team Adjuster":
                $department_name = "Administrative (DTE)";
                break;
            case "DTE Admissions Member":
                $department_name = "Administrative (DTE)";
                break;
            case "DNE Admissions Team Adjuster":
                $department_name = "Administrative (DNE)";
                break;
            case "DNE Admissions Member":
                $department_name = "Administrative (DNE)";
                break;

                // DTVE Legacy
            case "DTVE Legacy Coordinator":
                $department_name = "Administrative (DTVE)";
                break;
            case "DTVE Legacy Assessor":
                $department_name = "Administrative (DTVE)";
                break;
            case "DTVE Legacy Clerk":
                $department_name = "Administrative (DTVE)";
                break;
            case "DTVE Legacy Data Entry":
                $department_name = "Administrative (DTVE)";
                break;

                // Front Desk Officer
            case "Front Desk Officer":
                $department_name = "Administrative (SVGCC)";
                break;

            default:
                $department_name = false;
        }
        return $department_name;
    }


    public static function createLectuerUserRecord($signup_model)
    {
        if ($signup_model == true && $signup_model->validate() == true) {
            $user = new User();
            $user->email = $signup_model->institutional_email;
            $user->persontypeid = 3;
            $user->isactive = 1;
            $user->isdeleted = 0;

            if (
                $signup_model->username == '' || $signup_model->username == null
                || $signup_model->username == false
            ) {
                $department =
                    Department::find()
                    ->where(
                        [
                            'departmentid' => $signup_model->departmentid,
                            'isactive' => 1, 'isdeleted' => 0
                        ]
                    )
                    ->one();

                $user->username =
                    EmployeeModel::generateEmployeeUsername(date("Y"), $department->divisionid);
            } else {
                $user->username = $signup_model->username;
            }

            if ($user->save() == true) {
                return $user;
            }
        }
        return null;
    }


    public static function createPersonalEmailRecord($user_id, $email_address)
    {
        $user = self::findUserByID($user_id);
        if ($user == true) {
            $email = new Email();
            $email->email = $email_address;
            $email->personid = $user->personid;
            $email->priority = 1;
            if ($email->save() == true) {
                return $email;
            }
        }
        return null;
    }


    public static function createEmployeeRecord($user_id, $signup_model)
    {
        $user = self::findUserByID($user_id);
        if (
            $user == true
            && $signup_model == true && $signup_model->validate() == true
        ) {
            $employee = new Employee();
            $employee->personid = $user_id;
            $employee->title = ucfirst($signup_model->title);
            $employee->firstname = ucfirst($signup_model->firstname);

            if ($signup_model->middlename == true) {
                $employee->middlename = ucfirst($signup_model->middlename);
            }

            $employee->lastname = ucfirst($signup_model->lastname);
            $employee->employeetitleid = $signup_model->employeetitleid;
            $employee->maritalstatus = $signup_model->maritalstatus;
            $employee->religion = $signup_model->religion;
            $employee->nationality = $signup_model->nationality;
            $employee->placeofbirth = $signup_model->placeofbirth;
            $employee->nationalidnumber = $signup_model->nationalidnumber;
            $employee->nationalinsurancenumber = $signup_model->nationalinsurancenumber;
            $employee->inlandrevenuenumber = $signup_model->inlandrevenuenumber;
            $employee->gender = $signup_model->gender;
            $employee->dateofbirth = $signup_model->dateofbirth;

            if ($employee->save() == true) {
                return $employee;
            }
        }
        return null;
    }


    public static function assignEmployeeToDepartment($user_id, $department_id)
    {
        $user = self::findUserByID($user_id);
        if ($user == true && $department_id == true) {
            $department_assignment = new EmployeeDepartment();
            $department_assignment->departmentid = $department_id;
            $department_assignment->personid = $user_id;

            if ($department_assignment->save() == true) {
                return $department_assignment;
            }
        }
        return null;
    }


    public static function createLecturerAccount($signup_model)
    {
        if ($signup_model == true && $signup_model->validate() == true) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $user = self::createLectuerUserRecord($signup_model);

                if ($user == true) {
                    $email =
                        self::createPersonalEmailRecord(
                            $user->personid,
                            $signup_model->personal_email
                        );

                    if ($email == true) {
                        $employee =
                            self::createEmployeeRecord($user->personid, $signup_model);

                        if ($employee == true) {
                            $department_assignment =
                                self::assignEmployeeToDepartment(
                                    $user->personid,
                                    $signup_model->departmentid
                                );

                            if ($department_assignment == true) {
                                $transaction->commit();
                                return $user;
                            }
                        }
                    }
                }
                $transaction->rollBack();
            } catch (Exception $ex) {
                $transaction->rollBack();
                return null;
            }
        }
        return null;
    }


    public static function createCredentialedUserRecord($signup_model)
    {
        if ($signup_model == true && $signup_model->validate() == true) {
            $user = new User();
            $user->email = $signup_model->institutional_email;
            $user->persontypeid = 3;
            $user->setPassword($signup_model->password);
            $user->setSalt();
            $user->isactive = 1;
            $user->isdeleted = 0;

            if (
                $signup_model->username == '' || $signup_model->username == null
                || $signup_model->username == false
            ) {
                $department =
                    Department::find()
                    ->where(
                        [
                            'departmentid' => $signup_model->departmentid,
                            'isactive' => 1, 'isdeleted' => 0
                        ]
                    )
                    ->one();

                $user->username =
                    EmployeeModel::generateEmployeeUsername(date("Y"), $department->divisionid);
            } else {
                $user->username = $signup_model->username;
            }

            if ($user->save() == true) {
                return $user;
            }
        }
        return null;
    }


    public function sendWelcomeEmail($user, $password)
    {
        if ($user == true && $password == true) {
            return Yii::$app->account_creation_mailer
                ->compose(
                    'user-account-creation/welcome.php',
                    [
                        'full_name' => EmployeeModel::getEmployeeFullName($user->personid),
                        'username' => $user->username,
                        'password' => $password
                    ]
                )
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo($user->email)
                ->setSubject('Welcome to the SVGCC Administrative Terminal')
                ->send();
        }
        return false;
    }


    public static function createCredentialedUser($signup_model)
    {
        if ($signup_model == true && $signup_model->validate() == true) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $user = self::createCredentialedUserRecord($signup_model);

                if ($user == true) {
                    $email =
                        self::createPersonalEmailRecord(
                            $user->personid,
                            $signup_model->personal_email
                        );

                    if ($email == true) {
                        $employee =
                            self::createEmployeeRecord($user->personid, $signup_model);

                        if ($employee == true) {
                            $department_assignment =
                                self::assignEmployeeToDepartment(
                                    $user->personid,
                                    $signup_model->departmentid
                                );

                            if ($department_assignment == true) {
                                if (
                                    $signup_model->send_welcome_email == true
                                    && self::sendWelcomeEmail($user, $signup_model->password) == true
                                ) {
                                    $transaction->commit();
                                    return $user;
                                } elseif ($signup_model->send_welcome_email == false) {
                                    $transaction->commit();
                                    return $user;
                                }
                            }
                        }
                    }
                }
                $transaction->rollBack();
            } catch (Exception $ex) {
                $transaction->rollBack();
                return null;
            }
        }
        return null;
    }


    public static function getUserStatus($user)
    {
        if ($user == true) {
            if ($user->isdeleted == 1) {
                return "Removed";
            } elseif ($user->isactive == 1 && $user->isdeleted == 0) {
                return "Active";
            } elseif ($user->isactive == 0 && $user->isdeleted == 0) {
                return "Disabled";
            }
        }
        return "Unknown";
    }


    public static function findUserByApplicantIDOrStudentID($username)
    {
        /* if applicantID is entered && user is currently applicant
        OR if studentID is entered && user is currently student
        */
        $userTableSearchResult =
            User::find()
            ->where(["username" => $username, "persontypeid" => [1, 2]])
            ->one();

        if ($userTableSearchResult == true) {
            return $userTableSearchResult;
        }

        /* if applicantID is entered && user is currently student */
        $studentTableSearchResult =
            Student::find()->where(["applicantname" => $username])->one();

        if ($studentTableSearchResult == true) {
            $user = self::findUserByID($studentTableSearchResult->personid);
            if ($user == true) {
                return $user;
            }
        }
        return null;
    }


    public static function getUserFullname($user)
    {
        if ($user->persontypeid == 1) {   // if applicant
            $applicant = ApplicantModel::getApplicantByPersonid($user->personid);
            return ApplicantModel::getApplicantFullName($applicant);
        } elseif ($user->persontypeid == 2) {   // if student
            $student = StudentModel::getStudentByPersonid($user->personid);
            return StudentModel::getStudentFullName($student);
        } elseif ($user->persontypeid == 3) {   // if employee
            return EmployeeModel::getEmployeeFullName($user->personid);
        }
    }

    public static function isSuccessfulApplicant($user)
    {
        $successfulApplications =
            Application::find()
            ->where([
                "personid" => $user->personid,
                "applicationstatusid" => 9,
                "isactive" => 1,
                "isdeleted" => 0
            ])
            ->orderBy('ordering DESC')
            ->all();
        if (!empty($successfulApplications)) {
            $currentApplication =  $successfulApplications[0];
            $currentOffer =
                Offer::find()
                ->where([
                    "applicationid" => $currentApplication->applicationid,
                    "isactive" => 1,
                    "isdeleted" => 0,
                    "ispublished" => 1
                ])
                ->one();
            if ($currentOffer == true) {
                return true;
            }
        }
        return false;
    }

    public static function isCompletedApplicant($user)
    {
        $successfulApplications =
            Application::find()
            ->where([
                "personid" => $user->personid,
                "applicationstatusid" => 9,
                "isactive" => 1,
                "isdeleted" => 0
            ])
            ->orderBy('ordering DESC')
            ->all();
        if (!empty($successfulApplications)) {
            $currentApplication =  $successfulApplications[0];
            $currentOffer =
                Offer::find()
                ->where([
                    "applicationid" => $currentApplication->applicationid,
                    "isactive" => 1,
                    "isdeleted" => 0,
                    "ispublished" => 0
                ])
                ->one();
            if ($currentOffer == true) {
                return true;
            }
        } else {
            $targetApplications =
                Application::find()
                ->where([
                    "personid" => $user->personid,
                    "applicationstatusid" => [2, 3, 4, 5, 6, 7, 8, 10],
                    "isactive" => 1,
                    "isdeleted" => 0
                ])
                ->all();
            if ($targetApplications == true) {
                return true;
            }
        }
        return false;
    }

    public static function isInCompletedApplicant($user)
    {
        $targetApplications =
            Application::find()
            ->where([
                "personid" => $user->personid,
                "applicationstatusid" => 1,
                "isactive" => 1,
                "isdeleted" => 0
            ])
            ->one();
        if ($targetApplications == true) {
            return true;
        }
        return false;
    }

    public static function isAbandonedApplicant($user)
    {
        $targetApplications =
            Application::find()
            ->where([
                "personid" => $user->personid,
                "applicationstatusid" => 11,
                "isactive" => 1,
                "isdeleted" => 0
            ])
            ->one();
        if ($targetApplications == true) {
            return true;
        }
        return false;
    }


    /**
     * Classifies User account
     * 
     * @param User $user
     * @return String
     * 
     * Test Command:
     * Untested
     */
    public static function  getUserClassification($user)
    {
        $isStudent = StudentModel::getActiveStudentByPersonid($user->personid);
        if ($isStudent == true) {
            return "Student";
        } elseif (self::isSuccessfulApplicant($user) == true) {
            return "Successful Applicant";
        } elseif (self::isCompletedApplicant($user) == true) {
            return "Completed Applicant";
        } elseif (self::isAbandonedApplicant($user) == true) {
            return "Abandoned Applicant";
        } elseif (self::isIncompletedApplicant($user) == true) {
            return "Incomplete Applicant";
        }
        return false;
    }


    /**
     * Return search results for User model by username field
     * 
     * @param string $username
     * @return User
     * 
     * Test command:
     * Untested
     */
    public static function getUserByUsername($username)
    {
        return User::find()->where(["username" => $username])->one();
    }


    /**
     * Returns search result for User mode by personid field
     *
     * @param integer $id
     * @return User
     * 
     * Test command:
     * Untested
     */
    public static function getUserById($id)
    {
        return User::find()->where(["personid" => $id])->one();
    }


    public static function findUserByApplicantIdPotentialStudentIdOrStudentId(
        $username
    ) {
        /* if applicantID is entered && user is currently applicant
        OR if studentID is entered && user is currently student
        */
        $userTableSearchResult =
            User::find()
            ->where(["username" => $username, "persontypeid" => [1, 2]])
            ->one();

        if ($userTableSearchResult == true) {
            return $userTableSearchResult;
        }

        /* if StudentID is entered by successful applicant has not enrolled yet */
        $potentialStudentIdSearchResult =
            Applicant::find()->where(["potentialstudentid" => $username])->one();

        if ($potentialStudentIdSearchResult == true) {
            $user =
                self::findUserByID($potentialStudentIdSearchResult->personid);
            if ($user == true) {
                return $user;
            }
        }

        /* if applicantID is entered && user is currently student */
        $studentTableSearchResult =
            Student::find()->where(["applicantname" => $username])->one();

        if ($studentTableSearchResult == true) {
            $user = self::findUserByID($studentTableSearchResult->personid);
            if ($user == true) {
                return $user;
            }
        }
        return null;
    }
}
