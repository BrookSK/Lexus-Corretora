<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use LEX\Core\Bootstrap;
use LEX\Core\Roteador;

Bootstrap::iniciar();
Roteador::despachar();
