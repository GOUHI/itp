<?php
declare (strict_types = 1);

namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
	protected $rule = [
	    'account|账户'=>'require|min:5|max:20',
        'password|密码' => 'require|min:32|max:32'
    ];

    protected $scene = [
        "login" =>['account','password'],
    ];
}
