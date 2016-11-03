<?php
    namespace backend\models;

    use Yii;
    use yii\base\Model;

    class AssignEmployeePassword extends Model
    {
        public $userid;
        public $password;
        public $confirm_password;

        
        /**
         * @inheritdoc
         * 
         * Author: Laurence Charles
         * Date Created: 02/11/2016
         * Date Last Modified: 02/11/2016
         */
        public function rules()
        {
            return [
                [['userid', 'password', 'confirm_password'], 'required'],     
                [['userid'], 'integer'],
                [['password', 'confirm_password'], 'string', 'min' => 8],
                [['password', 'confirm_password'], 'string', 'max' => 20],
                [['confirm_password'], 'compare', 'compareAttribute'=>'password','message'=>'Passwords do not match']
            ];
        }

        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'userid' => 'UserID',
                'password' => 'Password',
                'confirm_password' => 'Confirm Password',
            ];    
        }
        
}
