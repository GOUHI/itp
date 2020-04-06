<template>
  <div class="layout">
    <Layout>
      <Sider
        ref="side"
        hide-trigger
        collapsible
        :collapsed-width="78"
        v-model="isCollapsed"
        class="layout-sider"
        :style="{'background': '#fff','box-shadow':'1px 0px 5px #d7dde4'}"
      >
        <!-- logo区域 -->
        <div class="layout-logo">
          <a href="/">
            <img :src="imgClasses" alt />
          </a>
        </div>
        <!-- 菜单区域 -->
        <Menu
          :active-name="activeName"
          theme="light"
          width="auto"
          :class="menuitemClasses"
          accordion
          :open-names="openNames"
          @on-select="onSelect"
          ref="menu"
        >
          <!-- 一级菜单 -->
          <div v-for="firstItem in menuList" :key="firstItem.id">
            <Submenu :name="firstItem.key" :disabled="isSubmenu">
              <template slot="title">
                <Icon :type="firstItem.icon" />
                <span>{{firstItem.title}}</span>
              </template>
              <!-- 二级菜单 -->
              <div v-for="secondItem in firstItem.children" :key="secondItem.id">
                <Submenu v-if="secondItem.children" :name="secondItem.key">
                  <template slot="title">
                    <span>{{secondItem.title}}</span>
                  </template>
                  <!-- 三级菜单 -->
                  <MenuItem
                    :name="thirdItem.key"
                    :to="thirdItem.to"
                    v-for="thirdItem in secondItem.children"
                  >
                    <span>{{thirdItem.title}}</span>
                  </MenuItem>
                </Submenu>
                <MenuItem v-else :name="secondItem.key" :to="secondItem.to">
                  <span>{{secondItem.title}}</span>
                </MenuItem>
              </div>
            </Submenu>
          </div>
        </Menu>
      </Sider>
      <Layout>
        <Header :style="{padding: 0}" class="layout-header-bar">
          <div>
              <span>
                <Icon
                @click.native="collapsedSider"
                :class="rotateIcon"
                type="md-menu"
                size="18"
                ></Icon>
              </span>
              <span>
                <Icon type="md-refresh" size="18" @click="refreshPage"/></Icon>
              </span>
          </div>
          <div>
            <span>
              <Icon type="md-notifications-outline" size="18"/>
            </span>
            <span>
              <Dropdown @on-click="avatarMenu">
                  <a href="javascript:void(0)" style="color:#515a6e;">
                      <Avatar shape="square" size="small" src="https://i.loli.net/2017/08/21/599a521472424.jpg" style="padding: 0;margin-right: 5px;"/>
                      <span>李狗嗨</span>
                  </a>
                  <DropdownMenu slot="list">
                      <DropdownItem name="userInfo"><Icon type="md-person" style="margin-right:10px;"/>个人中心</DropdownItem>
                      <DropdownItem name="config"><Icon type="md-cog" style="margin-right:10px;"/>设置</DropdownItem>
                      <!-- <DropdownItem disabled>豆汁儿</DropdownItem> -->
                      <DropdownItem divided name="logOut"><Icon type="md-log-in" style="margin-right:10px;"/>退出</DropdownItem>
                  </DropdownMenu>
              </Dropdown>
            </span>
            <span>
              <Icon type="md-more" size="18"/>
            </span>
          </div>
            
        </Header>
        <Tap @getActiveName ="getActiveName"></Tap>
        <Content :style="{margin: '0px 20px 20px', background: '#fff', minHeight: '600px'}">
          <router-view></router-view>
        </Content>
      </Layout>
    </Layout>
  </div>
</template>
<script>
import { getMenuList } from "../assets/js/api/menu";
import { setStorage, getStorage, Clear, msg } from "../assets/js/utils";
import Tap from "./public/Tap";
export default {
  data() {
    return {
      //侧栏开关
      isCollapsed: false,
      //菜单开关
      isSubmenu: false,
      //导航定位菜单名称
      activeName: "",
      openNames: [],
      menuList: []
    };
  },
  computed: {
    rotateIcon() {
      return ["menu-icon", this.isCollapsed ? "rotate-icon" : ""];
    },
    imgClasses() {
      return this.isCollapsed
        ? "/assets/img/logo-small.png"
        : "/assets/img/logo.png";
    },
    menuitemClasses() {
      return ["menu-item", this.isCollapsed ? "collapsed-menu" : ""];
    }
  },
  mounted: function() {
    // 刷新获取菜单列表
    this.menuList = getStorage("menu");
    //刷新重新获取导航数据
    this.$store.commit("init");
  },
  methods: {
    collapsedSider() {
      //关闭展开的菜单
      this.openNames = [];
      this.$nextTick(() => {
        this.$refs.menu.updateOpened();
      });
      //禁止展开菜单栏
      this.isSubmenu = !this.isSubmenu;
      //收缩功能
      this.$refs.side.toggleCollapse();
    },
    // 退出操作
    avatarMenu(name) {
      if(name == 'userInfo'){
        msg('个人中心')
      }else if(name == 'config'){
        msg('设置')
      }else{
        // 退出操作
        Clear(3);
        this.$router.push("/login");
      }
    },
    // 菜单点击事件
    onSelect(name){
      // 获取laocl数据源
      let navTag = getStorage('navTag',2)
      // 循环判断一样的名称获取到index保存到loacl和vuex中
      navTag.list.forEach((item,index) => {
        if(item.path === '/'+name){
          navTag.index = index
          setStorage("navTag",navTag,2)
          this.$store.commit('pushItemByIndex',index)
        }
      });   
    },
    // 修改导航更改菜单栏激活状态
    getActiveName(data){
      this.activeName = data
    },
    // 刷新页面
    refreshPage(){
      this.$router.go(0);
    }
  },
  components: {
    Tap
  }
};
</script>

<style lang="less" scoped>
.layout {
  border: 1px solid #d7dde4;
  background: #f5f7f9;
  position: relative;
  border-radius: 4px;
  overflow: hidden;
  height: 100%;
  .ivu-layout {
    height: 100%;
  }
  .ivu-menu {
    position: unset;
  }
}
.layout-logo {
  height: 63px;
  line-height: 63px;
  text-align: center;
  img {
    height: 80%;
    vertical-align: middle;
  }
}

.layout-header-bar {
  background: #fff;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
  display: flex;
  // align-items:center;
  justify-content:space-between;
  span{
    display: inline-block;
    padding: 0 12px;
    cursor: Pointer;
    &:hover{
      background-color: #F8F8F9;
    }
  }
  Button{
    margin-right: 20px;
  }
}
.layout-logo-left {
  width: 90%;
  height: 30px;
  background: #5b6270;
  border-radius: 3px;
  margin: 15px auto;
}
.menu-icon {
  transition: all 0.3s;
}
.rotate-icon {
  transform: rotate(-90deg);
}
.menu-item span {
  display: inline-block;
  overflow: hidden;
  width: 100px;
  text-overflow: ellipsis;
  white-space: nowrap;
  vertical-align: bottom;
  transition: width 0.2s ease 0.2s;
}
.menu-item i {
  transform: translateX(0px);
  transition: font-size 0.2s ease, transform 0.2s ease;
  vertical-align: middle;
  font-size: 16px;
}
.collapsed-menu span {
  width: 0px;
  transition: width 0.2s ease;
}
.collapsed-menu img {
  width: 0px;
  transition: width 0.2s ease;
}
.collapsed-menu i {
  transform: translateX(5px);
  transition: font-size 0.2s ease 0.2s, transform 0.2s ease 0.2s;
  vertical-align: middle;
  font-size: 22px;
}
</style>