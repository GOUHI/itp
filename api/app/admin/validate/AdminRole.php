<?php
declare (strict_types = 1);

namespace app\admin\validate;

use think\Validate;

class AdminRole extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'name|名称' => 'require|min:2|max:20|unique:adminRole',
	    'desc|描述' => 'max:200|unique:adminRole',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'save'=>['name','desc'],
        'update'=>['name','desc'],
    ];
}
