<?php

namespace app\admin\controller;

use app\admin\model\Admin;
use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        try {
            $adminInfo = Admin::where('id',6)->select();
            return ret(200, '获取成功', $adminInfo);
        } catch (\Exception $e) {
            recordLog('sql错误', $e->getMessage(), 'system', 3, 'error');
            return ret(404, '服务器异常请联系管理员');
        }
    }
}
