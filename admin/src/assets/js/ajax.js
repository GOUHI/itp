import axios from 'axios'
import router from '../../router/index'
import {msg} from './utils'
// import { Notification, MessageBox } from 'element-ui'
// import store from '../store'
// import { getToken } from '@/utils/auth'
// import Config from '@/config'

// 创建axios实例
const service = axios.create({
    //   baseURL: process.env.NODE_ENV === 'production' ? process.env.BASE_API : '/', // api 的 base_url
    baseURL: '/admin/',
    timeout: 20000 // 请求超时时间
})

// request拦截器
service.interceptors.request.use(
    config => {
        //判断token是否存在
        const token = window.sessionStorage.getItem('token')
        if (token) {
            config.headers['Authorization'] = token // 让每个请求携带自定义token 请根据实际情况自行修改
        }
        config.headers['Content-Type'] = 'application/json'
        return config
    },
    error => {
        // Do something with request error
        console.log(error) // for debug
        Promise.reject(error)
    }
)

// response 拦截器
service.interceptors.response.use(
    response => {
        const code = response.status
        if (code < 200 || code > 300) {
            Notification.error({
                title: response.message
            })
            return Promise.reject('error')
        } else {
            if(response.data.code == 200){
                return response.data
            }else if(response.data.code == 201){
                msg('请重新登录',3)
                router.push('/home')
            }else if(response.data.code == 202){
                msg('权限不足',3)
            }else if(response.data.code == 400){
                msg(response.data.msg,3)
            }else{
                msg('网络错误,请联系管理员',3)
            }
            return false;
        }
    },
    error => {
        let code = 0
        try {
            code = error.response.data.code
        } catch (e) {
            if (error.toString().indexOf('Error: timeout') !== -1) {
                // Notification.error({
                //     title: '网络请求超时',
                //     duration: 2500
                // })
                return Promise.reject(error)
            }
            if (error.toString().indexOf('Error: Network Error') !== -1) {
                Notification.error({
                    title: '网络请求错误',
                    duration: 2500
                })
                return Promise.reject(error)
            }
        }
        if (code === 401) {
            MessageBox.confirm(
                '登录状态已过期，您可以继续留在该页面，或者重新登录',
                '系统提示',
                {
                    confirmButtonText: '重新登录',
                    cancelButtonText: '取消',
                    type: 'warning'
                }
            ).then(() => {
                // store.dispatch('LogOut').then(() => {
                //   location.reload() // 为了重新实例化vue-router对象 避免bug
                // })
            })
        } else if (code === 403) {
            router.push({ path: '/401' })
        } else {
            const errorMsg = error.response.data.message
            if (errorMsg !== undefined) {
                Notification.error({
                    title: errorMsg,
                    duration: 3000
                })
            }
        }
        return Promise.reject(error)
    }
)
export default service
