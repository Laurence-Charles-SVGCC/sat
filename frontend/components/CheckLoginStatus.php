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
            $exempted_routes = ['site/index', 'site/request-password-reset', 'site/reset-password'];

//            if (Yii::$app->user->isGuest == true 
//                    && $route != 'site/index'  
//                    && $route != 'site/request-password-reset'
//                    && $route != 'site/reset-password') 
            if (Yii::$app->user->isGuest == true && in_array($route, $exempted_routes) == false) 
            {
                Yii::$app->getResponse()->redirect(['site/index']);
            }
        }
        
        
        
        
    }
     


?>
