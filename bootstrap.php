<?php
// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $isDevMode);
// or if you prefer yaml or XML
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

// database configuration parameters
$conn = [
    'url' => getenv('DATABASE_URL')
];

// obtaining the entity manager
global $entityManager;
$entityManager = EntityManager::create($conn, $config);

// setup email
$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
try {
    $mail->IsSMTP(); // Use SMTP
    $mail->Host        = "smtp.gmail.com"; // Sets SMTP server
    $mail->SMTPDebug   = 2; // 2 to enable SMTP debug information
    $mail->SMTPAuth    = TRUE; // enable SMTP authentication
    $mail->SMTPSecure  = "tls"; //Secure conection
    $mail->Port        = 587; // set the SMTP port
    $mail->Username    = 'pm.pivo.sld@gmail.com'; // SMTP account username
    $mail->Password    = getenv('SMTP_PASS'); // SMTP account password
    $mail->Priority    = 1; // Highest priority - Email priority (1 = High, 3 = Normal, 5 = low)
    $mail->Encoding    = '8bit';
    $mail->ContentType = 'text/html; charset=utf-8\r\n';
    $mail->From        = 'pm.pivo.sld@gmail.com';
    $mail->FromName    = 'Pivo\'s';
    $mail->WordWrap = 50; // set word wrap to 50 characters
    $mail->IsHTML(false);
} catch (Exception $e) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}