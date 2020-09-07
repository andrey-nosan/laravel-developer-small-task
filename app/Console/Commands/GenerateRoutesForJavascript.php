<?php

namespace App\Console\Commands;

use Illuminate\Routing\Router;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

/**
 * Class GenerateRoutesForJavascript
 *
 * @package App\Console\Commands
 */
class GenerateRoutesForJavascript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route:json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate routes for javascript.';

    protected $router;

    /**
     * GenerateRoutesForJavascript constructor.
     *
     * @param  \Illuminate\Routing\Router  $router
     */
    public function __construct(Router $router)
    {
        parent::__construct();
        $this->router = $router;
    }

    public function handle()
    {

        Artisan::call('route:clear');

        $this->output->write('Cache of routers dropped successfully');

        $js_routes = [];
        $routes = $this->router->getRoutes();
        $this->output->newLine();
        $bar = $this->output->createProgressBar(count($routes));
        foreach ($routes as $route) {
            $js_routes[$route->getName()] = $route->uri();
            $bar->advance();
        }
        File::put('resources/js/routes.json', json_encode($js_routes, JSON_PRETTY_PRINT));
        $bar->finish();
        $this->output->success('Success!');
    }
}
