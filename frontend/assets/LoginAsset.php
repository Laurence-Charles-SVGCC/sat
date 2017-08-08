<?php
    namespace frontend\assets;

    use yii\web\AssetBundle;

    class LoginAsset extends AssetBundle
    {
        public $basePath = '@webroot';
        public $baseUrl = '@web';
        public $css = [
            'css/login/animate.css',
            'css/login/style.css',
        ];
        public $js = [
        ];
        public $depends = [
        ];
    }
