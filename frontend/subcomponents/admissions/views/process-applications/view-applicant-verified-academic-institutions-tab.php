<div role="tabpanel" class="tab-panel" id="institutions-attended">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4>Secondary Institutions</h4>
    </div>

    <table class="table table-hover">
      <tr>
        <th>Name</th>
        <th>Year Of Graduation</th>
      </tr>

      <?php foreach ($secondaryAttendances as $attendance) : ?>
        <tr>
          <td><?= $attendance->institutionName ?></td>
          <td><?= $attendance->yearOfGraduation ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>