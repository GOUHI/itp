import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../components/Home.vue'
import Console from '../components/Console.vue'
import AdminList from '../components/admin/adminList.vue'
import MenuList from '../components/admin/menuList.vue'
import RoleList from '../components/admin/roleList.vue'
import Login from '../components/Login.vue'
import userList from '../components/User.vue'
import { getStorage, setStorage } from '../assets/js/utils'
import $store from '../store'

// 引入进度条
import NProgress from 'nprogress' // Progress 进度条
import 'nprogress/nprogress.css' // Progress 进度条样式

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    redirect: 'home'
  },
  {
    path: '/login',
    component: Login
  },
  {
    path: '/home', component: Home, redirect: '/console', children: [
      { path: '/console', meta: { title: '控制台' }, component: Console },
      { path: '/adminList', meta: { title: '管理员配置' }, component: AdminList },
      { path: '/menuList', meta: { title: '菜单配置' }, component: MenuList },
      { path: '/roleList', meta: { title: '角色配置' }, component: RoleList },
      { path: '/userList', meta: { title: '用户配置' }, component: userList },
    ]
  }
]

const router = new VueRouter({
  routes
})

// 挂载路由导航守卫
router.beforeEach((to, from, next) => {
  NProgress.start()
  if (to.path == '/login') return next()

  //获取token
  const token = getStorage('token') || ''
  if (!token) {
    return next('/login')
  }

  // 获取local存储的导航数据
  let navTag = getStorage('navTag', 2)

  // 通过路由获取导航参数并且整理成需要的格式
  let info = {
    text: to.meta.title,
    path: to.path,
  };

  // 初始化是否有值参数
  let isTag = 0;
  // 判断导航数据是否有值，没有值就给初始化值
  navTag.list.forEach(item => {
    if (item.text === to.meta.title) {
      isTag++;
    }
  });

  // 如果不是重复路由，新增导航进loacl和vuex
  if (isTag == 0) {
    // 把新的导航数据push到原来的数组里面
    navTag.list.push(info);
    // 把新的定位赋值进去
    navTag.index = navTag.list.length - 1
    // 存入local中
    setStorage("navTag", navTag, 2);
    // 存入vuex中
    $store.commit("pushItem", navTag);
  }

  next()
})

router.afterEach((to, from, next) => {
  NProgress.done()
})

export default router
