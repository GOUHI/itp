<?php
declare (strict_types = 1);

namespace app\middleware;

class AdminAuth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next , $name = '')
    {
        //判断是否需要验证
        if($name != 'NoAuth'){
//            $admin = new Admin();
//            $adminInfo = $admin->getInfoByToken($request->token);
//            if(!empty($adminInfo)){
//                //判断是否有操作权限(app配置文件中指定不需要验证权限的分组ID)
//                if(!in_array($adminInfo->group_id,config('app.permiss_group'))){
//                    if(!in_array($request->permiss,explode(',',$adminInfo->permiss))){
//                        ret(202,'权限不足',[],[],200,1);
//                    }
//                }
//                $request->adminInfo = $adminInfo;
//            }else{
//                ret(201,'token失效',[],[],200,1);
//            }
        }

        //返回数据
        return $next($request);
    }
}
