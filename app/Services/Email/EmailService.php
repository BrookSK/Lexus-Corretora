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
            $mail->Body    = $corpo; // Corpo já vem formatado dos templates
            $mail->AltBody = strip_tags($corpo);
            $mail->send();
            return true;
        } catch (PHPMailerException $e) {
            error_log('[EmailService] Falha ao enviar para ' . $para . ': ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Renderiza um template de e-mail com o layout base
     */
    private static function renderTemplate(string $templateName, array $data): string
    {
        $templatePath = dirname(__DIR__, 2) . '/Views/emails/' . $templateName . '.php';
        if (!file_exists($templatePath)) {
            error_log('[EmailService] Template não encontrado: ' . $templateName);
            return '';
        }
        
        // Extrair variáveis para o template
        extract($data);
        
        // Capturar o conteúdo do template específico
        ob_start();
        include $templatePath;
        $bodyContent = ob_get_clean();
        
        // Preparar dados para o template base
        $baseData = array_merge($data, ['bodyContent' => $bodyContent]);
        extract($baseData);
        
        // Renderizar o template base
        ob_start();
        include dirname(__DIR__, 2) . '/Views/emails/_base.php';
        return ob_get_clean();
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
        $url = SistemaConfig::url();
        $html = self::renderTemplate('nova-demanda', [
            'emailCategory' => 'Nova Demanda',
            'emailTitleLine1' => 'Sua demanda foi',
            'emailTitleLine2' => 'recebida com sucesso',
            'emailSubtitle' => 'Estamos analisando seu projeto e em breve você receberá propostas qualificadas',
            'recipientFirstName' => explode(' ', $nomeCliente)[0],
            'nomeCliente' => $nomeCliente,
            'codigoDemanda' => $codigoDemanda,
            'tituloDemanda' => $tituloDemanda,
            'siteUrl' => $url,
            'documentCode' => $codigoDemanda,
        ]);
        $ok = self::enviar($para, "Sua demanda foi recebida — {$codigoDemanda}", $html);
        self::notificarAdmins("Nova demanda — {$codigoDemanda}", "<p><strong>Nova demanda recebida</strong></p><p><strong>Cliente:</strong> {$nomeCliente} ({$para})</p><p><strong>Código:</strong> {$codigoDemanda}</p><p><strong>Título:</strong> {$tituloDemanda}</p><p><a href='{$url}/equipe/demandas' style='color:#B8945A'>Ver no painel →</a></p>");
        return $ok;
    }

    /** Notificação de nova oportunidade distribuída (para o parceiro) */
    public static function novaOportunidade(string $para, string $nomeParceiro, string $codigoDemanda, string $tituloDemanda, string $cidade, string $estado): bool
    {
        $url = SistemaConfig::url();
        $html = self::renderTemplate('nova-oportunidade', [
            'emailCategory' => 'Nova Oportunidade',
            'emailTitleLine1' => 'Nova oportunidade',
            'emailTitleLine2' => 'disponível',
            'emailSubtitle' => 'Um projeto compatível com seu perfil está aguardando sua proposta',
            'recipientFirstName' => explode(' ', $nomeParceiro)[0],
            'nomeParceiro' => $nomeParceiro,
            'codigoDemanda' => $codigoDemanda,
            'tituloDemanda' => $tituloDemanda,
            'cidade' => $cidade,
            'estado' => $estado,
            'siteUrl' => $url,
            'documentCode' => $codigoDemanda,
        ]);
        return self::enviar($para, "Nova oportunidade disponível — {$codigoDemanda}", $html);
    }

    /** Notificação de proposta recebida (para o cliente) */
    public static function novaPropostaCliente(string $para, string $nomeCliente, string $codigoDemanda, string $nomeParceiro): bool
    {
        $url = SistemaConfig::url();
        $html = self::renderTemplate('nova-proposta-cliente', [
            'emailCategory' => 'Nova Proposta',
            'emailTitleLine1' => 'Você recebeu',
            'emailTitleLine2' => 'uma nova proposta',
            'emailSubtitle' => 'Um parceiro qualificado enviou uma proposta para seu projeto',
            'recipientFirstName' => explode(' ', $nomeCliente)[0],
            'nomeCliente' => $nomeCliente,
            'codigoDemanda' => $codigoDemanda,
            'nomeParceiro' => $nomeParceiro,
            'siteUrl' => $url,
            'documentCode' => $codigoDemanda,
        ]);
        $ok = self::enviar($para, "Nova proposta recebida — {$codigoDemanda}", $html);
        self::notificarAdmins("Nova proposta — {$codigoDemanda}", "<p><strong>Nova proposta recebida</strong></p><p><strong>Parceiro:</strong> {$nomeParceiro}</p><p><strong>Demanda:</strong> {$codigoDemanda}</p><p><strong>Cliente:</strong> {$nomeCliente}</p><p><a href='{$url}/equipe/propostas' style='color:#B8945A'>Ver no painel →</a></p>");
        return $ok;
    }

    /** Notificação de proposta selecionada (para o parceiro) */
    public static function propostaSelecionada(string $para, string $nomeParceiro, string $codigoDemanda): bool
    {
        $url = SistemaConfig::url();
        $html = self::renderTemplate('proposta-selecionada', [
            'emailCategory' => 'Proposta Selecionada',
            'emailTitleLine1' => 'Parabéns!',
            'emailTitleLine2' => 'Sua proposta foi selecionada',
            'emailSubtitle' => 'O cliente escolheu sua proposta para realizar o projeto',
            'recipientFirstName' => explode(' ', $nomeParceiro)[0],
            'nomeParceiro' => $nomeParceiro,
            'codigoDemanda' => $codigoDemanda,
            'siteUrl' => $url,
            'documentCode' => $codigoDemanda,
        ]);
        $ok = self::enviar($para, "Sua proposta foi selecionada — {$codigoDemanda}", $html);
        self::notificarAdmins("Proposta selecionada — {$codigoDemanda}", "<p><strong>Proposta selecionada</strong></p><p><strong>Parceiro:</strong> {$nomeParceiro}</p><p><strong>Demanda:</strong> {$codigoDemanda}</p>");
        return $ok;
    }

    /** Notificação de proposta recusada (para o parceiro) */
    public static function propostaRecusada(string $para, string $nomeParceiro, string $codigoDemanda): bool
    {
        $url = SistemaConfig::url();
        $html = self::renderTemplate('proposta-recusada', [
            'emailCategory' => 'Atualização de Proposta',
            'emailTitleLine1' => 'Atualização sobre',
            'emailTitleLine2' => 'sua proposta',
            'emailSubtitle' => 'Informações sobre o status da sua proposta',
            'recipientFirstName' => explode(' ', $nomeParceiro)[0],
            'nomeParceiro' => $nomeParceiro,
            'codigoDemanda' => $codigoDemanda,
            'siteUrl' => $url,
            'documentCode' => $codigoDemanda,
        ]);
        return self::enviar($para, "Atualização sobre sua proposta — {$codigoDemanda}", $html);
    }

    /** Notificação de contrato formalizado (para cliente e parceiro) */
    public static function contratoFormalizado(string $para, string $nomeDestinatario, string $codigoDemanda, string $valor): bool
    {
        $url = SistemaConfig::url();
        $html = self::renderTemplate('contrato-formalizado', [
            'emailCategory' => 'Contrato Formalizado',
            'emailTitleLine1' => 'Contrato',
            'emailTitleLine2' => 'formalizado',
            'emailSubtitle' => 'O contrato foi oficialmente formalizado e o projeto está pronto para iniciar',
            'recipientFirstName' => explode(' ', $nomeDestinatario)[0],
            'nomeDestinatario' => $nomeDestinatario,
            'codigoDemanda' => $codigoDemanda,
            'valor' => $valor,
            'siteUrl' => $url,
            'documentCode' => $codigoDemanda,
        ]);
        $ok = self::enviar($para, "Contrato formalizado — {$codigoDemanda}", $html);
        self::notificarAdmins("Contrato formalizado — {$codigoDemanda}", "<p><strong>Contrato formalizado</strong></p><p><strong>Demanda:</strong> {$codigoDemanda}</p><p><strong>Valor:</strong> {$valor}</p><p><a href='{$url}/equipe/contratos' style='color:#B8945A'>Ver no painel →</a></p>");
        return $ok;
    }

    /** Resultado de qualificação (para o parceiro) */
    public static function resultadoQualificacao(string $para, string $nomeParceiro, string $status, string $parecer = ''): bool
    {
        $url = SistemaConfig::url();
        $aprovado = in_array($status, ['aprovado', 'vetriks_ativo'], true);
        $html = self::renderTemplate('resultado-qualificacao', [
            'emailCategory' => 'Resultado de Qualificação',
            'emailTitleLine1' => $aprovado ? 'Qualificação' : 'Resultado da',
            'emailTitleLine2' => $aprovado ? 'aprovada' : 'qualificação',
            'emailSubtitle' => $aprovado ? 'Você agora faz parte da nossa rede de parceiros qualificados' : 'Resultado da análise do seu perfil profissional',
            'recipientFirstName' => explode(' ', $nomeParceiro)[0],
            'nomeParceiro' => $nomeParceiro,
            'status' => $status,
            'parecer' => $parecer,
            'siteUrl' => $url,
            'documentCode' => '',
        ]);
        $titulo = $aprovado ? 'Qualificação aprovada' : 'Resultado da qualificação';
        $parecerHtml = $parecer ? "<p><strong>Parecer:</strong> {$parecer}</p>" : '';
        $ok = self::enviar($para, "{$titulo} — " . SistemaConfig::nome(), $html);
        self::notificarAdmins("Qualificação — {$nomeParceiro}", "<p><strong>Resultado de qualificação</strong></p><p><strong>Parceiro:</strong> {$nomeParceiro}</p><p><strong>Status:</strong> {$status}</p>{$parecerHtml}");
        return $ok;
    }

    /** Boas-vindas ao novo parceiro cadastrado */
    public static function boasVindasParceiro(string $para, string $nomeParceiro): bool
    {
        $url = SistemaConfig::url();
        $html = self::renderTemplate('boas-vindas-parceiro', [
            'emailCategory' => 'Boas-vindas',
            'emailTitleLine1' => 'Bem-vindo à',
            'emailTitleLine2' => SistemaConfig::nome(),
            'emailSubtitle' => 'Seu cadastro foi recebido e está sendo analisado por nossa equipe',
            'recipientFirstName' => explode(' ', $nomeParceiro)[0],
            'nomeParceiro' => $nomeParceiro,
            'siteUrl' => $url,
            'documentCode' => '',
        ]);
        $ok = self::enviar($para, "Bem-vindo à " . SistemaConfig::nome() . "!", $html);
        self::notificarAdmins("Novo parceiro cadastrado", "<p><strong>Novo parceiro</strong></p><p><strong>Nome:</strong> {$nomeParceiro}</p><p><strong>E-mail:</strong> {$para}</p><p><a href='{$url}/equipe/parceiros' style='color:#B8945A'>Ver no painel →</a></p>");
        return $ok;
    }

    /** Notificação interna de novo contato recebido (para a equipe) */
    public static function novoContatoEquipe(string $paraEquipe, string $nomeRemetente, string $emailRemetente, string $mensagem): bool
    {
        $url = SistemaConfig::url();
        $html = self::renderTemplate('novo-contato', [
            'emailCategory' => 'Novo Contato',
            'emailTitleLine1' => 'Novo contato',
            'emailTitleLine2' => 'recebido',
            'emailSubtitle' => 'Um novo contato foi enviado através do formulário do site',
            'recipientFirstName' => 'Equipe',
            'nomeRemetente' => $nomeRemetente,
            'emailRemetente' => $emailRemetente,
            'mensagem' => $mensagem,
            'siteUrl' => $url,
            'documentCode' => '',
        ]);
        $ok = self::enviar($paraEquipe, "Novo contato recebido — " . SistemaConfig::nome(), $html);
        self::notificarAdmins("Novo contato — {$nomeRemetente}", "<p><strong>Novo contato recebido</strong></p><p><strong>Nome:</strong> {$nomeRemetente}</p><p><strong>E-mail:</strong> {$emailRemetente}</p><p><strong>Mensagem:</strong> {$mensagem}</p>");
        return $ok;
    }

    // ── Helpers ───────────────────────────────────────────────────────────
}
