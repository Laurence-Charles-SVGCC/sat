<?php

/* 
 * Author: Laurence Charles
 * Date Created: 04/12/2015
 * Date Last Modified: 04/12/2015
 */

    namespace app\subcomponents\gradebook;
    use \yii\base\Module;

    class GradebookModule extends Module
    {
        public $controllerNamespace = 'app\subcomponents\gradebook\controllers';

        public function init()
        {
            parent::init();

            // custom initialization code goes here
        }
    }

