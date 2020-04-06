<template>
  <div class="tap-content">
    <div class="fl">
      <div v-for="(item,index) in $store.state.menuList" :key="index" class="fl">
        <Tag
          size="large"
          class="tap-item"
          closable
          v-if="$store.state.index === index"
          type="dot"
          color="primary"
          @on-close="onClose(index)"
        >{{item.text}}</Tag>
        <Tag
          size="large"
          class="tap-item"
          closable
          v-else
          @click.native="onClick(index,item)"
          @on-close="onClose(index)"
        >{{item.text}}</Tag>
      </div>
    </div>
    <div class="tap-down-menu fr">
      <Dropdown @on-click="closeMenu">
        <a href="javascript:void(0)">
          <Icon type="ios-arrow-down"></Icon>
        </a>
        <DropdownMenu slot="list">
          <DropdownItem name="closeLeft">
            <Icon type="md-arrow-round-back" />关闭左边
          </DropdownItem>
          <DropdownItem name="closeRight">
            <Icon type="md-arrow-round-forward" />关闭右边
          </DropdownItem>
          <DropdownItem name="closeOther">
            <Icon type="md-close" />关闭其他
          </DropdownItem>
          <DropdownItem name="closeAll">
            <Icon type="md-close-circle" />关闭全部
          </DropdownItem>
        </DropdownMenu>
      </Dropdown>
    </div>
  </div>
</template>

<script>
import { setStorage, getStorage, msg } from "../../assets/js/utils";
export default {
  data() {
    return {
      isDownMenu: false
    };
  },
  mounted() {},
  methods: {
    // 导航单个关闭函数
    onClose(index) {
      // 获取laocl中的导航值
      let navTag = getStorage("navTag", 2);
      // 判断是否最后一个导航，最后一个不容许删除
      if (navTag.list.length > 1 && index != 0) {
        // 判断当前index是否是激活的index
        if (navTag.index == index) {
          // 获取上一个定位数
          navTag.index = navTag.index - 1;
          // 通过index删除数组中的导航数组
          navTag.list.splice(index, 1);
          // 保存loacl和vuex中
          setStorage("navTag", navTag, 2);
          this.$store.commit("pushItem", navTag);
          // 进行跳转
          this.$router.push(navTag.list[index - 1].path);
        }else{
          // 通过index删除数组中的导航数组
          navTag.list.splice(index, 1);
          // 将导航定位往前移动一位
          navTag.index = navTag.index -1
          // 保存loacl和vuex中
          setStorage("navTag", navTag, 2);
          this.$store.commit("pushItem", navTag);
        }  
      }
    },
    // 导航关闭菜单操作方法
    closeMenu(name) {
      // 获取loacl数据的值

      // var numbersArr = [1,2,3,4,5,6];
      // numbersArr.splice(2,1);//第一个参数为数组序号，第二个参数为删除个数

      let navTag = getStorage("navTag", 2);
      let length = navTag.list.length;
      let index = navTag.index;
      let newNavTag = {list: []};

      if (name == "closeLeft") {
        //关闭左边
        navTag.list.splice(0, index,navTag.list[0]);
        navTag.index = 1
        setStorage('navTag',navTag,2)
        this.$store.commit('pushItem',navTag)
      } else if (name == "closeRight") {
        // 关闭右边
        navTag.list.splice(index+1, length-index);
        setStorage('navTag',navTag,2)
        this.$store.commit('pushItem',navTag)
      } else if (name == "closeOther") {
        // 关闭其他
        newNavTag.list.push(navTag.list[0])
        newNavTag.list.push(navTag.list[index])
        newNavTag.index = 1
        setStorage('navTag',newNavTag,2)
        this.$store.commit('pushItem',newNavTag)
      } else {
        // 关闭全部
        // 改变菜单栏目的激活状态
        this.$emit("getActiveName", '');
        newNavTag.list.push(navTag.list[0])
        newNavTag.index = 0
        setStorage('navTag',newNavTag,2)
        this.$store.commit('pushItem',newNavTag)
        this.$router.push('/')
      }
    },
    // 导航点击事件
    onClick(index, item) {
      // 改变菜单栏目的激活状态
      this.$emit("getActiveName", item.path.substr(1));
      // 获取导航数据
      let navTag = getStorage("navTag", 2);
      // 更新index为最新选择的
      navTag.index = index;
      // 同步到loacl和vuex中
      setStorage("navTag", navTag, 2);
      this.$store.commit("pushItemByIndex", index);
      // 路由跳转
      this.$router.push(item.path);
    }
  }
};
</script>

<style lang="less" scoped>
// tap盒子样式
.tap-content {
  height: 34px;
  margin: 10px 20px;
}

// 每一个tap标签的样式
.tap-item {
  background-color: #fff;
  color: red;
  cursor: Pointer;
}

// 下拉菜单样式
.tap-down-menu {
  text-align: center;
  background-color: #fff;
  width: 50px;
  line-height: 34px;
  margin-right: 10px;
  //   下拉菜单的icon样式
  i {
    margin-right: 5px;
  }
}
</style>