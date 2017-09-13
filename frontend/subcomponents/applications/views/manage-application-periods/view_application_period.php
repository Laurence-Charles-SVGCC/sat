 <?php
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    
    use frontend\models\Club;
    
    $this->title = 'Clubs Control Panel';
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header text-center no-padding">
    <a href="<?= Url::toRoute(['/subcomponents/registry/clubs/manage-clubs']);?>" title="Manage Clubs">
        <h1>Welcome To The Club Management System</h1>
    </a>
</div>

<section class="content-header">
    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
</section><br/><br/>

<div class="box box-primary table-responsive no-padding" style="font-size:1.1em">
    <div class="box-header with-border">
        <span class="box-title"><?= $this->title?></span>
     </div>
    
    <div class="box-body">  
        <div class="panel panel-default">
            <div class="panel-heading" style="color:green;font-weight:bold; font-size:1.3em">Clubs Listing
                <?php if(Yii::$app->user->can('createClub')):?>
                    <a class="btn btn-success glyphicon glyphicon-plus pull-right" href=<?=Url::toRoute(['/subcomponents/registry/clubs/configure-club', 'action' => 'create']);?> role="button"> Create Club</a>
                <?php endif;?>
            </div>

            </br>
            <?php if($clubs == false):?>
                <p><strong>No clubs have been created</strong></p>

            <?php else:?>
                <table class='table table-condensed' style='margin: 0 auto;'>
                    <?php foreach($clubs as $club):?>
                        <tr>
                            <th rowspan='3' style='vertical-align:top; text-align:center; font-size:1.2em; width:20%'><?=$club->name?></th>
                            <th>Founded</th>
                            <td><?=$club->yearfounded;?></td>
                            <th>Description</th>
                            <td><?=$club->description?></td>
                        </tr>

                        <tr>
                            <th>Motto</th>
                            <td colspan='3' ><?=$club->motto;?></td>
                        </tr>

                        <tr>
                            <th>Division</th>
                            <td><?=Club::getDivision($club->clubid);?></td>

                            <th>Action</th>
                            <td>
                                <div class='dropdown'>
                                    <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                                        Select your intended action
                                        <span class='caret'></span>
                                    </button>
                                    <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                                        <?php if(Yii::$app->user->can('editClub')):?>
                                            <li><a href=<?=Url::toRoute(['/subcomponents/registry/clubs/configure-club', 'action' => 'edit', 'recordid' => $club->clubid])?>>Edit</a></li>
                                        <?php endif;?>

                                        <?php if(Club::hasMembers($club->clubid) == false  && Yii::$app->user->can('deleteClub')):?>    
                                            <li><a href=<?=Url::toRoute(['/subcomponents/registry/clubs/delete-club', 'recordid' => $club->clubid])?>>Delete Club</a></li>
                                        <?php endif;?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </table>
            <?php endif;?>
        </div>
    </div><br/>
 </div>