<?php
declare(strict_types=1);
namespace LEX\Core\Jobs;

use LEX\Core\AppLogger;

final class ProcessadorJobs
{
    private array $handlers = [];

    public function registrar(string $tipo, callable $handler): void
    {
        $this->handlers[$tipo] = $handler;
    }

    public function processar(bool $umaVez = false): void
    {
        while (true) {
            $job = RepositorioJobs::proximo();
            if (!$job) {
                if ($umaVez) break;
                sleep(2);
                continue;
            }

            RepositorioJobs::marcarProcessando($job['id']);
            $tipo = $job['type'];
            $payload = json_decode($job['payload'], true) ?? [];

            if (!isset($this->handlers[$tipo])) {
                RepositorioJobs::marcarFalha($job['id'], "Handler não encontrado: $tipo");
                AppLogger::erro("Job handler não encontrado", ['tipo' => $tipo, 'id' => $job['id']]);
                if ($umaVez) break;
                continue;
            }

            try {
                $contexto = new ContextoJob($job['id'], $tipo, $payload, $job['run_at'] ?? null);
                ($this->handlers[$tipo])($contexto);
                RepositorioJobs::marcarConcluido($job['id']);
                AppLogger::info("Job concluído", ['tipo' => $tipo, 'id' => $job['id']]);
            } catch (\Throwable $e) {
                RepositorioJobs::marcarFalha($job['id'], $e->getMessage());
                AppLogger::erro("Job falhou", ['tipo' => $tipo, 'id' => $job['id'], 'erro' => $e->getMessage()]);
            }

            if ($umaVez) break;
        }
    }
}
