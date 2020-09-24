<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\model\Admin;
use app\admin\model\AdminMenu;
use app\admin\model\AdminRole;
use app\BaseController;
use think\Request;

class Login extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //获取参数
        $data['account'] = input('post.account','','trim');
        $data['password'] = input('post.password','','trim');
        //进行验证
        $validate = new \app\admin\validate\Admin();
        if(!$validate->scene('login')->check($data)){
            return ret(400,$validate->getError(),[],[],200);
        }

        $adminInfo = Admin::where('account',$data['account'])->find();
        $adminInfo->token='123';
        Admin::where('id',$adminInfo->id)->update(['token'=>'123']);
        $menus = AdminRole::where('id',$adminInfo->role_id)->value('menu');
        $adminInfo->menu = getChild(AdminMenu::whereIn('id',$menus)->select());
        return ret(200,'登录成功',$adminInfo);
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
        //
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
