<?php
$config = include('config.php');
include_once('controller.php');

$subscription = getSubscriptionsByPass($_GET['pass']);
if (count($subscription) == 0) {
    echo 'Ongeldige link';
    exit;
} else {
    $subscription = $subscription[0];
}
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
    
    <p>Op deze pagina kun je je inschrijving ongedaan maken of aanpassen.</p>
    
    <div class="card">
        <div class="card-header">
            Inschrijving aanpassen
        </div>
        <div class="card-body">
            <form class="hidden-print" method="post">
                <?php $key = 1; ?>
                <input type="hidden" name="action" value="modify">
                <input type="hidden" name="pass" value="<?= $_GET['pass'] ?>">
                <div class="form-group">
                    <label>Tijdslot</label>
                    <input type="text" readonly class="form-control" value="<?= $subscription->getTime(); ?>">
                    <small class="form-text text-muted">Om de tijd aan te passen schrijf je je eerst uit en meld je je
                        daarna weer aan.</small>
                </div>
                <div class="form-group">
                    <label for="name-ob-<?= $key ?>">Deelnemers</label>
                    <div class="participant-container">
                        <?php foreach ($subscription->getNames() as $c => $name) : ?>
                            <input type="text" class="form-control" name="name[]" id="name-ob-<?= $key ?>"
                                   placeholder="Naam deelnemer <?= $c + 1; ?>" value="<?= $name; ?>">
                        <?php endforeach; ?>
                    </div>
                    <small class="form-text text-muted">Maak een veld leeg om een losse deelnemer uit te schrijven.
                        Je kunt geen extra deelnemers toevoegen.
                    </small>
                </div>
                <div class="form-group">
                    <?php $s = $subscription->getSpeltak(); ?>
                    <label for="speltak-ob-<?= $key ?>">Speltak</label>
                    <select name="speltak" required class="form-control" id="speltak-ob-<?= $key ?>">
                        <option selected disabled hidden value="">Kies een speltak</option>
                        <optgroup label="Onderbouw">
                            <option <?= ($s != 'Bevers') ?: 'selected' ?>>Bevers</option>
                            <option <?= ($s != 'Jacala welpen') ?: 'selected' ?>>Jacala welpen</option>
                            <option <?= ($s != 'Rikki Tikki Tavi welpen') ?: 'selected' ?>>Rikki Tikki Tavi welpen</option>
                        </optgroup>
                        <optgroup label="Bovenbouw">
                            <option <?= ($s != 'Gidsen') ?: 'selected' ?>>Gidsen</option>
                            <option <?= ($s != 'Verkenners') ?: 'selected' ?>>Verkenners</option>
                            <option <?= ($s != 'RSA') ?: 'selected' ?>>RSA</option>
                            <option <?= ($s != 'Vrijwilliger') ?: 'selected' ?>>Vrijwilliger</option>
                        </optgroup>
                    </select>
                </div>
                <div class="form-group">
                    <label for="email-ob-<?= $key ?>">E-mailadres inschrijver</label>
                    <input type="email" required readonly class="form-control" id="email-ob-<?= $key ?>"
                           aria-describedby="emailHelp-ob-<?= $key ?>"
                           placeholder="E-mailadres invullen" value="<?= $subscription->getEmail(); ?>">
                    <small id="emailHelp-ob-<?= $key ?>" class="form-text text-muted">E-mailadres wordt niet openbaar
                        gemaakt.</small>
                </div>
                <button type="submit" class="btn btn-primary">Inschrijving aanpassen</button>
            </form>
        </div>
    </div>
    
    <hr style="margin: 30px;">
    
    <div class="card">
        <div class="card-header">
            Uitschrijven
        </div>
        <div class="card-body">
            <form class="hidden-print" method="post" action="index.php">
                <input type="hidden" name="action" value="unsubscribe">
                <input type="hidden" name="pass" value="<?= $_GET['pass'] ?>">
                <button type="submit" class="btn btn-danger">Alle deelnemers uitschrijven</button>
            </form>
        </div>
    </div>
    
    
    
    
    
</div>






<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

</body>
</html>