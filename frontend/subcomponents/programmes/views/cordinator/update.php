<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;

    $this->title = 'Update Co-ordinator';
    $this->params['breadcrumbs'][] = ['label' => 'Co-ordinator Dashboard', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-index">
        <div class = "custom_wrapper">
            <div class="custom_header">
                <a href="<?= Url::toRoute(['/subcomponents/programmes/cordinators/index']);?>" title="Manage Co-ordinators">     
                    <img class="custom_logo_students" src ="<?=Url::to('../images/programme.png');?>" alt="scroll avatar">
                    <span class="custom_module_label" > Welcome to the Co-ordinator Management System</span> 
                    <img src ="<?=Url::to('../images/programme.png');?>" alt="scroll avatar" class="pull-right">
                </a>    
            </div>
            
            <div class="custom_body">  
                <h1 class="custom_h1"><?=$this->title?></h1>
                
                <br/>
                <div style="width:80%; margin: 0 auto; font-size: 20px;">

                    <?php 
                        $form = ActiveForm::begin([
                            'id' => 'update-cordinator',
                            'options' => [
                                 'class' => 'form-layout',
                            ]
                        ]) 
                    ?>
                        
                        
                    
                        <br/>
                        <?= Html::a(' Cancel',
                                    ['cordintor/index'],
                                    ['class' => 'btn btn-danger glyphicon glyphicon-remove-circle', 'style' => 'width:20%; margin-left:55%;margin-right:2.5%']
                                    );
                        ?>
                        <?= Html::submitButton(' Save', ['class' => 'btn btn-success glyphicon glyphicon-ok', 'style' => 'width:20%;']);?>
                <?php ActiveForm::end() ?>
            </div>
        </div>
</div>

