<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::options('{all?}', 'OptionsController@index');

Route::middleware(['auth:apiAdmin','permission'])->group(function (){

    /**
     * 网站配置
     */
    Route::prefix('config')->group(function (){
        Route::get('/', 'ConfigController@website')
            ->name('config@website')
            ->permissionName('网站基本配置')
            ->bindMenu('/config');

        Route::post('/', 'ConfigController@updateWebsite')
            ->name('config@website@update')
            ->permissionName('更新网站基本配置');

    },'网站配置');


    /**
     * 管理员
     */
    Route::prefix('member')->group(function (){
        Route::get('/', 'MemberController@lists')
            ->name('member@lists')
            ->permissionName('获取管理员列表')
            ->bindMenu('/member');
        Route::get('/my', 'MemberController@info')
            ->name('member@myinfo')
            ->permissionName('获取信息')
            ->autoPermission();
        Route::get('/{id}', 'MemberController@info')
            ->name('member@info')
            ->permissionName('获取管理员信息');
        Route::post('/', 'MemberController@create')
            ->name('member@create')
            ->permissionName('创建管理员');
        Route::delete('/', 'MemberController@delete')
            ->name('member@delete')
            ->permissionName('删除管理员');
        Route::patch('/{id}', 'MemberController@update')
            ->name('member@update')
            ->permissionName('编辑管理员');
    },'管理员');

    /**
     * 菜单
     */
    Route::prefix('menu')->group(function (){
        Route::get('/backend', 'MenuController@backend')
            ->name('menu@backend')
            ->bindMenu('/menu/backend')
            ->permissionName('获取后台菜单')
            ->autoPermission();


        Route::get('/frontend', 'MenuController@frontend')
            ->name('menu@frontend')
            ->bindMenu('/menu/frontend')
            ->permissionName('获取前台菜单')
            ->autoPermission();


        Route::post('/', 'MenuController@create')
            ->name('menu@create')
            ->permissionName('创建菜单');

        Route::delete('/{id}', 'MenuController@delete')
            ->name('menu@delete')
            ->permissionName('删除菜单');

        Route::patch('/{id}', 'MenuController@update')
            ->name('menu@update')
            ->permissionName('编辑菜单');

        Route::put('/up/{id}', 'MenuController@up')
            ->name('menu@up')
            ->permissionName('上移菜单');

        Route::put('/down/{id}', 'MenuController@down')
            ->name('menu@down')
            ->permissionName('上移菜单');


    },'菜单');


    /**
     * 角色
     */
    Route::prefix('role')->group(function (){
        Route::get('/', 'RoleController@lists')
            ->name('role@lists')
            ->permissionName('获取角色列表')
            ->bindMenu('/role');
        Route::post('/', 'RoleController@create')
            ->name('role@create')
            ->permissionName('创建角色');
        Route::delete('/{id}', 'RoleController@delete')
            ->name('role@delete')
            ->permissionName('删除角色');
        Route::patch('/{id}', 'RoleController@update')
            ->name('role@update')
            ->permissionName('更新角色');
    },'角色');


    /**
     * 权限
     */

    Route::prefix('permission')->group(function (){
        Route::get('/{role_id}', 'PermissionController@lists')
            ->name('permission@lists')
            ->permissionName('获取角色权限');
        Route::get('/', 'PermissionController@my')
            ->name('permission@my')
            ->permissionName('获取我的权限')->autoPermission();
        Route::patch('/{id}', 'PermissionController@adjust')
            ->name('permission@adjust')
            ->permissionName('更新角色权限');
    },'权限');

    /**
     * 文章
     */

    Route::prefix('article')->group(function (){

        Route::get('/', 'ArticleController@lists')
            ->name('article@lists')
            ->permissionName('获取文章列表')
            ->bindMenu('/article');
        Route::post('/', 'ArticleController@create')
            ->name('article@create')
            ->permissionName('创建文章');
        Route::delete('/{id}', 'ArticleController@delete')
            ->name('article@delete')
            ->permissionName('删除文章');
        Route::patch('/{id}', 'ArticleController@update')
            ->name('article@update')
            ->permissionName('更新文章');

    },'文章');

    /**
     * 文章分类
     */

    Route::prefix('article-category')->group(function (){

        Route::get('/', 'ArticleCategoryController@lists')
            ->name('article-category@lists')
            ->permissionName('获取文章分类列表')
            ->bindMenu('/article/category');
        Route::post('/', 'ArticleCategoryController@create')
            ->name('article-category@create')
            ->permissionName('创建文章分类');
        Route::delete('/{id}', 'ArticleCategoryController@delete')
            ->name('article-category@delete')
            ->permissionName('删除文章分类');
        Route::patch('/{id}', 'ArticleCategoryController@update')
            ->name('article-category@update')
            ->permissionName('更新文章分类');

    },'文章分类');


    /**
     * 文章标签
     */

    Route::prefix('article-tag')->group(function (){

        Route::get('/', 'ArticleTagController@lists')
            ->name('article-tag@lists')
            ->permissionName('获取文章标签列表')
            ->bindMenu('/article/tag');
        Route::post('/', 'ArticleTagController@create')
            ->name('article-tag@create')
            ->permissionName('创建文章标签');
        Route::delete('/{id}', 'ArticleTagController@delete')
            ->name('article-tag@delete')
            ->permissionName('删除文章标签');
        Route::patch('/{id}', 'ArticleTagController@update')
            ->name('article-tag@update')
            ->permissionName('更新文章标签');

    },'文章标签');

    /**
     * 上传
     */

    Route::post('/upload','UploadController@upload')->autoPermission();

    /**
     * 登出
     */
    Route::get('/logout', 'MemberController@logout')->autoPermission();

});

/**
 * 登录
 */
Route::post('/login', 'MemberController@login');


