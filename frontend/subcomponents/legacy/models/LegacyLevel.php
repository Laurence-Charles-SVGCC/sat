<?php
    namespace frontend\subcomponents\legacy\models;
    
    use yii\helpers\Url;
    use yii\helpers\Html;
    use Yii;

    /**
     * This is the model class for table "legacy_level".
     *
     * @property string $legacylevelid
     * @property string $name
     * @property integer $isactive
     * @property integer $isdeleted
     *
     * @property LegacyBatch[] $legacyBatches
     */
    class LegacyLevel extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'legacy_level';
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['name'], 'required'],
                [['isactive', 'isdeleted'], 'integer'],
                [['name'], 'string', 'max' => 100]
            ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'legacylevelid' => 'Legacylevelid',
                'name' => 'Name',
                'isactive' => 'Isactive',
                'isdeleted' => 'Isdeleted',
            ];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLegacyBatches()
        {
            return $this->hasMany(LegacyBatch::className(), ['legacylevelid' => 'legacylevelid']);
        }
        
        
        /**
         * 
         * @return type
         * 
         * Author: charles.laurence1@gmail.com
         * Creted: 2018_06_04
         * Modified: 2018_06_05
         */
        public static function getActiveLevels()
        {
            return LegacyLevel::find()
                    ->where(['isactive' => 1, 'isdeleted' => 0])
                    ->orderBy('name ASC')
                    ->all();
        }
        
        
        /**
         * Determines available action
         * 
         * @param type $id
         * @return string
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2018_06_05
         * Modified: 2018_06_05
         */
        public function determineAvailableAction()
        {
            if ($this->isactive == 1 && $this->isdeleted == 0)
            {
                return "delete";
            }
            elseif ($this->isactive == 0 && $this->isdeleted == 1)
            {
                return "restore";
            }
            else
            {
                return "none";
            }
        }
        
        
        /**
         * Generates appropiraite control button for record
         * 
         * @param type $id
         * @return type
         * 
         *  Author: charles.laurence1@gmail.com
         *  Created: 2018_06_05
         *  Modified: 2018_06_05
         */
        public static function genrateUIAction($id)
        {
            $level = LegacyLevel::findOne(['legacylevelid' => $id]);
            $available_action = $level->determineAvailableAction();
            if ($available_action == "delete")
            {
                return Html::a(' ', 
                        Url::toRoute(['/subcomponents/legacy/level/delete-level', 'id' => $id]), ['class' => 'btn btn-danger glyphicon glyphicon-remove']);
            }
            elseif ($available_action == "restore")
            {
                return Html::a(' ', 
                        Url::toRoute(['/subcomponents/legacy/level/restore-level', 'id' => $id]), ['class' => 'btn btn-success glyphicon glyphicon-refresh']);
            }
            else
            {
                "N/A";
            }
        }
        
        
    }
    
?>
