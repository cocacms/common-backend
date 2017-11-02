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
                'route'=> '/dashboard',
                'tag'  => 'dashboard'
            ]
        );


        $backend->children()->create(
            [
                'name' => '网站配置',
                'icon' => 'setting',
                'route'=> '/config',
                'bpid' => $dashboard->id,

            ]
        );

        $safety = $backend->children()->create(
            [
                'name' => '管理与安全',
                'icon' => 'safety',
                'bpid' => $dashboard->id,

            ]
        );

        $safety->children()->create(
            [
                'name' => '管理员管理',
                'icon' => 'solution',
                'route'=> '/member',
                'bpid' => $safety->id,

            ]
        );

        $safety->children()->create(
            [
                'name' => '角色与权限',
                'icon' => 'team',
                'route'=> '/role',
                'bpid' => $safety->id,

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
