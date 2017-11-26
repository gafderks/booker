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

  <title>Pivo's PubQuiz</title>
  <style>
    @media print {
      .collapse {
        display: block !important;
        height: auto !important;
      }
      
      .hidden-print {
        display: none !important;
      }
    }
  </style>
  
  <style>

    @font-face {font-family:"Orange Juice";src:url("orange_juice.eot?") format("eot"),url("orange_juice.woff") format("woff"),url("orange_juice.ttf") format("truetype"),url("orange_juice.svg#orangejuice") format("svg");font-weight:normal;font-style:normal;}
    /*@font-face {*/
      /*font-family: 'Return-To-Sender';*/
      /*src:  url('Return-To-Sender.ttf.woff') format('woff'),*/
      /*url('Return-To-Sender.ttf.svg#Return-To-Sender') format('svg'),*/
      /*url('Return-To-Sender.ttf.eot'),*/
      /*url('Return-To-Sender.eot?#iefix') format('embedded-opentype');*/
      /*font-weight: normal;*/
      /*font-style: normal;*/
    /*}*/

    body {
      background: #31302f;
      color: white;
      height: 100%;
    }
    
    html {
      height: 100%;
    }
    
    body > .container {
      min-height: 100%;
      border-left: 50px solid transparent;
      border-right: 50px solid transparent;
      border-image: url(border.png);
      border-image-slice: 155;
      border-image-width: 32px;
    }
    
    h1 {
      font-family: 'Orange Juice',apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
      font-size: 60px;
    }
    
    /*h2, h3 {*/
      /*font-family: 'Return-To-Sender',apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;*/
    /*}*/
    
    .card {
      color: black;
    }
  </style>
