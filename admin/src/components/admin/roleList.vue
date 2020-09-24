<template>
  <div class="content">
    <h3>角色管理</h3>
    <!-- 表格头部 -->
    <div class="operating">
      <Button type="success" class="searchItem" @click="drawerOpen">添加</Button>
      <Poptip confirm title="确认删除嘛?" @on-ok="handleDeleteButton">
        <Button type="error" class="searchItem">删除</Button>
      </Poptip>
    </div>

    <!-- 表格区域 -->
    <div class="table">
      <Table
        row-key="id"
        ref="selection"
        border
        :columns="table.columns"
        :data="table.data"
        @on-select="handleTableSelectBySingle"
        @on-select-all="handleTableSelectByChange"
        @on-selection-change="handleTableSelectByChange"
        _showChildren
      >
        <template slot-scope="{ row }" slot="icon">
          <Icon :type="row.icon" />
        </template>
        <template slot-scope="{ row, index }" slot="action">
          <Button type="success" size="small" style="margin-right: 5px" @click="edit(row)">
            <Icon type="md-checkbox-outline" />
          </Button>
          <Poptip
            transfer
            placement="top-end"
            confirm
            title="确认删除嘛?"
            @on-ok="handleDeleteButton(row)"
          >
            <Button type="error" size="small">
              <Icon type="md-trash" />
            </Button>
          </Poptip>
        </template>
      </Table>
    </div>

    <!-- 抽屉区域 -->
    <Drawer
      :title="drawer.id > 0 ? '编辑角色' : '添加角色'"
      v-model="drawer.isShow"
      width="720"
      :mask-closable="false"
      :styles="drawer.styles"
      :closable="false"
    >
      <Form
        ref="ruleValidate"
        :rules="drawer.ruleValidate"
        :model="drawer.formData"
        :label-width="100"
      >
        <FormItem label="名称" label-position="right" prop="name">
          <Input v-model="drawer.formData.name" placeholder="请输入角色名称" />
        </FormItem>
        <FormItem label="描述" prop="desc">
          <Input v-model="drawer.formData.desc" type="textarea" placeholder="请输入角色描述..." />
        </FormItem>
        <FormItem label="权限">
          <Tree :data="drawer.formData.menu" ref="menuTree" show-checkbox></Tree>
        </FormItem>
      </Form>
      <div class="demo-drawer-footer">
        <Button style="margin-right: 8px" @click="drawerCancel">
          <Icon type="md-exit" style="margin-right: 5px" />取消
        </Button>
        <Button type="info" @click="saveMenu" :loading="loading">
          <Icon type="md-folder" style="margin-right: 5px" />保存
        </Button>
      </div>
    </Drawer>
  </div>
</template>

