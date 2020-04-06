<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;

class Admin extends Model
{
    //表名
    protected $name = 'admin';
    //转换字段
    protected $type =[
        "create_time"=>"createTime",
    ];
}
