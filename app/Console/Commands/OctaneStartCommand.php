<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class OctaneStartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'octane:start-windows 
                            {--domain=a97infinity.test} 
                            {--port=8443} 
                            {--workers=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start Octane server on Windows with RoadRunner and SSL support';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Define signal constants for Windows compatibility
        if (! defined('SIGINT')) {
            define('SIGINT', 2);
        }
        if (! defined('SIGTERM')) {
            define('SIGTERM', 15);
        }
        if (! defined('SIGHUP')) {
            define('SIGHUP', 1);
        }

        $domain = $this->option('domain');
        $port = $this->option('port');
        $workers = $this->option('workers');

        $this->info("Starting Octane server on https://{$domain}:{$port} with {$workers} workers...");

        // Update RoadRunner configuration
        $this->updateRoadRunnerConfig($domain, $port, $workers);

        // Start RoadRunner
        $process = new Process([base_path('rr.exe'), 'serve', '-c', base_path('.rr.yaml')]);
        $process->setTimeout(null);
        $process->setIdleTimeout(null);

        $this->info('RoadRunner server started. Press Ctrl+C to stop.');

        try {
            $process->run(function ($type, $buffer) {
                $this->line($buffer);
            });
        } catch (\Exception $e) {
            $this->error('Error starting RoadRunner: '.$e->getMessage());

            return 1;
        }

        return 0;
    }

    /**
     * Update RoadRunner configuration dynamically
     */
    private function updateRoadRunnerConfig($domain, $port, $workers)
    {
        $rpcPort = 6000 + ($port % 100);
        $metricsPort = 2100 + ($port % 100);

        $yaml = "version: '3'
rpc:
    listen: 'tcp://0.0.0.0:{$rpcPort}'
server:
    command: 'php app.php'
    relay: pipes
http:
    address: '{$domain}:{$port}'
    middleware:
        - gzip
        - static
    static:
        dir: public
        forbid:
            - .php
            - .htaccess
    pool:
        num_workers: {$workers}
        supervisor:
            max_worker_memory: 100
    ssl:
        cert: C:\\laragon\\etc\\ssl\\laragon.crt
        key: C:\\laragon\\etc\\ssl\\laragon.key
jobs:
    pool:
        num_workers: 2
        max_worker_memory: 100
    consume: {}
kv:
    local:
        driver: memory
        config:
            interval: 60
metrics:
    address: '0.0.0.0:{$metricsPort}'";

        file_put_contents(base_path('.rr.yaml'), $yaml);
    }
}
