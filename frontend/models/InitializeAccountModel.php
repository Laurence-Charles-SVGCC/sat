<?php

    namespace frontend\models;

    use Yii;
    use yii\base\Model;

    /**
     * Model used for the initialization of a student account.
     */
    class InitializeAccountModel extends Model
    {
        public $email;
        public $pword;
        public $confirm;
        public $title;
        public $firstname;
        public $middlename;
        public $lastname;

        /**
         * @inheritdoc
         *
         * Author: Laurence Charles
         * Date Created: 27/05/2016
         * Date Last Modified: 28/05/2016
         *
         */
        public function rules()
        {
            return [
                [['email', 'pword', 'confirm', 'title', 'firstname', 'middlename', 'lastname'], 'required'],
                ['email', 'email'],
                [['pword', 'confirm'], 'string', 'min' => 8],
                [['pword', 'confirm'], 'string', 'max' => 20],
                [['confirm'], 'compare', 'compareAttribute'=>'pword','message'=>'Passwords do not match'],
                [['title'], 'string', 'max' => 3],
                [['firstname', 'middlename', 'lastname', 'email'], 'string', 'max' => 45],
            ];
        }


        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'email' => 'Email',
                'pword' => 'Password',
                'confirm' => 'Confirm Password',
                'title' => 'Title',
                'firstname' => 'First Name',
                'middlename' => 'Middle Name',
                'lastname' => 'Last Name',
            ];
        }
    }
