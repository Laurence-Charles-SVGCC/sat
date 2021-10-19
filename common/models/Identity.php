<?php

namespace common\models;

use Yii;

class Identity
{
  /**
   * [userLoginSessionExpired description]
   * @return boolean
   */
  public static function userLoginSessionExpired()
  {
    if (Yii::$app->user->isGuest == true) {
      return true;
    }
    return false;
  }


  /**
   * [userAccountSuspended description]
   * @return boolean
   */
  public static function userAccountSuspended()
  {
    $user = Yii::$app->user->identity;
    if ($user == true && ($user->isactive == 0 || $user->isdeleted == 1)) {
      return true;
    }
    return false;
  }

  /**
   * [userFailsAuthorizationModelCheck description]
   * @param  string $authorization
   * @return boolean
   */
  public static function userFailsAuthorizationModelCheck($authorization)
  {
    $user = Yii::$app->user->identity;
    if ($user == true && Yii::$app->user->can($authorization) == false) {
      return true;
    }
    return false;
  }


  /**
   * [getUserLayout description]
   * @return string
   */
  public static function getUserLayout()
  {
    $user = Yii::$app->user->identity;
    if ($user == true) {
      $roleName = AuthorizationModel::getUserRoleName($user->personid);
      if ($roleName != null) {
        return str_replace(" ", "_", strtolower(trim($roleName)));
      }
    }

    return "/user_role_assignment_not_found.php";
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
    } elseif (self::userFailsAuthorizationModelCheck($authorization) == true) {
      Yii::$app->getSession()->setFlash(
        'error',
        'You are not authorized to perform that action. Please contact the Registrar.'
      );
      return $controller->goHome();
    } else {
      return self::getUserLayout();
    }
  }

  /**
   * Finds user by id
   *
   * @param integer $id
   * @return User|NULL
   */
  public static function findUserByID($id)
  {
    return User::find()
      ->where(['personid' => $id/*, 'isactive' => 1, 'isdeleted' => 0*/])
      ->one();
  }

  /**
   * Finds user by username
   *
   * @param string $username
   * @return User|NULL
   */
  public static function findUserByUsername($username)
  {
    return User::find()
      ->where(['username' => $username/*, 'isactive' => 1, 'isdeleted' => 0*/])
      ->one();
  }

  /**
   * Finds user by email
   *
   * @param string $email
   * @return User|NULL
   */
  public static function findUserByEmail($email)
  {
    return User::find()
      ->where(['email' => $email/*, 'isactive' => 1, 'isdeleted' => 0*/])
      ->one();
  }

  /**
   * Finds user by resettoken
   *
   * @param string $token
   * @return User|null
   */
  public static function findUserByResetToken($token)
  {
    return User::find()
      ->where(['resettoken' => $token, 'isactive' => 1, 'isdeleted' => 0])
      ->one();
  }

  /**
   * Returns current user's username
   * @return String
   */
  public static function getCurrentUsername()
  {
    $user = Yii::$app->user->identity;
    if ($user == true) {
      return $user->username;
    }
    return null;
  }

  /**
   * Determines whether user is staff member
   *
   * @param  User  $user
   * @return boolean
   */
  public static function isStaff($user)
  {
    return $user->persontypeid == 3;
  }

  /**
   * Determines division that current user is attached to
   *
   * @return User|null
   */
  public static function getCurrentUserDivision()
  {
    return Division::find()
      ->innerJoin('department', '`department`.`divisionid` = `division`.`divisionid`')
      ->innerJoin('employee_department', '`employee_department`.`departmentid` = `department`.`departmentid`')
      ->where(['employee_department.personid' => Yii::$app->user->identity->personid])
      ->one();
  }

  /**
   * Returns the name of the layout file appropriate for current user
   *
   * @return String ["main" | "member_svgcc" | "member_dasgs" | "member_dtve" | "member_dte" | "member_dne"]
   */
  public static function getCurrentUserLayout()
  {
    $division = self::getCurrentUserDivision();
    if ($division == null) {
      return "main";
    } else {
      return "member_" . strtolower($division->abbreviation);
    }
  }

  /**
   * Return role-based view layout
   * @param  integer $user_id
   * @return string
   */
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


  /**
   * canDeactivateUserAccount
   * @param  User $user
   * @return boolean
   */
  public static function canDeactivateUserAccount($user)
  {
    if ($user == true && $user->isactive == 1 && $user->isdeleted == 0) {
      return true;
    }
    return false;
  }

  /**
   * deactivateUserAccount
   * @param  User $user
   * @return User
   */
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

  /**
   * canReactivateUserAccount
   * @param  User $user
   * @return boolean
   */
  public static function canReactivateUserAccount($user)
  {
    if ($user == true && $user->isactive == 0 && $user->isdeleted == 0) {
      return true;
    }
    return false;
  }



  /**
   * reactivateUserAccount
   * @param  User $user
   * @return User
   */
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


  /**
   * updateUserRoleAndEmployeeTitle
   * @param  User $user
   * @param  string $update
   * @return boolean
   */
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


  /**
   * updateUserDepartment
   * @param  User $user
   * @param  string $department_name
   * @return boolean
   */
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


  /**
   * getUserDepartmentName
   * @param  User $user
   * @return boolean
   */
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


  /**
   * getUserDepartmentAssignment
   * @param  User $user
   * @return EmployeeDepartment|NULL
   */
  public static function getUserDepartmentAssignment($user)
  {
    if ($user == true) {
      return EmployeeDepartment::find()
        ->where(['personid' => $user->personid])
        ->one();
    }
    return null;
  }


  /**
   * getDepartmentByRole
   * @param  string $role
   * @return string
   */
  public static function getDepartmentByRole($role)
  {
    $department_name = false;

    switch ($role) {
      case "Admission Team Adjuster (DASGS)":
        $department_name = "Administrative (DASGS)";
        break;

      case "Admission Team Adjuster (DTVE)":
        $department_name = "Administrative (DTVE)";
        break;

      case "Admission Team Adjuster (DTE)":
        $department_name = "Administrative (DTE)";
        break;

      case "Admission Team Adjuster (DNE)":
        $department_name = "Administrative (DNE)";
        break;

      case "Admissions Member (DASGS)":
        $department_name = "Administrative (DASGS)";
        break;

      case "Admissions Member (DTVE)":
        $department_name = "Administrative (DTVE)";
        break;

      case "Admissions Member (DTE)":
        $department_name = "Administrative (DTE)";
        break;

      case "Admissions Member (DNE)":
        $department_name = "Administrative (DNE)";
        break;

      case "Assistant Registrar":
        $department_name = "Registry";
        break;

      case "Bursar":
        $department_name = "Bursary";
        break;

      case "Bursary Staff":
        $department_name = "Bursary";
        break;

      case "DTE Secretary":
        $department_name = "Administrative (DTE)";
        break;

      case "DTVE Legacy Assessor":
        $department_name = "Administrative (DTVE)";
        break;

      case "DTVE Legacy Clerk":
        $department_name = "Administrative (DTVE)";
        break;

      case "DTVE Legacy Coordinator":
        $department_name = "Administrative (DTVE)";
        break;

      case "DTVE Legacy Data Entry":
        $department_name = "Administrative (DTVE)";
        break;

      case "Dean (DASGS)":
        $department_name = "DASGS Senior";
        break;

      case "Deputy Dean (DASGS)":
        $department_name = "DASGS Senior";
        break;

      case "Dean (DTVE)":
        $department_name = "DTVE Senior";
        break;

      case "Deputy Dean (DTVE)":
        $department_name = "DTVE Senior";
        break;

      case "Dean (DTE)":
        $department_name = "DTE Senior";
        break;

      case "Deputy Dean (DTE)":
        $department_name = "DTE Senior";
        break;

      case "Dean (DNE)":
        $department_name = "DNE Senior";
        break;

      case "Deputy Dean (DNE)":
        $department_name = "DNE Senior";
        break;

      case "Deputy Director":
        $department_name = "Directorate";
        break;

      case "Deputy Librarian":
        $department_name = "Library (SVGCC)";
        break;

      case "Director":
        $department_name = "Directorate";
        break;

      case "Divisional Staff (DASGS)":
        $department_name = "Administrative (DASGS)";
        break;

      case "Divisional Staff (DTVE)":
        $department_name = "Administrative (DTVE)";
        break;

      case "Divisional Staff (DTE)":
        $department_name = "Administrative (DTE)";
        break;

      case "Divisional Staff (DNE)":
        $department_name = "Administrative (DNE)";
        break;

      case "E-College Head":
        $department_name = "E-College";
        break;

      case "Head Librarian":
        $department_name = "Library (SVGCC)";
        break;

      case "Librarian (DASGS)":
        $department_name = "Library (DASGS)";
        break;

      case "Librarian (DTVE)":
        $department_name = "Library (DTVE)";
        break;

      case "Librarian (DTE)":
        $department_name = "Library (DTE)";
        break;

      case "Librarian (DNE)":
        $department_name = "Library (DNE)";
        break;

      case "Library Staff (DASGS)":
        $department_name = "Library (DASGS)";
        break;

      case "Library Staff (DTVE)":
        $department_name = "Library (DTVE)";
        break;

      case "Library Staff (DTE)":
        $department_name = "Library (DTE)";
        break;

      case "Library Staff (DNE)":
        $department_name = "Library (Determines)";
        break;

      case "Registrar":
        $department_name = "Registry";
        break;

      case "Registry Staff":
        $department_name = "Registry";
        break;

      case "Counsellor":
        $department_name = "Adminsitrative (SVGCC)";
        break;

      default:
        $department_name = false;
    }
    return $department_name;
  }


  /**
   * createLectuerUserRecord
   * @param  SignupLecturerForm $signup_model
   * @return User
   */
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


  /**
   * createPersonalEmailRecord
   * @param  integer $user_id
   * @param  string $email_address
   * @return Email
   */
  public static function createPersonalEmailRecord($user_id, $email_address)
  {
    $user = Identity::findUserByID($user_id);
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


  /**
   * createLecturerEmployeeRecord
   * @param  string $user_id
   * @param  SignupLecturerForm $signup_model
   * @return Employee
   */
  public static function createEmployeeRecord($user_id, $signup_model)
  {
    $user = Identity::findUserByID($user_id);
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


  /**
   * assignEmployeeToDepartment
   * @param  integer $user_id
   * @param  integer $department_id
   * @return EmployeeDepartment
   */
  public static function assignEmployeeToDepartment($user_id, $department_id)
  {
    $user = Identity::findUserByID($user_id);
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


  /**
   * createLecturerAccount
   * @param  SignupLecturerForm $signup_model
   * @return User
   */
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
      } catch (yii\base\Exception $ex) {
        $transaction->rollBack();
        return null;
      }
    }
    return null;
  }


  /**
   * createCredentialedUserRecord
   * @param  SignupCredentialedUserForm $signup_model
   * @return User|NULL
   */
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


  /**
   * sendWelcomeEmail
   * @param  User $user
   * @param  string $password
   * @return boolean
   */
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


  /**
   * createCredentialedUser
   * @param  SignupCredentialedUserForm $signup_model
   * @return User
   */
  public function createCredentialedUser($signup_model)
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
      } catch (yii\base\Exception $ex) {
        $transaction->rollBack();
        return null;
      }
    }
    return null;
  }


  /**
   * getUserStatus
   * @param  User $user
   * @return string
   */
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
}
