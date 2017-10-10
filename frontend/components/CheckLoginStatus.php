<?php
    namespace frontend\components;
    
    use Yii;
    use frontend\models\ApplicationSettings;
    use yii\base\Behavior;
    
    class CheckLoginStatus extends Behavior
    {
        public function events()
        {
            return [
                \yii\web\Application::EVENT_AFTER_REQUEST => 'checkLoginStatus',
            ];
        }
        
        
        public function checkLoginStatus()
        {
            $route = Yii::$app->urlManager->parseRequest(Yii::$app->request)[0];

            if (Yii::$app->user->isGuest == true && $route != 'site/index') 
            {
                Yii::$app->getResponse()->redirect(['site/index']);
            }
        }
        
        
        
        
    }
     


?>
