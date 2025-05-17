<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TrainModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:train-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to Train our model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo $this->description;
    }
}
