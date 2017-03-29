<?php
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url; 
?>

<div id="user-listing"
    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'label' => 'Username',
                    'format' => 'html',
                    'value' => function($row)
                    {
                        if ($row["user_type"] == "Employee")
                        {
                            return Html::a($row['username'], 
                                        Url::to(['employee/employee-profile', 'personid' => $row['personid']]));
                        }
                        elseif ($row["user_type"] == "Student")
                        {
                            if(strstr(Url::home(true), "localhost") == true)
                            {
                                $url = "http://localhost/sat_dev/frontend/web/index.php?r=subcomponents%2Fstudents%2Fprofile%2Fstudent-profile&personid=" . $row["personid"] . "&studentregistrationid=" . $row["studentregistrationid"];
                            }
                            else
                            {
                                $url = "http://www.svgcc.vc/subdomains/sat/frontend/web/index.php?r=subcomponents%2Fstudents%2Fprofile%2Fstudent-profile&personid=" . $row["personid"] . "&studentregistrationid=" . $row["studentregistrationid"];
                            }
                            return "<a href='$url'>" . $row['username'] . "</a>";
                        }
                    }
                ],
                [
                    'attribute' => 'title',
                    'format' => 'text',
                    'label' => 'Title'
                ],
                [
                    'attribute' => 'first_name',
                    'format' => 'text',
                    'label' => 'First Name'
                ],
                [
                    'attribute' => 'middle_name',
                    'format' => 'text',
                    'label' => 'Middle Name'
                ],
                [
                    'attribute' => 'last_name',
                    'format' => 'text',
                    'label' => 'Last Name'
                ],
                [
                    'attribute' => 'gender',
                    'format' => 'text',
                    'label' => 'Gender'
                ],
                [
                    'attribute' => 'user_type',
                    'format' => 'text',
                    'label' => 'User Type'
                ],
                [
                    'attribute' => 'personid',
                    'format' => 'text',
                    'label' => 'Person ID'
                ],
//                [
//                    'attribute' => 'isactive',
//                    'format' => 'boolean',
//                    'label' => 'Active'
//                ],
            ],
        ]); 
    ?>
</div>
