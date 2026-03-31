<?php
declare(strict_types=1);

return [
    'db' => [
        'host'    => 'localhost',
        'porta'   => 3306,
        'nome'    => 'lexus_corretora',
        'usuario' => 'root',
        'senha'   => '',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'url'       => 'https://lexuscorretora.com.br',
        'ambiente'  => 'producao', // 'desenvolvimento' | 'producao'
        'debug'     => false,
        'timezone'  => 'America/Sao_Paulo',
        'chave_app' => 'GERAR_CHAVE_ALEATORIA_64_CHARS',
    ],
    'smtp' => [
        'host'     => 'smtp.exemplo.com',
        'porta'    => 587,
        'usuario'  => '',
        'senha'    => '',
        'de_email' => 'noreply@lexuscorretora.com.br',
        'de_nome'  => 'Lexus Corretora',
    ],
];
