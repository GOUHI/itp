import request from '../ajax';

//获取管理员列表
export const adminIndex = (data) => {
    return request({
        url: 'admin/index',
        method: 'post',
        data: data
    })
}

//新增管理员
export const adminSave = (data) => {
    return request({
        url: 'admin/save',
        method: 'post',
        data: data
    })
}

//修改管理员
export const adminUpdate = (data) => {
    return request({
        url: 'admin/update',
        method: 'post',
        data: data
    })
}

//删除管理员
export const adminDelete = (data) => {
    return request({
        url: 'admin/delete',
        method: 'post',
        data: data
    })
}