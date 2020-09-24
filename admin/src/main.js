import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './plugins/iview.js'

//引入全局样式表
import './assets/css/global.css'

//引入全局js方法
import { msg } from "./assets/js/utils";
Vue.prototype.$msg = msg

// 导入网络请求
// import axios from 'axios'
// //配置请求的根路径
// axios.defaults.baseURL = 'https://www.liulongbin.top:8888/api/private/v1/'
// // 为请求头中添加token
// axios.interceptors.request.use(config =>{
//   config.headers.Authorization = window.sessionStorage.getItem('token')
//   return config
// })
// Vue.prototype.$http = axios



Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
