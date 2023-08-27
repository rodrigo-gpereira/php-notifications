<?php

namespace Notifications;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email
{
    private $mail = \stdClass::class;

    public function __construct($smtp, $user, $pass, $port, $secure, $fromEmail, $fromName, $debug = false)
    {
        $this->mail = new PHPMailer(true);
        
        if($debug){
            $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;   
        }
        
        $this->mail->isSMTP();                                           
        $this->mail->Host       = $smtp;                    
        $this->mail->SMTPAuth   = true;                                   
        $this->mail->Username   = $user;
        $this->mail->Password   = $pass;
        $this->mail->SMTPSecure = $secure;
        $this->mail->Port       = $port;
        $this->mail->CharSet = 'utf-8';
        $this->mail->setLanguage('br');

        $this->mail->SMTPOptions = array(
            'ssl' => [
                'verify_peer' => false,
                'verify_depth' => false,
                'allow_self_signed' => true
            ],
        );

        //Recipients
        $this->mail->setFrom($fromEmail, $fromName);  
    }
    public function sendMail($subject, $body, $addressEmail, $addressName,$replyEmail, $replyName)
    {

        $this->mail->isHTML(true);                                  
        $this->mail->Subject = (string)$subject;
        $this->mail->Body    = $body;

        $this->mail->addAddress($addressEmail, $addressName);
        $this->mail->addReplyTo($replyEmail, $replyName);

        try {
            $this->mail->send();
        } catch (Exception $e) {
            echo "Erro ao enviar o email: {$this->mail->ErrorInfo} {$e->getMessage()}";
        }
    }
}
