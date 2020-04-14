<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use stdClass;

class EmailController extends Controller
{
    private $data;


    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->data = new stdClass();

        $this->mail->isSMTP();
        $this->mail->isHTML(true);
        $this->mail->setLanguage("pt-br");

        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Charset = 'UTF-8';

        $this->mail->Host = 'smtp.live.com';
        $this->mail->Port = 587;
        $this->mail->Username = 'agenda.vexpenses@outlook.com';
        $this->mail->Password = 'vexpensesagenda321';
    }

    public function welcomeEmail(Request $request) {
        $html = '';
        $html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <br>';

        $html .= 'Seja muito bem vindo ' . $request->name . '. <br>';
        $html .= 'Esperamos que aproveite nossa agenda de contatos!. <br><br>';
        $html .= 'Email de confirmação, se não se cadastrou desconsidere. <br>';

        $this->add("Seja Bem vindo!", $html, $request->name, $request->email);
        $this->sendMail();
    }

    public function add(string $subject, string $body, string $recipient_name, string $recipient_email)
    {
        $this->data->subject = $subject;
        $this->data->body = utf8_decode($body);
        $this->data->recipient_name = $recipient_name;
        $this->data->recipient_email = $recipient_email;
        return $this;
    }

    public function sendMail()
    {
        try {
            $this->mail->Subject = $this->data->subject;
            $this->mail->msgHTML($this->data->body);
            $this->mail->setFrom('agenda.vexpenses@outlook.com', 'Agenda de Contatos');
            $this->mail->addAddress($this->data->recipient_email, $this->data->recipient_name);

            $this->mail->send();

        } catch (Exception $e) {
            return ResponseController::returnApi(true, null, "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}");;
        }
    }
}
