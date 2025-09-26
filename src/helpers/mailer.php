<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

class Mailer {
    public static function sendOtp($toEmail, $otp) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'husnimubarakportofolio@gmail.com';
            $mail->Password = 'ghmm mgji jcup acyn';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('husnimubarakportofolio@gmail.com', 'Zegar Boegar - Kasir Digital');
            $mail->addAddress($toEmail);

            $mail->isHTML(true);
            $mail->Subject = 'Kode OTP Lupa Password';
            $mail->Body    = "Kode OTP Anda: <b>$otp</b>";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
            return false;
        }
    }
}
