import request from '../ajax';

//登录接口
export const login = (data) => {
  return request({
    url: 'login/index',
    method: 'post',
    data
  })
}