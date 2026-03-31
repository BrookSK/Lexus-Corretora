<?php
declare(strict_types=1);
namespace LEX\App\Services\Email;

use LEX\Core\ConfiguracoesSistema;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

final class EmailService
{
    public static function enviar(string $para, string $assunto, string $corpo): bool
    {
        $config = ConfiguracoesSistema::smtpConfig();
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $config['host'];
            $mail->Port = $config['porta'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['usuario'];
            $mail->Password = $config['senha'];
            $mail->SMTPSecure = $config['porta'] === 465 ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom($config['de_email'], $config['de_nome']);
            $mail->addAddress($para);

            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body = $corpo;
            $mail->AltBody = strip_tags($corpo);

            $mail->send();
            return true;
        } catch (PHPMailerException $e) {
            error_log('[EmailService] Falha ao enviar e-mail para ' . $para . ': ' . $e->getMessage());
            return false;
        }
    }

    public static function enviarTemplate(string $para, string $template, array $dados): bool
    {
        $templatePath = dirname(__DIR__, 2) . '/Views/_emails/' . $template . '.php';

        if (!file_exists($templatePath)) {
            error_log('[EmailService] Template não encontrado: ' . $template);
            return false;
        }

        extract($dados, EXTR_SKIP);
        ob_start();
        include $templatePath;
        $corpo = ob_get_clean();

        $assunto = $dados['assunto'] ?? $dados['subject'] ?? 'Lexus Corretora';

        return self::enviar($para, $assunto, $corpo);
    }
}
