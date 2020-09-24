<?php

declare(strict_types=1);

namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'account|账户' => 'require|min:4|max:20',
        'password|密码' => 'require|min:32|max:32',
        'role_id|角色' => 'require|number',
        'status|状态' => 'require|in:1,2'
    ];

    protected $scene = [
        "login" => ['account', 'password'],
    ];

    // 新建管理员验证规则
    public function sceneSave()
    {
    	return $this->only(['account', 'password', 'role_id', 'status'])
        	->append('account', 'unique:admin');
    }    
    // 修改管理员验证规则
    public function sceneUpdate()
    {
    	return $this->only(['account', 'password', 'role_id', 'status'])
        	->append('account', 'unique:admin');
    }  
}
