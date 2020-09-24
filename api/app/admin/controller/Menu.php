<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\admin\model\AdminMenu;
use app\BaseController;
use think\Request;

class Menu extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $limit = input('post.limit',15,'trim');
        try {
            $menuList = AdminMenu::order('id')->paginate($limit);
            // 循环插入展开属性
            foreach ($menuList as $key => $value) {
                $menuList[$key]['_showChildren'] = true;
            }
            return ret(200, '', getChild($menuList->toArray()['data'], 0, 2),['total'=>$menuList->toArray()['total']]);
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
        //赋值
        $data['p_id'] = input('post.p_id', 0, 'trim');
        $data['title'] = input('post.title', '', 'trim');
        $data['key'] = input('post.key', '', 'trim');
        $data['icon'] = input('post.icon', '', 'trim');
        $data['to'] = input('post.to', '', 'trim');
        //表单验证
        $validate = new \app\admin\validate\AdminMenu();
        if (!$validate->scene('save')->check($data)) {
            return ret(400, $validate->getError());
        }

        try {
            //逻辑判断
            if ($data['p_id'] != 0) {
                //判断库里是否有这个父id
                if (!AdminMenu::where('id', $data['p_id'])->value('id')) {
                    return ret(400, '上级菜单不存在');
                }

            } else {
                //判断icon是否存在
                if ($data['icon'] == '') {
                    return ret(400, '顶级菜单icon必填填写');
                }
            }

            //新增操作
            if (AdminMenu::insert($data) !== false) {
                return ret(200);
            }

            return ret(400, '新增菜单失败，请联系管理员');
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
    public function read($id)
    {
        //
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
        //赋值
        $data['id'] = input('post.id', 0, 'trim');
        $data['p_id'] = input('post.p_id', 0, 'trim');
        $data['title'] = input('post.title', '', 'trim');
        $data['key'] = input('post.key', '', 'trim');
        $data['icon'] = input('post.icon', '', 'trim');
        $data['to'] = input('post.to', '', 'trim');
        //表单验证
        $validate = new \app\admin\validate\AdminMenu();
        if (!$validate->scene('update')->check($data)) {
            return ret(400, $validate->getError());
        }

        try {
            //逻辑判断
            if (!AdminMenu::where('id', $data['id'])->value('id')) {
                return ret(400, '需要修改的菜单不存在');
            }

            if ($data['p_id'] != 0) {
                //判断库里是否有这个父id
                if (!AdminMenu::where('id', $data['p_id'])->value('id')) {
                    return ret(400, '上级菜单不存在');
                }

            } else {
                //判断icon是否存在
                if ($data['icon'] == '') {
                    return ret(400, '顶级菜单icon必填填写');
                }
            }

            //新增操作
            if (AdminMenu::where('id', $data['id'])->update($data) !== false) {
                return ret(200);
            }

            return ret(400, '修改菜单失败，请联系管理员');
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

            if(AdminMenu::whereIn('id',$ids)->delete() !== false){
                return ret(200,'删除成功');
            }

            return ret(400,'删除失败，请联系管理员');
        } catch (\Exception $e) {
            recordLog('sql错误', $e->getMessage(), 'system', 3, 'error');
            return ret(404, '服务器异常请联系管理员');
        }
    }

    public function getMenuList()
    {
        $menuObj = AdminMenu::select();
        if (!$menuObj->isEmpty()) {
            // 循环插入展开属性
            foreach ($menuObj as $key => $value) {
                $menuObj[$key]['expand'] = true;
            }
            return ret(200, '菜单获取成功', getChild($menuObj));
        }

        return ret(200, '暂时无菜单');
    }
}
