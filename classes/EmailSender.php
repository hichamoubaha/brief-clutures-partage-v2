<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class EmailSender {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.example.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'your_email@example.com';
        $this->mail->Password = 'your_password';
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = 587;
    }

    public function sendWelcomeEmail($email, $role) {
        try {
            $this->mail->setFrom('noreply@artculture.com', 'Art Culture Platform');
            $this->mail->addAddress($email);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Welcome to Art Culture Platform';

            if ($role === 'auteur') {
                $this->mail->Body = 'Welcome to Art Culture Platform! We invite you to start publishing your articles.';
            } else {
                $this->mail->Body = 'Welcome to Art Culture Platform! We encourage you to explore, comment, and add articles to your favorites.';
            }

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