</head>
<body>
<div class="container">
  
  <h1>Pivo's PubQuiz 2017</h1>
    
  <?php if (isset($notice)): ?>
    <div class="alert alert-<?= $notice['type'] ?>" role="alert">
        <?= $notice['text']; ?>
    </div>
  <?php endif; ?>
  
  <p>Op zaterdag 6 januari organiseren de pivo's een spooktocht voor alle 16+-leden, ouders en
    vrijwilligers
    van
    Scouting Laurentius.
    Deelname kost &euro;5 per team.</p>

  <h2>Teams</h2>
  <div id="accordion" role="tablist">
    <?php foreach (getTeams() as $key => $team): ?>
    <?php $available = $config['maxGroupSize'] - subscriptionCount($team); ?>
    <div class="card">
      <div class="card-header" role="tab" id="heading-<?= $key ?>">
        <h5 class="mb-0">
          <a data-toggle="collapse" href="#collapse-<?= $key ?>" aria-expanded="true" aria-controls="collapse-<?= $key ?>">
            <?= $key+1 ?>. <?= $team->getTeamName() ?>
            <small class="text-muted"><?= subscriptionCount($team) ?> deelnemer<?= (subscriptionCount($team) > 1) ? "s" : "" ?></small>
          </a>
        </h5>
      </div>

      <div id="collapse-<?= $key ?>" class="collapse" role="tabpanel" aria-labelledby="heading-<?= $key ?>"
           data-parent="#accordion">
        <div class="card-body">
          <h3 class="hidden-print">Teamleden</h3>
          <?php if ($available == $config['maxGroupSize']): ?>
            <p><em>Nog geen inschrijvingen</em></p>
          <?php else: ?>
          <ol>
            <?php foreach ($team->getSubscriptions() as $subscription): ?>
              <?php foreach ($subscription->getNames() as $name): ?>
                <li><?= $name; ?></li>
              <?php endforeach; ?>
            <?php endforeach; ?>
          </ol>
          <?php endif; ?>
    
          <?php if ($available > 0): ?>
          <p class="hidden-print">
            <a class="btn btn-primary hidden-print" data-toggle="collapse" href="#subscribe-<?= $key ?>"
               aria-expanded="false"
               aria-controls="subscribe-<?= $key ?>">
              Inschrijven
            </a>
          </p>
          <div class="collapse hidden-print" id="subscribe-<?= $key ?>">
            <div class="card card-body">
              <form class="hidden-print" method="post">
                <h3>Inschrijfformulier</h3>
                <input type="hidden" name="action" value="subscribe">
                <input type="hidden" name="team" value="<?= $team->getId() ?>">
                <input type="hidden" name="max-participants" value="<?= $available ?>">
                <input type="hidden" name="teamname" value="">
                <div class="form-group">
                  <label for="name-<?= $key ?>">Deelnemers</label>
                  <div class="participant-container">
                    <input type="text" required class="form-control" autocomplete="off" name="name[]" id="name-<?=
                    $key ?>"
                           placeholder="Naam deelnemer 1">
                  </div>
                  <button type="button" class="btn form-control btn-secondary add-participant">Deelnemer toevoegen (max
                    <?= $available ?>)</button>
                </div>
                <div class="form-group">
                  <label for="teamname-<?= $key ?>">Teamnaam</label>
                  <input type="text" name="tn" required readonly disabled class="form-control" id="teamname-<?= $key ?>"
                         aria-describedby="teamnameHelp-<?= $key ?>" autocomplete="off"
                         value="<?= $team->getTeamName() ?>">
                  <small id="teamnameHelp-<?= $key ?>" class="form-text text-muted">Alleen de eerste inschrijver kan
                    de teamnaam aanpassen met de link in de bevestigingsmail.</small>
                </div>
                <div class="form-group">
                  <label for="email-<?= $key ?>">E-mailadres inschrijver</label>
                  <input type="email" name="email" required class="form-control" id="email-<?= $key ?>"
                         aria-describedby="emailHelp-<?= $key ?>" autocomplete="off"
                         placeholder="E-mailadres invullen">
                  <small id="emailHelp-<?= $key ?>" class="form-text text-muted">E-mailadres wordt niet openbaar
                    gemaakt.</small>
                </div>
                <button type="submit" class="btn btn-primary">
                  <?php if ($available == $config['maxGroupSize']): ?>
                    Team inschrijven
                  <?php else: ?>
                    Teamleden toevoegen
                  <?php endif; ?>
                </button>
              </form>
            </div>
          </div>
          <?php else: ?>
          <p>
            <button type="button" disabled class="btn btn-danger hidden-print">Inschrijving is vol</button>
          </p>
          <?php endif; ?>


        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <div class="card hidden-print">
      <?php $key = "new" ?>
      <div class="card-header" role="tab" id="heading-<?= $key ?>">
        <h5 class="mb-0">
          <a data-toggle="collapse" href="#collapse-<?= $key ?>" aria-expanded="true" aria-controls="collapse-<?= $key ?>">
            <span style="color: black; font-weight: bold;">+&nbsp;Nieuw team</span>
          </a>
        </h5>
      </div>

      <div id="collapse-<?= $key ?>" class="collapse" role="tabpanel" aria-labelledby="heading-<?= $key ?>"
           data-parent="#accordion">
        <div class="card-body">
          <div class="hidden-print" id="subscribe-<?= $key ?>">
                <div class="card card-body">
                  <form class="hidden-print" method="post">
                    <h3>Inschrijfformulier</h3>
                    <input type="hidden" name="action" value="subscribe">
                    <input type="hidden" name="team" value="new">
                    <input type="hidden" name="max-participants" value="<?= $config['maxGroupSize'] ?>">
                    <div class="form-group">
                      <label for="name-<?= $key ?>">Deelnemers</label>
                      <div class="participant-container">
                        <input type="text" required class="form-control" autocomplete="off" name="name[]" id="name-<?=
                        $key ?>"
                               placeholder="Naam deelnemer 1">
                      </div>
                      <button type="button" class="btn form-control btn-secondary add-participant">Deelnemer toevoegen (max
                          <?= $config['maxGroupSize'] ?>)</button>
                    </div>
                    <div class="form-group">
                      <label for="teamname-<?= $key ?>">Teamnaam</label>
                      <input type="text" name="teamname" required class="form-control" id="teamname-<?= $key ?>"
                             aria-describedby="teamnameHelp-<?= $key ?>" autocomplete="off"
                             placeholder="Teamnaam invullen">
                      <small id="teamnameHelp-<?= $key ?>" class="form-text text-muted">De teamnaam kan later nog
                        worden aangepast.</small>
                    </div>
                    <div class="form-group">
                      <label for="email-<?= $key ?>">E-mailadres inschrijver</label>
                      <input type="email" name="email" required class="form-control" id="email-<?= $key ?>"
                             aria-describedby="emailHelp-<?= $key ?>" autocomplete="off"
                             placeholder="E-mailadres invullen">
                      <small id="emailHelp-<?= $key ?>" class="form-text text-muted">E-mailadres wordt niet openbaar
                        gemaakt.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Team inschrijven
                    </button>
                  </form>
                </div>
            </div>
        </div>
      </div>
    </div>
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
                                  (currentParticipants + 1)+'" autocomplete="off">'));
      container.find(".participant-container").append(element);
    }
    if (currentParticipants + 1 === maxParticipants) {
      $(this).remove();
    }

  });
</script>
</body>
</html>