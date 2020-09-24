import request from '../ajax';

//获取权限接口
export const roleIndex = () => {
    return request({
        url: 'role/index',
        method: 'post'
    })
}

//新增权限接口
export const roleSave = (data) => {
    return request({
        url: 'role/save',
        method: 'post',
        data: data
    })
}

//获取权限详情
export const roleRead = (data) => {
    return request({
        url: 'role/read',
        method: 'post',
        data: data
    })
}

//修改角色接口
export const roleUpdate = (data) => {
    return request({
        url: 'role/update',
        method: 'post',
        data: data
    })
}

//删除角色接口
export const del = (data) => {
    return request({
        url: 'role/delete',
        method: 'post',
        data: data
    })
}