import Vue from 'vue'
import Vuex from 'vuex'
import { getStorage } from '../assets/js/utils'

Vue.use(Vuex)
import { Form } from 'view-design'

export default new Vuex.Store({
  state: {
    // 导航数据
    menuList: [],
    // 定位位置
    index: 0
  },
  mutations: {
    // 初始化导航数据
    init(state) {
      let navTag = getStorage('navTag', 2)
      state.menuList = navTag.list
      state.index = navTag.index
    },
    pushItemByIndex(state,index){
      state.index = index
    },
    // 存入导航数据
    pushItem(state, step) {
      state.menuList = step.list
      state.index = step.index
    }
  },
  actions: {
    getInit(context) {
      return context.commit('init')
    }
  },
  modules: {
  }
})
