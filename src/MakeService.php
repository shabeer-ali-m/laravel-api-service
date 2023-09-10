<?php

namespace Smartphp\LaravelApiService;

use Illuminate\Console\GeneratorCommand;

/**
 * Class MakeService.
 *
 * @author  smartphp
 */
class MakeService extends GeneratorCommand
{
    /**
     * STUB_PATH.
     */
    const STUB_PATH = __DIR__ . '/Stubs/';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:api {name : Create a CRUD API}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new REST CRUD API';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = '';

    protected function getStub()
    {

    }

    /**
     * @return string
     */
    protected function getServiceStub(): string
    {
        return self::STUB_PATH . 'service.stub';
    }

    protected function getInterfaceStub(): string
    {
        return self::STUB_PATH . 'interface.stub';
    }

    protected function getControlllerStub(): string
    {
        return self::STUB_PATH . 'controller.stub';
    }

    protected function getRequestStub(): string
    {
        return self::STUB_PATH . 'request.stub';
    }

    /**
     * @return string
     */
    protected function getServiceInterfaceStub(): string
    {
        return self::STUB_PATH . 'service.interface.stub';
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @see \Illuminate\Console\GeneratorCommand
     *
     */
    public function handle()
    {
        if ($this->isReservedName($this->getNameInput())) {
            $this->error('The name "' . $this->getNameInput() . '" is reserved by PHP.');
            return false;
        }

        $name = $this->getNameInput();
        $serviceName = $this->qualifyClass($name);
        $path = $this->getPath($serviceName.'Service');
        
        /*
        if ((! $this->hasOption('force') ||
                ! $this->option('force')) &&
            $this->alreadyExists($this->getNameInput())) {
            $this->error($this->type . ' already exists!');
            return false;
        }
        */

        $this->makeDirectory($path);
        
        $this->files->put(
            $path,
            $this->sortImports(
                $this->buildServiceClass($serviceName, $isInterface=false)
            )
        );

        // controller
        $path = str_replace('/Services',
            '/Http/Controllers',
            $this->getPath($serviceName.'Controller')
        );

        $this->files->put(
            $path,
            $this->sortImports(
                $this->buildServiceController($name)
            )
        );

        // request
        $path = str_replace('/Services',
            '/Http/Requests',
            $this->getPath($serviceName.'Request')
        );

        $this->files->put(
            $path,
            $this->sortImports(
                $this->buildHttpRequests($name)
            )
        );

        $this->info('Created successfully.');
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @param $isInterface
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildServiceClass(string $name, $isInterface): string
    {
        $stub = $this->files->get(
            $isInterface ? $this->getServiceInterfaceStub() : $this->getServiceStub()
        );

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildServiceInterface(string $name): string
    {
        $stub = $this->files->get($this->getInterfaceStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    protected function buildServiceController(string $name): string
    {
        $stub = $this->files->get($this->getControlllerStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    protected function buildHttpRequests(string $name): string
    {
        $stub = $this->files->get($this->getRequestStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * @param $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Services';
    }
}
