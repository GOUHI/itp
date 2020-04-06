import request from '../ajax';

//登录后获取菜单接口
export const getMenuList = () => {
    return request({
        url: 'menu/getMenuList',
        method: 'post'
    })
}

// 菜单列表页面接口
export const index = (data) => {
    return request({
        url: 'menu/index',
        method: 'post',
        data
    })
}

// 菜单新增接口
export const save = (data) =>{
    return request({
        url:'menu/save',
        method:'post',
        data
    })
}

// 菜单编辑接口
export const update = (data) =>{
    return request({
        url:'menu/update',
        method:'post',
        data
    })
}

// 菜单删除接口
export const del = (data) =>{
    return request({
        url:'menu/delete',
        method:'post',
        data
    })
}