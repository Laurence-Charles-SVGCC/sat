<div>
  <div>
    <p><?= date("l F j, Y"); ?></p>
    <p>Dear <?= $first_name . ' ' . $last_name ?>,</p>

    <p>
      We are pleased to inform you that your application to the St. Vincent and
      the Grenadines Community College has been successful. You are offered a
      place in the <?= $programme; ?> Programme at the <?= $division_name ?>
      commencing on <?= $package->commencementdate?>.<br/>
    </p>

    <p>
      <strong>
        Your Student Number is: <?= $studentno; ?>.
      </strong>
    </p>
  </div>

  <div style="white-space: pre;">
    <?= $package->emailcontent?>
  </div>

  <div>
    <p>With warm wishes and kind regards,</p>
    <?php if (stripos(Url::home(true), "localhost") == false) :?>
       <p>
         <img src="https://sat.svgcc.vc/images/signature.png"
         alt="mrs-rouse-signature">
       </p>
    <?php else: ?>
         <p>
           <img src="http://localhost/sat_dev/frontend/web/img/signature.png"
           alt="mrs-rouse-signature">
         </p>
    <?php endif; ?>

    <p>
      Samantha Minors-Rouse<br/>
      Registrar
    </p><br/>

    <div style="white-space: pre;">
      <?= $package->disclaimer?>
    </div>
  </div>
</div>