<script>
import { roleIndex, roleSave, roleRead, roleUpdate, roleDel } from "../../assets/js/api/role";
import { getMenuList } from "../../assets/js/api/menu";
import { msg } from "../../assets/js/utils";
export default {
  data() {
    return {
      // 按钮的提交状态
      loading: false,
      // 搜索数据源
      searchData: {
        title: ""
      },
      // 表格数据结构
      table: {
        columns: [
          {
            type: "selection",
            width: 80,
            align: "center"
          },
          {
            title: "标识",
            key: "id",
            width: 100,
            sortable: true
          },
          {
            title: "名称",
            key: "name",
            tree: true,
            ellipsis: true
          },
          {
            title: "描述",
            key: "desc"
          },
          {
            title: "操作",
            slot: "action",
            width: 150,
            align: "center"
          }
        ],
        data: [],
        total: 0
      },

      // 抽屉数据
      drawer: {
        isShow: false,
        id: 0,
        styles: {
          height: "calc(100% - 55px)",
          overflow: "auto",
          paddingBottom: "53px",
          position: "static"
        },
        formData: {
          name: "",
          desc: "",
          menu: [],
          menuStr: ""
        },
        ruleValidate: {
          name: [
            {
              required: true,
              message: "名称必填",
              trigger: "blur"
            },
            {
              type: "string",
              min: 2,
              max: 10,
              message: "标题必须在2~10字节内",
              trigger: "blur"
            }
          ],
          desc: [
            {
              required: true,
              message: "描述必填",
              trigger: "blur"
            },
            {
              type: "string",
              min: 2,
              max: 20,
              message: "描述必须在2~200字节内",
              trigger: "blur"
            }
          ]
        }
      },

      // 表格多选
      ids: []
    };
  },
  mounted() {
    this.init();
  },
  methods: {
    // 初始化数据
    init() {
      this.getRoleList();
    },
    // 获取菜单列表数据
    async getRoleList(page) {
      const { data: res, total: total } = await roleIndex();
      this.table.data = res;
      this.table.total = total;
    },
    //获取详情中tree数据
    async getMenuListByAll() {
      const { data: res } = await getMenuList();
      this.drawer.formData.menu = res;
    },
    //获取详情中数据（单个角色）
    async getRoleInfo(id) {
      const { data: res } = await roleRead({ id: id });
      //进行数据赋值
      this.drawer.id = res.id;
      this.drawer.formData.name = res.name;
      this.drawer.formData.desc = res.desc;
      this.drawer.formData.menu = res.menu;
    },
    //打开抽屉获取数据
    drawerOpen() {
      this.drawer.isShow = true;
      this.getMenuListByAll();
    },
    // 关闭抽屉清除数据
    drawerCancel() {
      this.drawer.isShow = false;
      this.drawer.id = 0;
      this.$refs["ruleValidate"].resetFields();
      this.drawer.formData = {
        name: "",
        desc: "",
        menu: [],
        menuStr: ""
      };
    },
    // 表格单选项
    handleTableSelectBySingle(selection, row) {
      if (this.ids.lenght <= 0) {
        this.ids = row.id;
      } else {
        this.ids.push(row.id);
      }
    },
    // 表格修改项
    handleTableSelectByChange(selection) {
      if (selection.length > 0) {
        let ids = [];
        selection.forEach(item => {
          ids.push(item.id);
        });
        this.ids = ids;
      } else {
        this.ids = [];
      }
    },
    // 删除按钮事件
    async handleDeleteButton(row) {
      let ids = [];
      if (row) {
        ids[0] = row.id;
      } else {
        if (this.ids.length === 0) {
          this.msg("请勾选需要删除项", 3);
          return false;
        } else {
          ids = this.ids;
        }
      }

      // 定义接收参数
      let res = "";
      //删除操作
      res = await roleDel({ ids: ids });
      // 判断是否成功，成功后清除抽屉等数据
      if (res.code == 200) {
        this.getRoleList();
        msg(res.msg);
      }
    },
    // 编辑操作
    edit(data) {
      this.drawer.isShow = true;
      this.getRoleInfo(data.id);
    },
    // 保存操作
    saveMenu() {
      // 获取树形结构选中的节点
      let treeItem = this.$refs.menuTree.getCheckedNodes();
      // 判断权限是否勾选
      if (treeItem.length <= 0) {
        this.$msg("权限必须填写", 3);
        return false;
      }
      // 初始化需要接受树形结构的数组
      let menuTreeList = [];
      // 数据赋值
      treeItem.forEach(item => {
        menuTreeList.push(item.id);
      });
      //把数据赋值给formData中
      //   this.drawer.formData.menu = menuTreeList
      this.drawer.formData.menuStr = menuTreeList.toString();
      // console.log(this.drawer,menuTreeList);
      // return false;

      // 定义接收参数
      let res = "";
      // 进行表单验证
      this.$refs["ruleValidate"].validate(async valid => {
        // 判断验证是否成功
        if (!valid) return false;
        //打开loading
        this.loading = true;

        // 判断是编辑还是新增
        if (this.drawer.id == 0) {
          res = await roleSave(this.drawer.formData);
        } else {
          // 把需要修改的id存入数组中
          this.drawer.formData.id = this.drawer.id;
          res = await roleUpdate(this.drawer.formData);
        }

        // 判断是否成功，成功后清除抽屉等数据
        if (res.code == 200) {
          this.loading = false;
          this.drawerCancel();
          this.getRoleList();
          msg(res.msg);
        } else {
          this.loading = false;
        }
      });
    }
  }
};
</script>

<style lang="less" scoped>
.content {
  padding: 20px;
  .search,
  .operating,
  .table,
  .page {
    padding-top: 20px;
  }
}

.page {
  display: flex;
  justify-content: flex-end;
}

// 搜索条件
.searchItem {
  margin-right: 20px;
}

.demo-drawer-footer {
  width: 100%;
  position: absolute;
  bottom: 0;
  left: 0;
  border-top: 1px solid #e8e8e8;
  padding: 10px 16px;
  text-align: right;
  background: #fff;
}
h3 {
  padding-bottom: 20px;
  font-size: 16px;
  color: 000;
  border-bottom: 1px solid #eee;
}
</style>