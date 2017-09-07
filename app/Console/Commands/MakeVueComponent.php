<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeVueComponent extends Command
{

    /**
     * The file path of the vue component.
     */
    const VUE_COMPONENT_PATH = 'resources/assets/js/components/';
    /**
     * The file path of the vue js.
     */
    const VUE_JS_PATH = 'resources/assets/js/';
    /**
     * The stub of the vue component.
     */
//    const VUE_COMPONENT_STUB = __DIR__.'/stubs/vue.stub';
    const VUE_COMPONENT_STUB = 'resources/stubs/vue/vue.stub';
    /**
     * The stub of the vue js.
     */
    const VUE_JS_STUB = 'resources/stubs/vue/vuejs.stub';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
//    protected $signature = 'command:name';
    protected $signature = 'make:vue {name} {--nojs : Do not create a js file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Vue Component';

    /**
     * The object of the Filesystem.
     *
     * @var Filesystem
     */
    protected $fs;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $fs)
    {
        parent::__construct();
        $this->fs = $fs;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->createVueComponent();
        $this->createVueJS();
    }

    private function createVueComponent()
    {
        $name = $this->argument('name');
        $path = base_path(self::VUE_COMPONENT_PATH . $name).'.vue';
        if ($this->fs->exists($path)) {
            $this->error('Vue component already exists!');
            return;
        }
        //
        $this->makeDirectory($path);
        $stub = $this->fs->get(self::VUE_COMPONENT_STUB);
        $this->fs->put($path, $stub);
        $this->info('Vue component created successfully.');
    }
    private function createVueJS()
    {
        $nojs = $this->option('nojs');
        if (! $nojs) {
            $name = $this->argument('name');
            $path = base_path(self::VUE_JS_PATH . strtolower($name)).'.js';
            if ($this->fs->exists($path)) {
                $this->error('Vue js already exists!');
                return;
            }
            //
            $this->makeDirectory($path);
            $stub = $this->fs->get(self::VUE_JS_STUB);
            $stub = $this->renderStub($stub, ['name' => $name]);
            $this->fs->put($path, $stub);
            $this->info('Vue js created successfully.');
        }
    }
    private function makeDirectory($path)
    {
        if (! $this->fs->isDirectory(dirname($path))) {
            $this->fs->makeDirectory(dirname($path), 0777, true, true);
        }
    }
    private function renderStub($stub, $datas)
    {
        foreach ($datas as $find => $replace) {
            $stub = str_replace('$'.$find, $replace, $stub);
        }
        return $stub;
    }
}
