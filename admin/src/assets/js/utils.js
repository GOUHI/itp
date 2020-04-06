import Vue from 'vue'
import md5 from 'js-md5'
const $this = Vue.prototype

//消息提示
export function msg(msg, type = 1) {
    if (type == 1) {
        Vue.prototype.$Message.success(msg);
    } else if (type == 2) {
        Vue.prototype.$Message.warning(msg);
    } else if (type == 3) {
        $this.$Message.error(msg);
    }
}

//MD5加密
export function getMd5(string) {
    return string = md5(string)
}

//存入缓存
// type 1、存入session 2、存入local
export function setStorage(key, value, type = 1) {
    if (type === 1) {
        if (typeof value === 'object') {
            window.sessionStorage.setItem(key, JSON.stringify(value))
        } else {
            window.sessionStorage.setItem(key, value)
        }
    } else {
        if (typeof value === 'object') {
            window.localStorage.setItem(key, JSON.stringify(value))
        } else {
            window.localStorage.setItem(key, value)
        }
    }
}

//读取缓存
// type 1、存入session 2、存入local
export function getStorage(key, type = 1) {
    try {
        if (type === 1) {
            return JSON.parse(window.sessionStorage.getItem(key))
        } else {
            return JSON.parse(window.localStorage.getItem(key))
        }
    } catch (error) {
      return null
    }
}


/**
 * 删除指定本地存储
 * @param key
 * @param type   1、session  2、local
 * @constructor
 */
export function Del(key, type = 1) {
    if (type === 1) {
        return window.sessionStorage.removeItem(key);
    } else {
        return window.localStorage.removeItem(key);
    }
}

/**
 * 清空本地存储
 * @param type 1、session  2、local 3、所有
 * @constructor
 */
export function Clear(type = 1) {
    if (type === 1) {
        window.sessionStorage.clear();
    } else if(type === 2) {
        window.localStorage.clear();
    }else{
        window.localStorage.clear();
        window.sessionStorage.clear();
    }
}