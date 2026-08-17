<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class TestAllCommand extends Command
{
    protected $signature = 'test:all {--report : Mostrar informe detallado}';
    protected $description = 'Ejecutar todas las pruebas modernas y mostrar informe';

    public function handle()
    {
        $this->info(' Iniciando ejecución de todas las pruebas...');
        $this->newLine();

        $tests = [
            'Unit/ConfigTest' => 'Pruebas de configuración',
            'Unit/RedisTest' => 'Pruebas de Redis',
            'Feature/Auth/LoginTest' => 'Pruebas de inicio de sesión',
            'Feature/CsrfTest' => 'Pruebas CSRF',
            'Feature/SessionTest' => 'Pruebas de sesión',
            'Browser/LoginTest' => 'Pruebas de navegador (Dusk)',
        ];

        $results = [];
        $failed = [];

        foreach ($tests as $path => $name) {
            $this->info(" Ejecutando: {$name}");

            $process = new Process([
                'vendor/bin/pest',
                "--testsuite={$path}",
                '--colors=always',
                '--no-interaction',
            ]);

            $process->setTimeout(300);
            $process->run();

            $output = $process->getOutput();

            if ($process->isSuccessful()) {
                $this->line("    {$name} exitoso");
                $results[$name] = ' Aprobado';
            } else {
                $this->line("    {$name} falló");
                $failed[$name] = $output;
                $results[$name] = ' Falló';
            }

            if ($this->option('report')) {
                $this->line($output);
            }
            $this->newLine();
        }

        $this->newLine();
        $this->line('═══════════════════════════════════════════════════');
        $this->info(' Informe final de pruebas');
        $this->line('═══════════════════════════════════════════════════');

        foreach ($results as $name => $status) {
            $this->line("{$status}  {$name}");
        }

        if (!empty($failed)) {
            $this->newLine();
            $this->error(' Algunas pruebas fallaron:');
            foreach ($failed as $name => $output) {
                $this->line("    {$name}");
                $this->line('   ' . str_repeat('─', 40));
                $this->line($this->extractErrors($output));
            }
        } else {
            $this->newLine();
            $this->info(' ¡Todas las pruebas pasaron exitosamente!');
        }

        return empty($failed) ? 0 : 1;
    }

    private function extractErrors($output)
    {
        $lines = explode("\n", $output);
        $errors = [];
        $inError = false;

        foreach ($lines as $line) {
            if (str_contains($line, 'FAIL') || str_contains($line, 'Error')) {
                $inError = true;
            }
            if ($inError && !empty(trim($line))) {
                $errors[] = trim($line);
            }
            if (str_contains($line, 'Tests:') && $inError) {
                break;
            }
        }

        return implode("\n", array_slice($errors, 0, 20));
    }
}