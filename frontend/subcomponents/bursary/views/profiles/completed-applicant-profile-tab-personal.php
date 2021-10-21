<div role="tabpanel" class="tab-pane" id="completed-applicant-personal">
  <div class="panel panel-default">
    <div class="panel-heading">General</div>

    <div class="panel-body">
      <table class="table table-hover">
        <tr>
          <td rowspan="3">
            <img src=<?= $displayPicture; ?> alt="avatar" class="img-rounded">
          </td>
          <th>Username</th>
          <td><?= $username; ?></td>
          <th>Applications</th>
          <td>
            <?php foreach ($applicationDetails as $detail) : ?>
              <?= "{$detail['ordering']} - {$detail['name']}"; ?><br />
            <?php endforeach; ?>
          </td>
        </tr>

        <tr>
          <th>Date Of Birth</th>
          <td><?= $applicant->dateofbirth; ?></td>
          <th>Gender</th>
          <td><?= $applicant->gender; ?></td>
        </tr>

        <tr>
          <th>Nationality</th>
          <td><?= $applicant->nationality; ?></td>
          <th>Place Of Birth</th>
          <td><?= $applicant->placeofbirth; ?></td>
        </tr>

        <tr>
          <td></td>
          <th>Sponsor's Name</th>
          <td><?= $applicant->sponsorname; ?></td>
          <th>Beneficiary Information</th>
          <td><?= $beneficiaryDetails; ?></td>
        </tr>
      </table>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">Contact Details</div>
    <div class="panel-body">
      <table class="table table-hover">
        <tr>
          <td></td>
          <th>Home Phone</th>
          <td><?= $phone->homephone; ?></td>
          <th>Cell Phone</th>
          <td><?= $phone->cellphone; ?></td>
        </tr>

        <tr>
          <td></td>
          <th>Work Phone</th>
          <td><?= $phone->workphone; ?></td>
          <th>Personal Email</th>
          <td><?= $personalEmail; ?></td>
        </tr>

        <tr>
          <td></td>
          <th>Institutional Email</th>
          <td><?= $institutionalEmail; ?></td>
          <th></th>
          <td></td>
        </tr>
      </table>
    </div>
  </div>
</div>