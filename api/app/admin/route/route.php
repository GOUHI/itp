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

//菜单操作
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

//角色操作
Route::group('role', function () {
    //角色列表
    Route::post('index','role/index');
    //新增角色
    Route::post('save','role/save');
    //角色详情
    Route::post('read','role/read');
    //修改角色
    Route::post('update','role/update');
    //删除角色
    Route::post('delete','role/delete');
})->middleware(\app\middleware\AdminAuth::class);

//管理员操作
Route::group('admin', function () {
    //管理员列表
    Route::post('index','admin/index');
    //新增管理员
    Route::post('save','admin/save');
    //管理员详情
    Route::post('read','admin/read');
    //修改管理员
    Route::post('update','admin/update');
    //删除管理员
    Route::post('delete','admin/delete');
})->middleware(\app\middleware\AdminAuth::class);
