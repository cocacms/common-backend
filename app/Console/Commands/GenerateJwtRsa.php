<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class GenerateJwtRsa extends Command
{
    use ConfirmableTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jwt:make:rsa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the application jwt ras private and public key';

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
     * @return void
     */
    public function handle()
    {
        $keys = $this->generateRsaKey();

        // Next, we will replace the application key in the environment file so it is
        // automatically setup for this developer. This key gets generated using a
        // secure random byte generator and is later base64 encoded for storage.
        if (! $this->setKeyInEnvironmentFile($keys)) {
            return;
        }

        $this->laravel['config']['auth.jwt.privateKey'] = $keys[0];
        $this->laravel['config']['auth.jwt.publicKey'] = $keys[1];

        $this->info("jwt ras private and public key set successfully.");
    }

    /**
     * Generate a random key for the application.
     *
     * @return array
     */
    protected function generateRsaKey()
    {

        //创建公钥和私钥
        $res=openssl_pkey_new(array('private_key_bits' => 1024)); #此处512必须不能包含引号。

        //提取私钥
        openssl_pkey_export($res, $private_key);

        //生成公钥
        $public_key=openssl_pkey_get_details($res);

        $public_key=$public_key["key"];

        //显示数据
        $public_key = str_replace([
            "-----BEGIN PUBLIC KEY-----",
            "-----END PUBLIC KEY-----",
            "\n"
        ],"",$public_key);

        $private_key = str_replace([
            "-----BEGIN PRIVATE KEY-----",
            "-----END PRIVATE KEY-----",
            "\n"
        ],"",$private_key);

        return [
            $private_key,
            $public_key
        ];
    }

    /**
     * Set the application key in the environment file.
     *
     * @param  array  $keys
     * @return bool
     */
    protected function setKeyInEnvironmentFile($keys)
    {
        $privateKey = $this->laravel['config']['auth.jwt.privateKey'];

        if (strlen($privateKey) !== 0 && (! $this->confirmToProceed())) {
            return false;
        }

        $this->writeNewEnvironmentFileWith('JWT_PRIVATE_KEY',$keys[0],$privateKey);

        $publicKey = $this->laravel['config']['auth.jwt.publicKey'];

        if (strlen($publicKey) !== 0 && (! $this->confirmToProceed())) {
            return false;
        }

        $this->writeNewEnvironmentFileWith('JWT_PUBLIC_KEY',$keys[1],$publicKey);

        return true;
    }

    /**
     * Write a new environment file with the given key.
     *
     * @param  string  $name
     * @param  string  $key
     * @param  string  $oldKey
     * @return void
     */
    protected function writeNewEnvironmentFileWith($name,$key,$oldKey)
    {
        file_put_contents($this->laravel->environmentFilePath(), preg_replace(
            $this->keyReplacementPattern($name,$oldKey),
            $name.'='.$key,
            file_get_contents($this->laravel->environmentFilePath())
        ));
    }

    /**
     * Get a regex pattern that will match env APP_KEY with any random key.
     *
     * @return string
     */
    protected function keyReplacementPattern($name,$key)
    {
        $escaped = preg_quote('='.$key, '/');

        return "/^{$name}{$escaped}/m";
    }
}
