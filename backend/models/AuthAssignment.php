<?php
    namespace backend\models;
    use backend\models\AuthItem;

    /**
     * This is the model class for table "auth_assignment".
     *
     * @property string $item_name
     * @property string $user_id
     * @property integer $created_at
     *
     * @property AuthItem $itemName
     */
    class AuthAssignment extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'auth_assignment';
        }


        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['item_name', 'user_id'], 'required'],
                [['created_at'], 'integer'],
                [['item_name', 'user_id'], 'string', 'max' => 64]
            ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'item_name' => 'Item Name',
                'user_id' => 'User ID',
                'created_at' => 'Created At',
            ];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getItemName()
        {
            return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
        }

        
        // (laurence_charles) - Generates list of user roles and teir associated descriptions
        public static function getUserRoleDetails($personid)
        {
            $role_details = array();
            
            $role_assignments = AuthAssignment::find()
                    ->innerJoin('auth_item' , '`auth_assignment`.`item_name` = `auth_item`.`name`')
                    ->where(['auth_assignment.user_id' => $personid, 'auth_item.type' => 1])
                    ->all();
            if(empty($role_assignments) == false)
            {
                foreach ($role_assignments as $record) 
                {
                    $combined = array();
                    $keys = array();
                    $values = array();
                    array_push($keys, "name");
                    array_push($keys, "description");
                    $k1 = strval($record->item_name);

                    $description = AuthItem::find()
                             ->where(['name' => $record->item_name])
                            ->one()
                            ->description;
                    $k2 = strval($description);
                    array_push($values, $k1);
                    array_push($values, $k2);
                    $combined = array_combine($keys, $values);
                    array_push($role_details, $combined);
                }
            }
            return $role_details;
        }
        
        
    }
