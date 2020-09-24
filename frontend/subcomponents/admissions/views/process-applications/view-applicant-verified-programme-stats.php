<?php if ($cape == true):?>
  <div class="box box-primary">
    <h3 class="text-center">Current Programme Statistics</h3>
    <table class="table table-condensed">
        <tr>
            <th>Subject</th>
            <th>Offers Made</th>
            <th>Last Intake</th>
        </tr>

        <?php foreach ($capeInfo as $key =>$ci): ?>
            <tr>
                <td> <?= $key ?> </td>
                <td> <?= $ci['offers_made'] ?> </td>
                <td> <?= $ci['capacity'] ?> </td>
            </tr>
        <?php endforeach; ?>
    </table>
  </div>
<?php else: ?>
  <div class="box box-primary">
    <h3 class="text-center">Current Programme Statistics</h3>
    <table class="table table-condensed">
      <thead>
        <tr>
          <th>Programme</th>
          <th>Last Intake</th>
          <th>Conditional Offers</th>
          <th>Full Offers</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td><?= $programme ?></td>
          <td><?= $programmeExpectedIntake ?></td>
          <td><?= $conditionalOffersMade ?></td>
          <td><?= $fullOffersMade ?></td>
        </tr>
      </tbody>
    </table>
  </div>
<?php endif;?>
