<?php

declare(strict_types=1);

namespace app\admin\controller;

use app\admin\model\Admin as AdminModel;
use app\BaseController;
use think\Request;

class Admin extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $limit = input('post.limit', 15, 'trim');
        try {
            $roleListObj = AdminModel::alias('t1')->field('*,(select name from master_admin_role as t2 where t2.id = t1.role_id) as role_name')->order('id')->paginate($limit);
            $roleList = $roleListObj->toArray()['data'];
            foreach ($roleList as $key => $value) {
                $roleList[$key]['status_name'] = adminStatus($value['status']);
            }
            return ret(200, '', $roleList, ['total' => $roleListObj->toArray()['total']]);
        } catch (\Exception $e) {
            recordLog('sql错误', $e->getMessage(), 'system', 3, 'error');
            return ret(404, '服务器异常请联系管理员');
        }
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        // 赋值
        $data['account'] = input('post.account', '', 'trim');
        $data['password'] = input('post.password', '', 'trim');
        $data['role_id'] = input('post.role_id', '', 'trim');
        $data['status'] = input('post.status', '', 'trim');

        //表单验证
        $validate = new \app\admin\validate\Admin();
        if(!$validate->scene('save')->check($data)){
            return ret(400,$validate->getError());
        }

        try {
            $data['create_time'] = getTime('date');
            if(AdminModel::insert($data) !== false){
                return ret(200);
            }

            return ret(400,'新增管理员失败');
        } catch (\Exception $e) {
            recordLog('sql错误', $e->getMessage(), 'system', 3, 'error');
            return ret(404, '服务器异常请联系管理员');
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
