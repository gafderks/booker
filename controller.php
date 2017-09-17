<?php
$config = include('config.php');
require_once('bootstrap.php');

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'subscribe':
            subscribe($_POST['time'], $_POST['name'], $_POST['speltak'], $_POST['email']);
            break;
        case 'unsubscribe':
            unsubscribe($_POST['pass']);
            break;
        case 'modify':
            updateSubscription($_POST['pass'], $_POST['name'], $_POST['speltak']);
            break;
    }
}

function subscribe($time, array $names, $speltak, $email) {
    global $entityManager, $config, $notice, $mail;
    
    // Check if there are not too many already
    if (subscriptionCount($time) + count($names) <= $config['maxGroupSize']) {
    
        $subscription = new Subscription($time, $names, $speltak, $email);
        $entityManager->persist($subscription);
        $entityManager->flush();
    
        $mail->addAddress($email);
        $mail->Subject = 'Inschrijving spooktocht pivo\'s';
        $mail->Body = "Je hebt een inschrijving gedaan voor de spooktocht van de pivo's met de volgende informatie:"
            ."\nDeelnemers: ".join(', ', $names)
            ."\nSpeltak: $speltak"
            ."\nTijdstip: ".$config['date']." om $time"
            ."\n\nOm je inschrijving aan te passen of je uit te schrijven ga je naar:"
            ."\n".$_SERVER['HTTP_HOST']."/modify.php?pass=".$subscription->getPass()
            ."\n\n\nAls je vragen hebt over je inschrijving kun je mailen naar pivos@descouting.nl";
        
        try {
            $mail->send();
        } catch (Exception $e) {
            echo 'Mailer Error: '.$mail->ErrorInfo;
        }
        
        $notice['text'] = "Je hebt succesvol ".count($names)." personen aangemeld voor de spooktocht om $time. <br/>Op je emailadres heb je een bevestiging ontvangen.";
        $notice['type'] = 'success';
    } else {
        $notice['text'] = "Fout";
        $notice['type'] = 'danger';
    }
}

function unsubscribe($pass) {
    global $entityManager, $config, $notice;
    
    $subscription = getSubscriptionsByPass($pass)[0];
    $entityManager->remove($subscription);
    $entityManager->flush();
    
    $notice['text'] = "Je uitschrijving was succesvol.";
    $notice['type'] = 'success';
}

function updateSubscription($pass, $names, $speltak) {
    global $entityManager, $config, $notice;
    
    $subscription = getSubscriptionsByPass($pass)[0];
    $subscription->update($names, $speltak);
    $entityManager->flush();
    
    $notice['text'] = "Je inschrijving is succesvol aangepast.";
    $notice['type'] = 'success';
}

function subscriptionCount($time) {
    $subscriptions = getSubscriptions($time);
    $counter = 0;
    foreach ($subscriptions as $subscription) {
        $counter += count($subscription->getNames());
    }
    return $counter;
}

function getSubscriptions($time) {
    global $entityManager;
    return $entityManager->getRepository('Subscription')->findBy(['time' => $time]);
}

function getSubscriptionsByPass($pass) {
    global $entityManager;
    return $entityManager->getRepository('Subscription')->findBy(['pass' => $pass]);
}