<?php

namespace common\models;

use Yii;
use common\controllers\DatabaseWrapperController;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\db\Expression;

/**
 * User model
 *
 * @property integer $personid
 * @property string $username
 * @property string $pword
 * @property string $p_word
 * @property string $email
 *
 * @property string $auth_key
 * @property integer $isactive
 * @property integer $isdeleted
 * @property integer $created_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        // return '{{%person}}';
        return 'person';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'datecreated',
                'updatedAtAttribute' => 'dateupdated',
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['isactive', 'default', 'value' => self::STATUS_ACTIVE],
            ['isactive', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['persontypeid', 'username'], 'required'],
            [['persontypeid', 'isactive', 'isdeleted'], 'integer'],
            [['datecreated', 'dateupdated'], 'safe'],
            [['salt', 'resettoken', 'email', "p_word"], 'string', 'max' => 255],
            [['pword', 'p_word'], 'string', 'min' => 8],
            [['username'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['personid' => $id, 'isactive' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username, 'isactive' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'resettoken' => $token,
            'isactive' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        // if (empty($token)
        if ($token == null) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return ($timestamp + $expire) >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->salt;
        //return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->pword);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->pword = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->resettoken = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->resettoken = null;
    }

    /**
     *
     *
     * @param string $type_name
     */
    public function setPersonTypeID($type_name)
    {
        $this->persontypeid = DatabaseWrapperController::getPersonTypeID($type_name);
    }

    /**
     *
     *
     * @param string $type_name
     */
    public function setSalt()
    {
        $this->salt = Yii::$app->security->generateRandomString();
    }


    /**
     * Returns a user record
     *
     * @param type $personid
     * @return User | NULL
     *
     * Author: Laurence Charles
     * Date Created: 2015_12_20
     * Date Last Modified: 2017_08_26
     */
    public static function getUser($personid)
    {
        $user = User::find()
            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
            ->one();
        return $user;
    }

    // (laurence_charles) - Returns full name of user or employee
    public static function getFullName($personid)
    {
        $full_name = false;

        $user = User::find()
            ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
            ->one();
        if ($user == true) {
            if ($user->persontypeid == 2) { //if student
                $student = Student::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
                if ($student) {
                    if ($student->middlename == false) {
                        $full_name = $student->title . ". " . $student->firstname . " " . $student->middlename . " " . $student->lastname;
                    } elseif ($student->middlename == true) {
                        $full_name = $student->title . ". " . $student->firstname . " " . $student->lastname;
                    }
                }
            } elseif ($user->persontypeid == 3) { //if employee
                $employee = Employee::find()
                    ->where(['personid' => $personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
                if ($employee) {
                    if ($employee->middlename == false) {
                        $full_name = $employee->title . ". " . $employee->firstname . " " . $employee->middlename . " " . $employee->lastname;
                    } elseif ($employee->middlename == true) {
                        $full_name = $employee->title . ". " . $employee->firstname . " " . $employee->lastname;
                    }
                }
            }
        }
        return $full_name;;
    }


    // (laurence_charles) - Return collection or user records based on $user_type
    public static function getUsers($user_type = null)
    {
        if ($user_type == null) {
            $users = User::find()
                ->where(['persontypeid' => [2, 3], 'isactive' => 1, 'isdeleted' => 0])
                ->all();
        } elseif ($user_type == 2) {
            $users = User::find()
                ->where(['persontypeid' => 2, 'isactive' => 1, 'isdeleted' => 0])
                ->all();
        } elseif ($user_type == 3) {
            $users = User::find()
                ->where(['persontypeid' => 3, 'isactive' => 1, 'isdeleted' => 0])
                ->all();
        }

        return $users;
    }


    public function getUserDivision()
    {
        $divisions =
            Division::find()
            ->innerJoin(
                'department',
                '`division`.`divisionid` = `department`.`divisionid`'
            )
            ->innerJoin(
                'employee_department',
                '`department`.`departmentid` = `employee_department`.`departmentid`'
            )
            ->where([
                'division.isactive' => 1,
                'division.isdeleted' => 0,
                'department.isactive' => 1,
                'department.isdeleted' => 0,
                'employee_department.personid' => $this->personid,
                'employee_department.isactive' => 1,
                'employee_department.isdeleted' => 0
            ])
            ->all();
        if (!empty($divisions)) {
            return $divisions[0]->divisionid;
        }
        return false;
    }



    /**
     * Return collection of student user accounts that are associated with a particular academis year
     *
     * @return [User] | []
     *
     * Author: Laurence Charles
     * Date Created: 2017_07_25
     * Date Last Modified: 2017_08_26
     */
    public static function getStudentUsersByYear($acadmeicyearid)
    {
        $cond = array();
        $cond['person.isactive'] = 1;
        $cond['person.isdeleted'] = 0;
        $cond['application.isactive'] = 1;
        $cond['application.isdeleted'] = 0;
        $cond['academic_offering.isactive'] = 1;
        $cond['academic_offering.isdeleted'] = 0;
        $cond['academic_offering.academicyearid'] = $acadmeicyearid;

        $users = User::find()
            ->innerJoin('`application`', '`person`.`personid` = `application`.`personid`')
            ->innerJoin('`academic_offering`', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
            ->where($cond)
            ->groupby('person.personid')
            ->all();

        return $users;
    }


    public static function generateEmployeeUsername()
    {
        $formattedYear = "14";
        $currentYear = date('Y');
        $firstSemesterMonths = ["09", "10", "11", "12"];
        $currentMonth = date('m');
        if (in_array($currentMonth, $firstSemesterMonths) == true) {
            $formattedYear = substr($currentYear, 2, 2);
        } else {
            $yearAsInteger = intval($currentYear) - 1; //to coincide with academic year
            $formattedYear = substr(strval($yearAsInteger), 2, 2);
        }

        $formattedDivision = "01";

        $lastUserID =
            User::find()
            ->orderBy('personid DESC', 'desc')
            ->one()
            ->personid;

        $newUserID = $lastUserID + 1;

        $autoGeneratedID =
            str_pad(strval(($newUserID % 10000)), 4, '0', STR_PAD_LEFT);

        return "{$formattedYear}{$formattedDivision}{$autoGeneratedID}";
    }

    public function isVisibleToUser($userID)
    {
        return false;
    }
}
