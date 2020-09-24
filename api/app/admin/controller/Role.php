<?php

declare(strict_types=1);

namespace app\admin\controller;

use app\admin\model\AdminMenu;
use app\admin\model\AdminRole;
use app\BaseController;
use think\Request;

class Role extends BaseController
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
            $roleList = AdminRole::order('id')->paginate($limit);
            return ret(200, '', $roleList->toArray()['data'], ['total' => $roleList->toArray()['total']]);
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
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //获取参数
        $data['name'] = input('post.name', '', 'trim');
        $data['desc'] = input('post.desc', '', 'trim');
        $data['menu'] = input('post.menuStr', '', 'trim');

        try {
            //表单验证
            $validate = new \app\admin\validate\AdminRole();
            if (!$validate->scene('save')->check($data)) {
                return ret(400, $validate->getError());
            }

            if (AdminRole::insert($data) !== false) {
                return ret(200, '角色创建成功');
            }

            return ret(400, '角色创建失败，请联系管理员');
        } catch (\Exception $e) {
            recordLog('sql错误', $e->getMessage(), 'system', 3, 'error');
            return ret(404, '服务器异常请联系管理员');
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read()
    {
        try {
            $id = input('post.id', 0, 'trim');
            $adminRoleInfo = AdminRole::where('id', $id)->find();
            if (!$adminRoleInfo) {
                return ret(400, '获取角色详情失败');
            }

            //数组整理
            $menuList = AdminMenu::order('id')->select();
            // 循环插入展开属性
            foreach ($menuList as $key => $value) {
                foreach (explode(',', $adminRoleInfo['menu']) as $k => $v) {
                    if ($value['id'] == $v) {
                        $menuList[$key]['checked'] = true;
                    }
                }
            }
            //更改menu数据
            $adminRoleInfo['menu'] = getChild($menuList, 0, 2);

            return ret(200, '获取角色详情成功', $adminRoleInfo);
        } catch (\Exception $e) {
            recordLog('sql错误', $e->getMessage(), 'system', 3, 'error');
            return ret(404, '服务器异常请联系管理员');
        }
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @param  int $id
     * @return \think\Response
     */
    public function update(Request $request)
    {
        //获取参数
        $data['id'] = input('post.id', '', 'trim');
        $data['name'] = input('post.name', '', 'trim');
        $data['desc'] = input('post.desc', '', 'trim');
        $data['menu'] = input('post.menuStr', '', 'trim');

        try {
            //表单验证
            $validate = new \app\admin\validate\AdminRole();
            if (!$validate->scene('update')->check($data)) {
                return ret(400, $validate->getError());
            }
            //判断ID是否存在
            if (!AdminRole::where('id', $data['id'])->value('id')) {
                return ret(400, '需要修改的角色不存在');
            }

            if (AdminRole::where('id', $data['id'])->update($data) !== false) {
                return ret(200, '角色修改成功');
            }

            return ret(400, '角色修改失败，请联系管理员');
        } catch (\Exception $e) {
            recordLog('sql错误', $e->getMessage(), 'system', 3, 'error');
            return ret(404, '服务器异常请联系管理员');
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete()
    {
        $ids = input('post.ids', 0, 'trim');
        try {
            if(empty($ids)){
                return ret(400,'参数错误');
            }

            if(in_array('1',$ids)){
                return ret(400,'超级管理员不可以删除');
            }

            if(AdminRole::whereIn('id',$ids)->delete() !== false){
                return ret(200,'删除成功');
            }

            return ret(400,'删除失败，请联系管理员');
        } catch (\Exception $e) {
            recordLog('sql错误', $e->getMessage(), 'system', 3, 'error');
            return ret(404, '服务器异常请联系管理员');
        }
    }
}
