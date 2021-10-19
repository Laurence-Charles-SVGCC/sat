<?php

namespace common\models;

use Yii;

class EmployeeModel
{
    public static function findCurrentEmployeeByIdentity()
    {
        $user = Yii::$app->user->identity;
        if ($user == true) {
            return Employee::find()
                ->where(['personid' => $user->personid])
                ->one();
        }
        return null;
    }

    public static function getCurrentEmployeeGender()
    {
        $employee = self::findCurrentEmployeeByIdentity();
        if ($employee == true) {
            return $employee->gender;
        }
        return null;
    }


    public static function getNameWithMiddleName($employee)
    {
        return "{$employee->title} {$employee->firstname} {$employee->middlename} {$employee->lastname}";
    }


    public static function getNameWithoutMiddleName($employee)
    {
        return "{$employee->title} {$employee->firstname} {$employee->lastname}";
    }


    public static function getCurrentEmployeeFullName()
    {
        $employee = self::findCurrentEmployeeByIdentity();
        if ($employee == true && self::hasMiddleName($employee) == true) {
            return self::getNameWithMiddleName($employee);
        } elseif ($employee == true && self::hasMiddleName($employee) == false) {
            return self::getNameWithoutMiddleName($employee);
        }
        return null;
    }


    public static function getEmployeeFullName($id)
    {
        $employee = self::getEmployeeByID($id);

        if ($employee == true && self::hasMiddleName($employee) == true) {
            return self::getNameWithMiddleName($employee);
        } elseif ($employee == true && self::hasMiddleName($employee) == false) {
            return self::getNameWithoutMiddleName($employee);
        }
        return null;
    }


    public static function hasMiddleName($employee)
    {
        if ($employee->middlename == true) {
            return true;
        }
        return false;
    }


    public static function getCurrentEmployeeAvatar()
    {
        $gender = self::getCurrentEmployeeGender();
        if ($gender == null) {
            return "img/logo.png";
        } elseif (in_array($gender, ["f", "female"]) == true) {
            return "img/avatar_female(150_150).png";
        } elseif (in_array($gender, ["m", "male"]) == true) {
            return "img/avatar_male(150_150).png";
        }
    }


    public static function getCurrentEmployeeTitle()
    {
        $user = Yii::$app->user->identity;
        if ($user == true) {
            $title =
                EmployeeTitle::find()
                ->innerJoin(
                    'employee',
                    '`employee_title`.`employeetitleid` = `employee`.`employeetitleid`'
                )
                ->where(['employee.personid' => $user->personid])
                ->all();

            if ($title == true) {
                return $title[0]->name;
            }
        }
        return "Title Pending...";
    }


    public static function getEmployeeByID($id)
    {
        return Employee::find()
            ->where(['personid' => $id, 'isdeleted' => 0])
            ->one();
    }


    public static function userAccountCategory($user)
    {
        if ($user->pword == null) {
            return "Gradebook Account";
        } else {
            return "Full User";
        }
    }


    public static function getEmployeeDepartment($employee)
    {
        $resultset  =
            Department::find()
            ->innerJoin(
                'employee_department',
                '`department`.`departmentid` = `employee_department`.`departmentid`'
            )
            ->where(
                [
                    'department.isactive' => 1, 'department.isdeleted' => 0,
                    'employee_department.personid' => $employee->personid,
                    'employee_department.isactive' => 1,
                    'employee_department.isdeleted' => 0
                ]
            )
            ->all();
        if (!empty($resultset)) {
            return $resultset[0];
        }
        return false;
    }

    public static function getEmployeeDepartmentID($employee)
    {
        $department = self::getEmployeeDepartment($employee);
        if ($department == true) {
            $department->departmentid;
        }
        return null;
    }

    public static function getEmployeeDepartmentName($employee)
    {
        $department = self::getEmployeeDepartment($employee);
        if ($department == true) {
            return $department->name;
        }
        return null;
    }

    public static function getEmployeeDivision($employee)
    {
        $department = self::getEmployeeDepartment($employee);

        if ($department == true) {
            return Division::find()
                ->where(
                    [
                        'divisionid' => $department->divisionid,
                        'isactive' => 1,
                        'isdeleted' => 0
                    ]
                )
                ->one();
        }
        return null;
    }

