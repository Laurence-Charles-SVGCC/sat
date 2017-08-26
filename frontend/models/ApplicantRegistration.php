<?php

    namespace frontend\models;

    use Yii;
    use common\models\User;
    use yii\custom\ModelNotFoundException;

    /**
     * This is the model class for table "applicant_registration".
     *
     * @property string $applicantregistrationid
     * @property string $applicantintentid
     * @property string $title
     * @property string $firstname
     * @property string $lastname
     * @property string $email
     * @property string $applicantname
     * @property string $created_at
     * @property string $token
     * @property string $updated_at
     *
     * @property ApplicantIntent $applicantintent
     */
    class ApplicantRegistration extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'applicant_registration';
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['applicantintentid'], 'integer'],
                [['title', 'firstname', 'lastname', 'email'], 'required'],
                [['created_at', 'updated_at'], 'safe'],
                [['title'], 'string', 'max' => 3],
                [['firstname', 'lastname', 'email', 'applicantname'], 'string', 'max' => 45],
                [['token'], 'string', 'max' => 15]
            ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'applicantregistrationid' => 'Applicantregistrationid',
                'applicantintentid' => 'Applicantintentid',
                'title' => 'Title',
                'firstname' => 'Firstname',
                'lastname' => 'Lastname',
                'email' => 'Email',
                'applicantname' => 'Applicantname',
                'created_at' => 'Created At',
                'token' => 'Token',
                'updated_at' => 'Updated At',
            ];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getApplicantintent()
        {
            return $this->hasOne(ApplicantIntent::className(), ['applicantintentid' => 'applicantintentid']);
        }


        public static function getApplicantRegistrationsByYear($acadmeicyearid)
        {
            $users = User::getStudentUsersByYear($acadmeicyearid);
            $registrations = array();
            
            if (empty($users) == true)
            {
                $error_message = "No student user accounts found for AcademicYear ->ID= " . $acadmeicyearid;
                throw new ModelNotFoundException($error_message);
            }
                    
            foreach ($users as $user)
            {
                $id = $user->id;
                $registration = ApplicantRegistration::find()
                        ->where(['applicantname' => $user->username, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                if (empty($users) == NULL)
                {
                    $error_message = "ApplicantRegistration for User -> ID= " .  $id  .  " not found.";
                    throw new ModelNotFoundException($error_message);
                }
                array_push($registrations, $registration);
            }
            
            return $registrations;
        }
        
        
        
    }
