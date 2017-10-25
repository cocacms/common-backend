<?php

use Illuminate\Database\Seeder;

class Menu extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $root = App\Models\Menu::create(
            [
                'name' => 'root',
                'tag'  => 'root',
                'show' => false,

            ]
        );

        $backend = $root->children()->create(
            [
                'name' => 'backend',
                'tag'  => 'backend',
                'show' => false,
            ]
        );


        $dashboard = $backend->children()->create(
            [
                'name' => '控制台',
                'icon' => 'laptop',
                'route'=> '/dashboard'
            ]
        );


        $menu = $backend->children()->create(
            [
                'name' => '菜单',
                'icon' => 'bars',
                'bpid' => $dashboard->id,
            ]
        );


        $menu->children()->create(
            [
                'name' => '后台菜单',
                'icon' => 'bars',
                'bpid' => $menu->id,
                'route'=> '/menu/backend'
            ]
        );

        $menu->children()->create(
            [
                'name' => '前台菜单',
                'icon' => 'bars',
                'bpid' => $menu->id,
                'route'=> '/menu/frontend'
            ]
        );


        $root->children()->create(
            [
                'name' => 'frontend',
                'tag'  => 'frontend',
                'show' => false,
            ]
        );
    }
}