    public static function getEmployeeDivisionAbbreviation($employee)
    {
        $division = self::getEmployeeDivision($employee);
        if ($division == true) {
            return $division->abbreviation;
        }
        return null;
    }

    public static function getEmployeeDivisionID($employee)
    {
        $division = self::getEmployeeDivision($employee);
        if ($division == true) {
            return $division->divisionid;
        }
        return null;
    }

    public static function getEmployeeDivisionName($employee)
    {
        $division = self::getEmployeeDivision($employee);
        if ($division == true) {
            return $division->name;
        }
        return null;
    }

    public static function getPersonalEmail($employee)
    {
        return Email::find()
            ->where(['personid' => $employee->personid])
            ->one();
    }

    public static function getPersonalEmailAddress($employee)
    {
        $email = self::getPersonalEmail($employee);
        if ($email == true) {
            return $email->email;
        }
        return null;
    }

    public static function getJobTitles()
    {
        return EmployeeTitle::find()
            ->where(['isdeleted' => 0])
            ->orderBy('name ASC')
            ->all();
    }

    public static function getLecturerJobTitles()
    {
        return EmployeeTitle::find()
            ->where(['like', 'name', 'Lecturer'])
            ->andWhere(['isdeleted' => 0])
            ->orderBy('name ASC')
            ->all();
    }

    public static function getAllDepartments()
    {
        return Department::find()
            ->where(['isdeleted' => 0])
            ->orderBy('name ASC')
            ->all();
    }

    public static function getCurrentEmployeeDepartment($id)
    {
        return EmployeeDepartment::find()->where(['personid' => $id])->one();
    }



    public static function loadEmployeeProfile($username)
    {
        $user = UserModel::findUserByUsername($username);
        $employee = self::getEmployeeByID($user->id);

        $model = new EmployeeProfileForm();
        $model->username = $user->username;
        $model->title = $employee->title;
        $model->firstname = $employee->firstname;
        $model->middlenames = $employee->middlename;
        $model->lastname = $employee->lastname;
        $model->gender = $employee->gender;
        $model->job_title = $employee->employeetitleid;
        $model->personal_email = self::getPersonalEmailAddress($employee);
        $model->institutional_email = $user->email;
        $model->division = self::getEmployeeDivisionID($employee);
        $model->department = self::getEmployeeDepartmentID($employee);
        $model->date_of_birth = $employee->dateofbirth;
        $model->marital_status = $employee->maritalstatus;
        $model->nationality = $employee->nationality;
        $model->place_of_birth = $employee->placeofbirth;
        $model->religion = $employee->religion;
        $model->nationalid_number = $employee->nationalidnumber;
        $model->nis_number = $employee->nationalinsurancenumber;
        $model->ird_number = $employee->inlandrevenuenumber;
        return $model;
    }

    public static function loadEmployeeProfileFormIntoEmployee(
        $employee,
        $employee_form
    ) {
        $employee->employeetitleid = $employee_form->job_title;
        $employee->title = $employee_form->title;
        $employee->firstname = $employee_form->firstname;
        $employee->middlename = $employee_form->middlenames;
        $employee->lastname = $employee_form->lastname;
        $employee->gender = $employee_form->gender;
        $employee->dateofbirth = $employee_form->date_of_birth;
        $employee->maritalstatus = $employee_form->marital_status;
        $employee->nationality = $employee_form->nationality;
        $employee->placeofbirth = $employee_form->place_of_birth;
        $employee->religion = $employee_form->religion;
        $employee->nationalidnumber = $employee_form->nationalid_number;
        $employee->nationalinsurancenumber = $employee_form->nis_number;
        $employee->inlandrevenuenumber = $employee_form->ird_number;

        return $employee;
    }

