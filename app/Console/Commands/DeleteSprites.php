<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteSprites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pokemon:sprites:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all pokemon sprites';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        set_time_limit(0);

        $this->newLine();
        $this->info('Limpieza de sprites.');
        $this->newLine();
        $this->info('Obteniendo sprites.');

        $start = microtime(true);

        $path = storage_path('app/public/pokemon');
        $folder = File::files($path);
        
        $this->newLine();
        $this->info('Eliminando sprites.');

        $format_bar = $this->output->createProgressBar(count($folder));
        $format_bar->setFormat(" %current%/%max% [%bar%] %percent:3s%% | %message% - %size% KB");
        $format_bar->setMessage('sprite');
        $format_bar->setMessage('0', 'size');
        
        $this->newLine();
        
        foreach ($folder as $sprite) {
            $format_bar->setMessage(File::basename($sprite));
            $format_bar->setMessage(number_format(File::size($sprite) / 1024, '2', ',', '.'), 'size');

            File::delete($sprite);
            
            $format_bar->advance();
        }

        $end = microtime(true) - $start;
        
        $this->newLine(2);
        $this->info('Sprites eliminados exitosamente. Tiempo transcurrido: '.number_format($end, 2, ',', '.').'s');

        return Command::SUCCESS;
    }
}
