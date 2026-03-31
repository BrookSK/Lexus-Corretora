<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use LEX\Core\{Bootstrap, AppLogger};
use LEX\Core\Jobs\ProcessadorJobs;

// Inicializar sem sessão/HTTP
date_default_timezone_set('America/Sao_Paulo');

$umaVez = in_array('--once', $argv ?? [], true);

AppLogger::info('Worker iniciado', ['once' => $umaVez]);

$processador = new ProcessadorJobs();

// Registrar handlers
require_once __DIR__ . '/app/Jobs/RegistroHandlers.php';
\LEX\App\Jobs\RegistroHandlers::registrar($processador);

$processador->processar($umaVez);
