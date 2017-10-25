<?php
namespace App\Services\Baum;
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */
use Baum\Console\InstallCommand as BaseCommand;

class InstallCommand extends BaseCommand
{
    public function handle() {
        parent::fire();
    }
}