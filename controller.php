<?php
$config = include('config.php');
require_once('bootstrap.php');

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'subscribe':
            subscribe($_POST['team'], $_POST['name'], $_POST['teamname'], $_POST['email']);
            break;
        case 'unsubscribe':
            unsubscribe($_POST['pass']);
            break;
        case 'modify':
            updateSubscription($_POST['pass'], $_POST['name'], $_POST['teamname']);
            break;
    }
}

function subscribe($teamId, array $names, $teamname, $email) {
    global $entityManager, $config, $notice, $mail;
    $team = null;
    
    // Add a new team if necessary
    if ($teamId == 'new') {
        $team = new Team(htmlspecialchars($teamname));
        $entityManager->persist($team);
    } else {
        $team = getTeamById($teamId);
    }
    
    // Check if there are not too many already
    if (subscriptionCount($team) + count($names) <= $config['maxGroupSize']) {
        if (!empty($email) && count($names) > 0) {
    
            $subscription = new Subscription($team, $names, $email);
            $entityManager->persist($subscription);
            $entityManager->flush();
    
            $mail->addAddress($email);
            $mail->Subject = 'Inschrijving PubQuiz Pivo\'s';
            $mail->Body = "Je hebt een inschrijving gedaan voor de pubquiz van de pivo's met de volgende informatie:"
                . "\nDeelnemers: " . join(', ', $names)
                . "\nTeamnaam: ".$subscription->getTeam()->getTeamName()
                . "\n\nOm je inschrijving aan te passen of je uit te schrijven ga je naar:"
                . "\nhttps://" . $_SERVER['HTTP_HOST'] . "/modify.php?pass=" . $subscription->getPass()
                . "\n\n\nAls je vragen hebt over je inschrijving kun je mailen naar pivos@descouting.nl";
    
            try {
                $mail->send();
            } catch (Exception $e) {
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            }
    
            $notice['text'] = "Je hebt succesvol " . count($names) . " personen aangemeld voor de pubquiz.<br/>Op je emailadres heb je een bevestiging ontvangen.";
            $notice['type'] = 'success';
        } else {
            $notice['text'] = "Fout: Onvolledige inschrijving.";
            $notice['type'] = 'danger';
        }
    } else {
        $notice['text'] = "Fout: Team zit al vol.";
        $notice['type'] = 'danger';
    }
}

function unsubscribe($pass) {
    global $entityManager, $config, $notice;
    
    $subscription = getSubscriptionsByPass($pass)[0];
    
    if ((count($subscription->getTeam()->getSubscriptions()) == 1)) {
        // Also remove the team
        $team = $subscription->getTeam();
        $entityManager->remove($team);
    }
    
    $entityManager->remove($subscription);
    $entityManager->flush();
    
    $notice['text'] = "Je uitschrijving was succesvol.";
    $notice['type'] = 'success';
}

function updateSubscription($pass, $names, $teamname) {
    global $entityManager, $config, $notice;
    
    $subscription = getSubscriptionsByPass($pass)[0];
    $subscription->update($names);
    
    if ($subscription->getId() == $subscription->getTeam()->getSubscriptions()->first()->getId()) {
        // allowed to edit team name
        $subscription->getTeam()->update($teamname);
    }
    
    $entityManager->flush();
    
    $notice['text'] = "Je inschrijving is succesvol aangepast.";
    $notice['type'] = 'success';
}

function subscriptionCount(\Team $team) {
    $subscriptions = $team->getSubscriptions();
    $counter = 0;
    foreach ($subscriptions as $subscription) {
        $counter += count($subscription->getNames());
    }
    return $counter;
}

/**
 * @return \Team[]
 */
function getTeams() {
    global $entityManager;
    return $entityManager->getRepository('Team')->findAll();
}

/**
 * @param $id
 * @return \Team
 */
function getTeamById($id) {
    global $entityManager;
    return $entityManager->getRepository('Team')->findBy(['id' => $id])[0];
}

/**
 * @param $pass
 * @return \Subscription[]
 */
function getSubscriptionsByPass($pass) {
    global $entityManager;
    return $entityManager->getRepository('Subscription')->findBy(['pass' => $pass]);
}