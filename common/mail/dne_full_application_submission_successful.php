<?php
    use yii\helpers\Html;
    use yii\web\UrlManager;
    use yii\helpers\Url;
?>

<div>
    <span>Dear <?= $applicant->getFullName();?>,</span><br/><br/>
    
    <p>
        You have successfully submitted your application for entry into the St. Vincent 
        and the Grenadines Community College. Please check your email periodically for 
        a response from the institution.
    </p>
    
    <div>
        <span>Below you can find summary of your programme choices;</span><br/>
        <table border = "1" style="width:95%">
            <tr>
                <th colspan='3'>ApplicantID/Username - <?= $username ;?></th>
            </tr>

            <tr>
                <th style="text-align: center;">Preference</th>
                <th style="text-align: center;">Programme</th>
                <th style="text-align: center;">Division</th>
            </tr>

            <?php foreach($application_records as $key=>$application): ?>
                <?php $temp = new \NumberFormatter('en_US', \NumberFormatter::ORDINAL);?>
                <tr>
                    <td><?= $application["ordering"] ?></td>
                    <td><?= $application["programme"] ?></td>
                    <td><?= $application["division"] ?></td>
                </tr>
            <?php endforeach;?>
        </table>
    </div><br/><br/>
    
    <div>
        <span>To complete application process, you must complete the instructions that follow;</span>
        
        <ol>
            <li>Log out of the SVGCC online application system.</li>
            <li>Print the application submission confirmation email.</li>
            <li>Bring the printed copy of the confirmation email and the $20 application fee to the SVGCC campus in Villa to make payment.</li>
            <li>Pay Application Fee while presenting the following supporting documents;</li>
                <ul>
                    <li>Certified Copy of Birth certificate</li>
                    <li>Certified Copy of Marriage Certificate (<i>where applicable</i>)</li>
                    <li>Transcripts (<i>where applicable</i>)</li>
                    <li>
                        Clear, certified photocopies of qualifications if;
                        <ul type="square">
                            <li>you sat CSEC examinations prior to 2010.</li>
                            <strong style="color:black">OR</strong>
                            <li>you sat any CSEC examinations during the January testing period.</li>
                            <strong style="color:black">OR</strong>
                            <li>you requested a review of your results and this review resulted in a modification of your results.</li>
                        </ul>
                    </li>
                    <li>A recent certified passport sized picture</li>
                    <li>Two (2) recent testimonials.</li>
                    <li>Police Record</li>
                </ul>
       </ol>
    </div>
    
    <div>
        <strong>Kind regards,<br />
        SVGCC</strong><br />
    </div>
    
    <p>
        * This e-mail is intended only for the address named above. As this e-mail may contain confidential or privileged information,
        if you are not the named address, you are not authorised to retain,  read, copy or disseminate this message or any part of it.
    </p>
</div>