    public static function applyEmployeeFormUpdates(
        $employee_form,
        $user,
        $employee,
        $personal_email
    ) {
        $records_modified = false;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            /****************     update user model fields     **********************/
            $user_modified = false;
            if ($employee_form->username != $user->username) {
                $user->username = $employee_form->username;
                $user_modified = true;
            }

            if ($employee_form->institutional_email != $user->email) {
                $user->email = $employee_form->institutional_email;
                $user_modified = true;
            }

            if ($user_modified == true && $user->save() == false) {
                $transaction->rollBack();
                return false;
            }
            /****************     update Email model fields     ************************/
            if ($employee_form->personal_email != $personal_email->email) {
                $personal_email->email = $employee_form->institutional_email;
                if ($personal_email->save() == false) {
                    $transaction->rollBack();
                    return false;
                }
            }
            /*************     update EmployeeDepartment model fields     **************/
            $current_department = self::getCurrentEmployeeDepartment($employee->personid);
            if (
                $employee_form->department == true
                && $employee_form->department != $current_department->departmentid
            ) {
                $current_department->isactive = 0;
                $current_department->isdeleted = 1;
                if ($current_department->save() == false) {
                    $transaction->rollBack();
                    return false;
                }
                $new_department = new EmployeeDepartment();
                $new_department->personid = $employee->personid;
                $new_department->departmentid = $employee_form->department;
                if ($new_department->save() == false) {
                    $transaction->rollBack();
                    return false;
                }
            }
            /****************     update Employee model fields     ******************/
            $employee =
                self::loadEmployeeProfileFormIntoEmployee($employee, $employee_form);

            if ($employee->save() == false) {
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        } catch (yii\base\Exception $ex) {
            $transaction->rollBack();
            return false;
        }
        return false;
    }

    public static function getAllEmployees()
    {
        return Employee::find()->all();
    }

    public static function generateEmployeeUsername($year, $divisionid)
    {
        $last_user = User::find()->orderBy('personid DESC', 'desc')->one();
        if ($last_user == false) {
            return false;
        }

        //150 used to prevent username clashes with the users already entered on eCampus.
        $num = $last_user ? strval($last_user->personid + 1) : 150;
        while (strlen($num) < 4) {
            $num = "0" . $num;
        }

        $year_component = substr($year, 2, 2);
        $division_component = "0" . $divisionid;
        $username = $year_component . $division_component . $num;
        if (strlen($username) == 8) {
            return $username;
        }

        return false;
    }

    public static function getFormattedEmployeeListing()
    {
        $employees =
            Employee::find()
            ->where(['isactive' => 1, 'isdeleted' => 0])
            ->orderBy('lastname')
            ->all();

        $keys = array();
        array_push($keys, '');

        $values = array();
        array_push($values, 'Select Employee...');

        foreach ($employees as $employee) {
            $role =
                AuthAssignment::find()
                ->where(['user_id' => $employee->personid])
                ->one();

            if ($role == true && $role->item_name == "System Administrator") {
                continue;
            } else {
                array_push($keys, $employee->personid);
                array_push($values, self::getEmployeeFullName($employee->personid));
            }
        }
        return array_combine($keys, $values);
    }

    public static function getEmployeeTitle($id)
    {
        $titles =
            EmployeeTitle::find()
            ->innerJoin(
                'employee',
                '`employee_title`.`employeetitleid` = `employee`.`employeetitleid`'
            )
            ->where(['employee.personid' => $id])
            ->all();

        if (!empty($titles)) {
            return $titles[0]->name;
        }
        return null;
    }

    public static function getEmployeeName($id)
    {
        $employee = Employee::find()
            ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
            ->one();

        if ($employee  == true) {
            return "{$employee->title} {$employee->firstname} {$employee->lastname}";
        }
        return false;
    }

    public static function updateEmployeeTitleOfUser($user, $employee_title_name)
    {
        if ($user == true && $employee_title_name == true) {
            $employee = self::getEmployeeByID($user->personid);

            $employee_title =
                EmployeeTitle::find()
                ->where(['name' => $employee_title_name])
                ->one();

            if ($employee == true && $employee_title == true) {
                $employee->employeetitleid = $employee_title->employeetitleid;
                if ($employee->save() == true) {
                    return $employee;
                }
            }
        }
        return null;
    }
}
