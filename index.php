<?php
$config = include('config.php');
include_once('controller.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css"
        integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

  <title>Pivo's Spooktochten</title>
</head>
<body>
<div class="container">
  
  <h1>Pivo's spooktochten</h1>
    
  <?php if (isset($notice)): ?>
    <div class="alert alert-<?= $notice['type'] ?>" role="alert">
        <?= $notice['text']; ?>
    </div>
  <?php endif; ?>
  
  <p>Op zaterdag 18 november organiseren de pivo's een spooktocht voor alle leden van Scouting Laurentius en hun
    broertjes, zusjes, vrienden en vriendinnen.</p>

  <h2>Onderbouw spooktocht</h2>
  <div id="accordion-ob" role="tablist">
    <?php foreach ($config['times']['onderbouw'] as $key => $time): ?>
    <?php $available = $config['maxGroupSize'] - subscriptionCount($time); ?>
    <div class="card">
      <div class="card-header" role="tab" id="heading-ob-<?= $key ?>">
        <h5 class="mb-0">
          <a data-toggle="collapse" href="#collapse-ob-<?= $key ?>" aria-expanded="true" aria-controls="collapse-ob-<?= $key ?>">
            <?= $time ?> uur
            <?php if ($available > 0): ?>
              <span class="badge badge-primary badge-success"><?= $available ?> plaatsen vrij</span>
            <?php else: ?>
              <span class="badge badge-primary badge-danger">vol</span>
            <?php endif; ?>
          </a>
        </h5>
      </div>

      <div id="collapse-ob-<?= $key ?>" class="collapse" role="tabpanel" aria-labelledby="heading-ob-<?= $key ?>"
           data-parent="#accordion-ob">
        <div class="card-body">
          <h3>Huidige inschrijvingen</h3>
          <?php if ($available == $config['maxGroupSize']): ?>
          <em>Nog geen inschrijvingen</em>
          <?php else: ?>
          <ol>
            <?php foreach (getSubscriptions($time) as $subscription): ?>
              <?php foreach ($subscription->getNames() as $name): ?>
                <li><?= $name; ?></li>
              <?php endforeach; ?>
            <?php endforeach; ?>
          </ol>
          <?php endif; ?>
    
          <?php if ($available > 0): ?>
          <p>
            <a class="btn btn-primary" data-toggle="collapse" href="#subscribe-ob-<?= $key ?>" aria-expanded="false"
               aria-controls="subscribe-ob-<?= $key ?>">
              Inschrijven
            </a>
          </p>
          <div class="collapse" id="subscribe-ob-<?= $key ?>">
            <div class="card card-body">
              <form class="hidden-print" method="post">
                <h3>Inschrijfformulier</h3>
                <input type="hidden" name="action" value="subscribe">
                <input type="hidden" name="time" value="<?= $time ?>">
                <input type="hidden" name="max-participants" value="<?= $available ?>">
                <div class="form-group">
                  <label for="name-ob-<?= $key ?>">Deelnemers</label>
                  <div class="participant-container">
                    <input type="text" required class="form-control" name="name[]" id="name-ob-<?= $key ?>"
                           placeholder="Naam deelnemer 1">
                  </div>
                  <button type="button" class="btn form-control btn-secondary add-participant">Deelnemer toevoegen (max
                    <?= $available ?>)</button>
                </div>
                <div class="form-group">
                  <label for="speltak-ob-<?= $key ?>">Speltak</label>
                  <select name="speltak" required class="form-control" id="speltak-ob-<?= $key ?>">
                    <option selected disabled hidden value="">Kies een speltak</option>
                    <option>Bevers</option>
                    <option>Jacala welpen</option>
                    <option>Rikki Tikki Tavi welpen</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="email-ob-<?= $key ?>">E-mailadres inschrijver</label>
                  <input type="email" name="email" required class="form-control" id="email-ob-<?= $key ?>"
                         aria-describedby="emailHelp-ob-<?= $key ?>"
                         placeholder="E-mailadres invullen">
                  <small id="emailHelp-ob-<?= $key ?>" class="form-text text-muted">E-mailadres wordt niet openbaar
                    gemaakt.</small>
                </div>
                <button type="submit" class="btn btn-primary">Inschrijven voor <?= $time ?></button>
              </form>
            </div>
          </div>
          <?php else: ?>
          <p>
            <button type="button" disabled class="btn btn-danger">Inschrijving is vol</button>
          </p>
          <?php endif; ?>


        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

</div>






<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

<script type="application/javascript">
  $(".add-participant").click(function() {
    var container = $(this).parent();
    var form = container.parent();
    var maxParticipants = parseInt(form.find("input[name=max-participants]")[0].value);
    var currentParticipants = container.find(".participant-container").children().length;

    if (currentParticipants < maxParticipants) {
      var element = $($.parseHTML('<input type="text" class="form-control" name="name[]" placeholder="Naam deelnemer '+
                                  (currentParticipants + 1)+'">'));
      container.find(".participant-container").append(element);
    }
    if (currentParticipants + 1 === maxParticipants) {
      $(this).remove();
    }

  });
</script>
</body>
</html>