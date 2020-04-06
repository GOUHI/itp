<?php
use think\facade\Route;
//测试路由
Route::group('index', function () {
    Route::get('index', 'index/index');
});

//登录操作
Route::group('login', function () {
    Route::post('index', 'login/index');
})->middleware(\app\middleware\AdminAuth::class,'NoAuth');

//管理员菜单操作
Route::group('menu', function () {
    //菜单列表
    Route::post('index','menu/index');
    //新增菜单
    Route::post('save','menu/save');
    //修改菜单
    Route::post('update','menu/update');
    //删除菜单
    Route::post('delete','menu/delete');
    //获取菜单列表树形结构
    Route::post('getMenuList', 'menu/getMenuList');
})->middleware(\app\middleware\AdminAuth::class);
