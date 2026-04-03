<?php
declare(strict_types=1);
namespace LEX\App\Services\Email;

use LEX\Core\ConfiguracoesSistema;
use LEX\Core\SistemaConfig;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

final class EmailService
{
    public static function enviar(string $para, string $assunto, string $corpo): bool
    {
        $config = ConfiguracoesSistema::smtpConfig();
        if (empty($config['host']) || empty($config['usuario'])) {
            error_log('[EmailService] SMTP não configurado.');
            return false;
        }
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $config['host'];
            $mail->Port       = (int)$config['porta'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $config['usuario'];
            $mail->Password   = $config['senha'];
            $mail->SMTPSecure = (int)$config['porta'] === 465 ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->CharSet    = 'UTF-8';
            $mail->setFrom($config['de_email'], $config['de_nome']);
            $mail->addAddress($para);
            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body    = self::wrapHtml($assunto, $corpo);
            $mail->AltBody = strip_tags($corpo);
            $mail->send();
            return true;
        } catch (PHPMailerException $e) {
            error_log('[EmailService] Falha ao enviar para ' . $para . ': ' . $e->getMessage());
            return false;
        }
    }

    /** Notifica todos os e-mails de administradores configurados */
    public static function notificarAdmins(string $assunto, string $corpo): void
    {
        $emailsRaw = \LEX\Core\Settings::obter('smtp.admin_emails', '');
        if (empty($emailsRaw)) return;
        $emails = array_filter(array_map('trim', preg_split('/[\n\r,;]+/', $emailsRaw)));
        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                try { self::enviar($email, $assunto, $corpo); } catch (\Throwable $e) { /* silenciar */ }
            }
        }
    }

    // ── Eventos de negócio ────────────────────────────────────────────────

    /** Confirmação de nova demanda criada (para o cliente) */
    public static function novaDemanda(string $para, string $nomeCliente, string $codigoDemanda, string $tituloDemanda): bool
    {
        $nome = SistemaConfig::nome();
        $url  = SistemaConfig::url();
        $ok = self::enviar($para, "Sua demanda foi recebida — {$codigoDemanda}", "
            <p>Olá, <strong>{$nomeCliente}</strong>!</p>
            <p>Recebemos sua demanda <strong>{$codigoDemanda} — {$tituloDemanda}</strong>.</p>
            <p>Nossa equipe irá analisá-la e em breve você receberá propostas de parceiros qualificados.</p>
            <p><a href='{$url}/cliente/demandas' style='color:#B8945A'>Acompanhar minha demanda →</a></p>
            <p>Atenciosamente,<br><strong>{$nome}</strong></p>
        ");
        self::notificarAdmins("Nova demanda — {$codigoDemanda}", "<p><strong>Nova demanda recebida</strong></p><p><strong>Cliente:</strong> {$nomeCliente} ({$para})</p><p><strong>Código:</strong> {$codigoDemanda}</p><p><strong>Título:</strong> {$tituloDemanda}</p><p><a href='{$url}/equipe/demandas' style='color:#B8945A'>Ver no painel →</a></p>");
        return $ok;
    }

    /** Notificação de nova oportunidade distribuída (para o parceiro) */
    public static function novaOportunidade(string $para, string $nomeParceiro, string $codigoDemanda, string $tituloDemanda, string $cidade, string $estado): bool
    {
        $nome = SistemaConfig::nome();
        $url  = SistemaConfig::url();
        return self::enviar($para, "Nova oportunidade disponível — {$codigoDemanda}", "
            <p>Olá, <strong>{$nomeParceiro}</strong>!</p>
            <p>Uma nova oportunidade compatível com seu perfil está disponível:</p>
            <table style='border-collapse:collapse;width:100%;margin:16px 0'>
                <tr><td style='padding:8px;border:1px solid #eee;font-weight:500'>Código</td><td style='padding:8px;border:1px solid #eee'>{$codigoDemanda}</td></tr>
                <tr><td style='padding:8px;border:1px solid #eee;font-weight:500'>Título</td><td style='padding:8px;border:1px solid #eee'>{$tituloDemanda}</td></tr>
                <tr><td style='padding:8px;border:1px solid #eee;font-weight:500'>Localização</td><td style='padding:8px;border:1px solid #eee'>{$cidade} / {$estado}</td></tr>
            </table>
            <p><a href='{$url}/parceiro/oportunidades' style='color:#B8945A;font-weight:500'>Ver oportunidade →</a></p>
            <p>Atenciosamente,<br><strong>{$nome}</strong></p>
        ");
    }

    /** Notificação de proposta recebida (para o cliente) */
    public static function novaPropostaCliente(string $para, string $nomeCliente, string $codigoDemanda, string $nomeParceiro): bool
    {
        $nome = SistemaConfig::nome();
        $url  = SistemaConfig::url();
        $ok = self::enviar($para, "Nova proposta recebida — {$codigoDemanda}", "
            <p>Olá, <strong>{$nomeCliente}</strong>!</p>
            <p>O parceiro <strong>{$nomeParceiro}</strong> enviou uma proposta para sua demanda <strong>{$codigoDemanda}</strong>.</p>
            <p><a href='{$url}/cliente/propostas' style='color:#B8945A;font-weight:500'>Ver proposta →</a></p>
            <p>Atenciosamente,<br><strong>{$nome}</strong></p>
        ");
        self::notificarAdmins("Nova proposta — {$codigoDemanda}", "<p><strong>Nova proposta recebida</strong></p><p><strong>Parceiro:</strong> {$nomeParceiro}</p><p><strong>Demanda:</strong> {$codigoDemanda}</p><p><strong>Cliente:</strong> {$nomeCliente}</p><p><a href='{$url}/equipe/propostas' style='color:#B8945A'>Ver no painel →</a></p>");
        return $ok;
    }

    /** Notificação de proposta selecionada (para o parceiro) */
    public static function propostaSelecionada(string $para, string $nomeParceiro, string $codigoDemanda): bool
    {
        $nome = SistemaConfig::nome();
        $url  = SistemaConfig::url();
        $ok = self::enviar($para, "Sua proposta foi selecionada — {$codigoDemanda}", "
            <p>Olá, <strong>{$nomeParceiro}</strong>!</p>
            <p>Parabéns! Sua proposta para a demanda <strong>{$codigoDemanda}</strong> foi selecionada.</p>
            <p>Nossa equipe entrará em contato para os próximos passos.</p>
            <p><a href='{$url}/parceiro/propostas' style='color:#B8945A;font-weight:500'>Ver minhas propostas →</a></p>
            <p>Atenciosamente,<br><strong>{$nome}</strong></p>
        ");
        self::notificarAdmins("Proposta selecionada — {$codigoDemanda}", "<p><strong>Proposta selecionada</strong></p><p><strong>Parceiro:</strong> {$nomeParceiro}</p><p><strong>Demanda:</strong> {$codigoDemanda}</p>");
        return $ok;
    }

    /** Notificação de proposta recusada (para o parceiro) */
    public static function propostaRecusada(string $para, string $nomeParceiro, string $codigoDemanda): bool
    {
        $nome = SistemaConfig::nome();
        return self::enviar($para, "Atualização sobre sua proposta — {$codigoDemanda}", "
            <p>Olá, <strong>{$nomeParceiro}</strong>!</p>
            <p>Informamos que sua proposta para a demanda <strong>{$codigoDemanda}</strong> não foi selecionada desta vez.</p>
            <p>Continue acompanhando novas oportunidades em nossa plataforma.</p>
            <p>Atenciosamente,<br><strong>{$nome}</strong></p>
        ");
    }

    /** Notificação de contrato formalizado (para cliente e parceiro) */
    public static function contratoFormalizado(string $para, string $nomeDestinatario, string $codigoDemanda, string $valor): bool
    {
        $nome = SistemaConfig::nome();
        $url  = SistemaConfig::url();
        $ok = self::enviar($para, "Contrato formalizado — {$codigoDemanda}", "
            <p>Olá, <strong>{$nomeDestinatario}</strong>!</p>
            <p>O contrato referente à demanda <strong>{$codigoDemanda}</strong> foi formalizado.</p>
            <p><strong>Valor:</strong> {$valor}</p>
            <p><a href='{$url}/equipe/contratos' style='color:#B8945A;font-weight:500'>Ver contrato →</a></p>
            <p>Atenciosamente,<br><strong>{$nome}</strong></p>
        ");
        self::notificarAdmins("Contrato formalizado — {$codigoDemanda}", "<p><strong>Contrato formalizado</strong></p><p><strong>Demanda:</strong> {$codigoDemanda}</p><p><strong>Valor:</strong> {$valor}</p><p><a href='{$url}/equipe/contratos' style='color:#B8945A'>Ver no painel →</a></p>");
        return $ok;
    }

    /** Resultado de qualificação (para o parceiro) */
    public static function resultadoQualificacao(string $para, string $nomeParceiro, string $status, string $parecer = ''): bool
    {
        $nome = SistemaConfig::nome();
        $url  = SistemaConfig::url();
        $aprovado = in_array($status, ['aprovado', 'vetriks_ativo'], true);
        $titulo = $aprovado ? 'Qualificação aprovada' : 'Resultado da qualificação';
        $msg = $aprovado
            ? 'Sua qualificação foi <strong>aprovada</strong>! Você já pode receber oportunidades qualificadas.'
            : 'Sua qualificação foi analisada. Infelizmente não foi possível aprovar neste momento.';
        $parecerHtml = $parecer ? "<p><strong>Parecer:</strong> {$parecer}</p>" : '';
        $ok = self::enviar($para, "{$titulo} — {$nome}", "
            <p>Olá, <strong>{$nomeParceiro}</strong>!</p>
            <p>{$msg}</p>
            {$parecerHtml}
            <p><a href='{$url}/parceiro/perfil' style='color:#B8945A;font-weight:500'>Ver meu perfil →</a></p>
            <p>Atenciosamente,<br><strong>{$nome}</strong></p>
        ");
        self::notificarAdmins("Qualificação — {$nomeParceiro}", "<p><strong>Resultado de qualificação</strong></p><p><strong>Parceiro:</strong> {$nomeParceiro}</p><p><strong>Status:</strong> {$status}</p>{$parecerHtml}");
        return $ok;
    }

    /** Boas-vindas ao novo parceiro cadastrado */
    public static function boasVindasParceiro(string $para, string $nomeParceiro): bool
    {
        $nome = SistemaConfig::nome();
        $url  = SistemaConfig::url();
        $ok = self::enviar($para, "Bem-vindo à {$nome}!", "
            <p>Olá, <strong>{$nomeParceiro}</strong>!</p>
            <p>Seu cadastro foi recebido com sucesso. Nossa equipe irá analisar seu perfil e em breve você receberá o resultado da qualificação.</p>
            <p><a href='{$url}/parceiro/dashboard' style='color:#B8945A;font-weight:500'>Acessar meu painel →</a></p>
            <p>Atenciosamente,<br><strong>{$nome}</strong></p>
        ");
        self::notificarAdmins("Novo parceiro cadastrado", "<p><strong>Novo parceiro</strong></p><p><strong>Nome:</strong> {$nomeParceiro}</p><p><strong>E-mail:</strong> {$para}</p><p><a href='{$url}/equipe/parceiros' style='color:#B8945A'>Ver no painel →</a></p>");
        return $ok;
    }

    /** Notificação interna de novo contato recebido (para a equipe) */
    public static function novoContatoEquipe(string $paraEquipe, string $nomeRemetente, string $emailRemetente, string $mensagem): bool
    {
        $nome = SistemaConfig::nome();
        $ok = self::enviar($paraEquipe, "Novo contato recebido — {$nome}", "
            <p><strong>Nome:</strong> {$nomeRemetente}</p>
            <p><strong>E-mail:</strong> {$emailRemetente}</p>
            <p><strong>Mensagem:</strong></p>
            <blockquote style='border-left:3px solid #B8945A;padding:8px 16px;margin:8px 0;color:#555'>{$mensagem}</blockquote>
        ");
        self::notificarAdmins("Novo contato — {$nomeRemetente}", "<p><strong>Novo contato recebido</strong></p><p><strong>Nome:</strong> {$nomeRemetente}</p><p><strong>E-mail:</strong> {$emailRemetente}</p><p><strong>Mensagem:</strong> {$mensagem}</p>");
        return $ok;
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    private static function wrapHtml(string $titulo, string $corpo): string
    {
        $nome = SistemaConfig::nome();
        $gold = '#B8945A';
        return "<!DOCTYPE html><html><head><meta charset='UTF-8'/></head><body style='font-family:Arial,sans-serif;background:#f5f5f5;margin:0;padding:0'>
            <div style='max-width:600px;margin:32px auto;background:#fff;border-radius:4px;overflow:hidden'>
                <div style='background:#0C0C0A;padding:24px 32px'>
                    <span style='font-family:Georgia,serif;font-size:1.4rem;color:{$gold};letter-spacing:.08em'>{$nome}</span>
                </div>
                <div style='padding:32px;color:#333;line-height:1.7;font-size:.95rem'>
                    {$corpo}
                </div>
                <div style='background:#f9f9f9;padding:16px 32px;font-size:.75rem;color:#999;border-top:1px solid #eee'>
                    Este é um e-mail automático. Por favor, não responda diretamente.
                </div>
            </div>
        </body></html>";
    }
}
