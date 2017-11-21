<?php

namespace App\Console\Commands;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Finder\Finder;
use SplFileInfo;
use Exception;


class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'init web';

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
     * @return mixed
     */
    public function handle()
    {
        if(file_exists('.env')){
            $this->error('.env already exist!');
            exit;
        }
        //复制环境设置
        $this->info("copy .env file...\n");
        copy('.env.example','.env');
        $this->info("copy success!\n");

        $this->alert('please input your mysql info');

        $localhost = $this->ask('What is your mysql host?','localhost');
        $port = $this->ask('What is your mysql port?', '3306');
        $database = $this->ask('What is your mysql database?', 'coca');
        $username = $this->ask('What is your mysql username?', 'root');
        $password = $this->ask('What is your mysql password?', 'root');

        file_put_contents($this->laravel->environmentFilePath(), str_replace(
            ["DB_HOST=127.0.0.1","DB_PORT=3306","DB_DATABASE=homestead","DB_USERNAME=homestead","DB_PASSWORD=secret"],
            ["DB_HOST=$localhost","DB_PORT=$port","DB_DATABASE=$database","DB_USERNAME=$username","DB_PASSWORD=$password"],
            file_get_contents($this->laravel->environmentFilePath())
        ));

        $this->info("generate key...\n");

        //生成key
        $this->call('key:generate');

        $this->reloadConfig();

        //配置有变化，重新链接数据库
        $this->laravel->detectEnvironment(function (){
            return 'local';
        });

        $this->laravel['db']->reconnect();

        $this->info("generate key success!\n");

        $this->info("migrate db...\n");

        //生成数据库结构
        $this->call('migrate');

        $this->info("creating administrator and menu info...\n");

        //创建管理账户
        $this->call('db:seed');

        $headers = ['username', 'password'];

        $users = [
            [
                'username' => 'root',
                'password' => 'root',

            ]
        ];

        $this->info("create success!\n");

        $this->alert('there are your administrator info');

        $this->table($headers, $users);


        $this->info("generate jwt rsa public and private...\n");

        //创建jwt key
        Artisan::call('jwt:make:rsa');

        $this->info("generate jwt rsa public and private success!\n");

        $this->info("install over!\n");

    }

    protected function reloadConfig(){

        try {
            (new Dotenv($this->laravel->environmentPath(), $this->laravel->environmentFile()))->load();
        } catch (InvalidPathException $e) {
            $this->error($e->getMessage());
        }

        $files = $this->getConfigurationFiles();

        if (! isset($files['app'])) {
            throw new Exception('Unable to load the "app" configuration file.');
        }

        foreach ($files as $key => $path) {
            $this->laravel['config']->set($key, require $path);
        }

    }


    protected function getConfigurationFiles()
    {
        $files = [];

        $configPath = realpath($this->laravel->configPath());

        foreach (Finder::create()->files()->name('*.php')->in($configPath) as $file) {
            $directory = $this->getNestedDirectory($file, $configPath);

            $files[$directory.basename($file->getRealPath(), '.php')] = $file->getRealPath();
        }

        ksort($files, SORT_NATURAL);

        return $files;
    }

    /**
     * Get the configuration file nesting path.
     *
     * @param  \SplFileInfo  $file
     * @param  string  $configPath
     * @return string
     */
    protected function getNestedDirectory(SplFileInfo $file, $configPath)
    {
        $directory = $file->getPath();

        if ($nested = trim(str_replace($configPath, '', $directory), DIRECTORY_SEPARATOR)) {
            $nested = str_replace(DIRECTORY_SEPARATOR, '.', $nested).'.';
        }

        return $nested;
    }
}
