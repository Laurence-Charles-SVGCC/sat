<?php
    namespace frontend\components;
    
    use Yii;
    use frontend\models\ApplicationSettings;
    use yii\base\Behavior;
    
    class CheckApplicationSettings extends Behavior
    {
        public function events()
        {
            return [
                \yii\web\Application::EVENT_AFTER_REQUEST => 'checkApplicationSettings',
            ];
        }
        
        
        public function checkApplicationSettings()
        {
            $route = Yii::$app->urlManager->parseRequest(Yii::$app->request)[0];
            if ($route != 'site/offline-login')
            {
                $settings = ApplicationSettings::getApplicationSettings();
                if ($settings)
                {
                    if (\Yii::$app->user->isGuest == true)
                    {

                        if ( $settings->is_online == false  && $route != 'site/under-maintenance')
                        {
                            \Yii::$app->getResponse()->redirect(['site/under-maintenance']);
                        }
                    }
                    else
                    {
                        if (Yii::$app->user->can('System Administrator') == false  && $route != 'site/under-maintenance')
                        {
                            if ( $settings->is_online == false)
                            {
                                \Yii::$app->getResponse()->redirect(['site/under-maintenance']  && $route != 'site/under-maintenance');
                            }
                        }
                        else
                        {
                            if ( $settings->allow_administrator == false  && $route != 'site/under-maintenance')
                            {
                                \Yii::$app->getResponse()->redirect(['site/under-maintenance']);
                            }
                        }
                    }
                }
                else
                {
                    if ($route != 'site/under-maintenance')
                    {
                        \Yii::$app->getResponse()->redirect(['site/under-maintenance']);
                    }
                }
            }
        }
        
        
        
        
    }
     


?>
