<?php

namespace App\Controllers\Mail;
require __DIR__ . "/../../../vendor/autoload.php";

use App\Core\Session;
use App\Core\View;
use App\models\Event;
use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();



class MailController
{
//    private $userData;
//    private $eventData;
    private $mailer;

    public function __construct()
    {
        $this->userData = Session::get("user");
        $this->eventData = new Event();
        $this->initializeMailer();
    }

    private function initializeMailer()
    {
        $this->mailer = new PHPMailer(true);

        $this->mailer->isSMTP();
        $this->mailer->Host       = $_ENV['MAIL_HOST']; // Replace with your SMTP host
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = $_ENV['MAIL_USERNAME']; // Replace with your SMTP username
        $this->mailer->Password   = $_ENV['MAIL_PASSWORD']; // Replace with your SMTP password
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port       = $_ENV['MAIL_PORT']; // Replace with your SMTP port

        $this->mailer->setFrom('no-reply@gmail.com', 'Evently');
    }



    public function sendApprovedMail()
    {
        try {
        $subject = "Event Approval";
        $body = "Your reservation has been approved!";

         View::render("organizer/email/approved", [
            'subject' => $subject,
            'body' => $body
        ]);



        $this->mailer->addAddress('aminalina908@gmail.com');
        $this->mailer->isHTML(true);
        $this->mailer->Subject = $subject;
        $this->mailer->Body    = "<h2 >$subject</h2>
                                    <div >Welcome!</div>
                                    <p>Thank you for your reservation. We look forward to seeing you at the event!</p>
                                    <p >$body</p>
                                   ";
        $this->mailer->AltBody = strip_tags($body);
        $this->mailer->send();

        return ['success' => true, 'message' => 'Email sent successfully!'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => "Failed to send email. Mailer Error: {$this->mailer->ErrorInfo}"];
        }

    }
}