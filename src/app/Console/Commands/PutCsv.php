<?php

namespace App\Console\Commands;

use App\Wishlist;
use Illuminate\Console\Command;

/**
 * Outputs wishlist data as CSV
 */
class PutCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:csv {path : output file path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Outputs wishlist data to a CSV file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $path = trim($this->argument('path'));
        
        if (strlen($path) == 0) {
            $this->error('Invalid output file.');
            return 1;
        }

        if (@file_exists($path)) {
            $this->error('Output file already exists.');
            return 1;
        }

        if ( ($fhandle = fopen($path, 'w+')) === FALSE) {
            $this->error('Failed to create output file.');
            return 1;
        }

        $data = Wishlist::wishlistReport();

        $bar = $this->output->createProgressBar(count($data));

        $bar->start();

        @fputcsv($fhandle, ['user','title wishlist','number of items'], ';');

        foreach ($data as $d) {
            if (@fputcsv($fhandle, [$d->user, $d->wishlist, $d->total ], ';') === FALSE) {
                $this->error('Failed to write data to the output file.');
                break;
            }

            $bar->advance();
        }

        $bar->finish();

        @fclose($fhandle);

        $this->line('');

        return 0;
    }
}
