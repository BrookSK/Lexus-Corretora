<?php
declare(strict_types=1);
namespace LEX\Core\Jobs;

final class ContextoJob
{
    public function __construct(
        public readonly int $jobId,
        public readonly string $tipo,
        public readonly array $payload,
        public readonly ?string $agendadoPara = null,
    ) {}
}
