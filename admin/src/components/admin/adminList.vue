<template>
  <div class="content">
    <h3>管理员配置</h3>
    <!-- <div class="search">
      <div class="clearfix">
        <Input
          v-model="searchData.title"
          placeholder="请输入菜单标题"
          style="width:auto"
          clearable
          class="fl searchItem"
        >
          <span slot="prepend">标题</span>
        </Input>

        <Input
          v-model="searchData.title"
          placeholder="请输入菜单标题"
          style="width:auto"
          class="fl searchItem"
        >
          <span slot="prepend">标题</span>
        </Input>

        <Button type="info" class="searchItem">搜索</Button>
        <Button type="warning" class="searchItem">重置</Button>
      </div>
    </div>-->
    <!-- 表格头部 -->
    <div class="operating">
      <Button type="success" class="searchItem" @click="drawer.isShow = true">添加</Button>
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
    <!-- <div class="page">
      <Page :total="table.total" @on-change="getAdminList" show-total show-elevator show-sizer />
    </div>-->

    <!-- 抽屉区域 -->
    <Drawer
      :title="drawer.id > 0 ? '编辑管理员' : '添加管理员'"
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
        <!-- <div style="border:1px solid red; padding:0 100px"> -->
        <FormItem label="账户" label-position="top" prop="account">
          <Input v-model="drawer.formData.account" placeholder="请输入账户" />
        </FormItem>
        <!-- </div> -->
        <FormItem label="密码" label-position="right" :prop="drawer.id === 0 ?'password':''">
          <Input v-model="drawer.formData.password" placeholder="不填不修改" type="password" />
        </FormItem>
        <FormItem label="分组" prop="role_id">
          <Select v-model="drawer.formData.role_id" filterable placeholder="可输入选择分组或者下拉选择">
            <Option v-for="item in roleList" :value="item.id" :key="item.id">{{ item.name }}</Option>
          </Select>
        </FormItem>
        <FormItem label="状态" prop="status">
          <RadioGroup v-model="drawer.formData.status">
            <Radio :label="1">正常</Radio>
            <Radio :label="2">黑名单</Radio>
          </RadioGroup>
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
import {
  adminIndex,
  adminSave,
  adminUpdate,
  adminDelete
} from "../../assets/js/api/admin";
import { roleIndex } from "../../assets/js/api/role";
import { LoadingBarConfig } from "view-design";
import { getMd5, msg } from "../../assets/js/utils";
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
        //列名
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
            title: "账户",
            key: "account",
            tree: true,
            ellipsis: true
          },
          {
            title: "创建时间",
            key: "create_time"
          },
          {
            title: "分组",
            key: "role_name"
          },
          {
            title: "状态",
            key: "status_name"
          },
          {
            title: "操作",
            slot: "action",
            width: 150,
            align: "center"
          }
        ],
        //数据
        data: [],
        //总条数
        total: 0
      },

      // 抽屉数据
      drawer: {
        //是否显示开关
        isShow: false,
        //判断是修改还是新增
        id: 0,
        styles: {
          height: "calc(100% - 55px)",
          overflow: "auto",
          paddingBottom: "53px",
          position: "static"
        },
        //表单数据
        formData: {
          account: "",
          password: "",
          role_id: "",
          status: 0
        },
        //表单验证
        ruleValidate: {
          account: [
            {
              required: true,
              message: "账户必填",
              trigger: "blur"
            },
            {
              type: "string",
              min: 2,
              max: 10,
              message: "账户必须在2~20字节内",
              trigger: "blur"
            }
          ],
          password: [
            {
              required: true,
              message: "密码必填",
              trigger: "blur"
            },
            {
              type: "string",
              min: 2,
              max: 15,
              message: "密码必须再2-15字节内",
              trigger: "blur"
            }
          ],
          role_id: [
            {
              required: true,
              type: "number",
              message: "分组必填",
              trigger: "blur"
            }
          ],
          status: [
            {
              required: true,
              type: "number",
              message: "状态必填",
              trigger: "blur"
            }
          ]
        }
      },

      // 抽屉下拉选择数据
      roleList: [],

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
      this.getAdminList();
      this.getRoleList();
    },
    // 获取菜单列表数据
    async getAdminList(page) {
      const { data: res, total: total } = await adminIndex();
      this.table.data = res;
      this.table.total = total;
    },

    // 获取角色列表数据
    async getRoleList(page) {
      const { data: res, total: total } = await roleIndex();
      this.roleList = res;
    },

    // 关闭抽屉清除数据
    drawerCancel() {
      this.drawer.isShow = false;
      this.drawer.id = 0;
      this.$refs["ruleValidate"].resetFields();
      this.drawer.formData = {
        account: "",
        password: "",
        role_id: "",
        status: ""
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
          msg("请勾选需要删除项", 3);
          return false;
        } else {
          ids = this.ids;
        }
      }

      // 定义接收参数
      let res = "";
      //删除操作
      res = await adminDelete({ ids: ids });
      // 判断是否成功，成功后清除抽屉等数据
      if (res.code == 200) {
        this.getAdminList();
        msg(res.msg);
      }
    },
    // 编辑操作
    edit(data) {
      this.drawer.isShow = true;
      this.drawer.id = data.id;
      this.drawer.formData = {
        account: data.account,
        role_id: data.role_id,
        status: data.status
      };
      // console.log(data);
    },
    // 保存操作
    saveMenu() {
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
          const self = this
          self.drawer.formData.password = getMd5(self.drawer.formData.password)
          res = await adminSave(self.drawer.formData);
        } else {
          // 把需要修改的id存入数组中
          this.drawer.formData.id = this.drawer.id;
          res = await adminUpdate(this.drawer.formData);
        }

        // 判断是否成功，成功后清除抽屉等数据
        if (res.code == 200) {
          this.loading = false;
          this.drawerCancel();
          this.getAdminList();
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