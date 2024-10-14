<?php

namespace TomatoPHP\FilamentDocs\Console;

use Illuminate\Console\Command;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;

class FilamentDocsInstall extends Command
{
    use RunCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'filament-docs:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install package and publish assets';

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Publish Vendor Assets');
        $this->artisanCommand(["migrate"]);
        $this->artisanCommand(["optimize"]);
        $this->artisanCommand(["filament:optimize-clear"]);
        $this->artisanCommand(["icon:cache"]);
        $this->info('Filament Docs installed successfully.');
    }
}
