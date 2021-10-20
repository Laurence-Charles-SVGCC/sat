<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <?php if (stripos(Url::home(true), "localhost") == false) : ?>
        <img src="https://sat.svgcc.vc/images/email_header.png" alt="email_header" class="img-rounded" style="max-width:700px; max-height:150px" />
    <?php else : ?>
        <img src="http://localhost:8888/sat_dev/frontend/web/img/email_header.png" alt="email_header" class="img-rounded" style="max-width:700px; max-height:150px" />
    <?php endif; ?>

    <?= $content ?>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>