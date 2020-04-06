<template>
  <div class="content">
    <h3>菜单管理</h3>
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
    <div class="operating">
      <Button type="success" class="searchItem" @click="drawer.isShow = true">添加</Button>
      <Poptip confirm title="确认删除嘛?" @on-ok="handleDeleteButton">
        <Button type="error" class="searchItem">删除</Button>
      </Poptip>
    </div>
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
    <!-- <div class="page">
      <Page :total="table.total" @on-change="getMenuList" show-total show-elevator show-sizer />
    </div> -->

    <!-- 抽屉区域 -->
    <Drawer
      :title="drawer.id > 0 ? '编辑菜单' : '添加菜单'"
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
        <FormItem label="上级菜单" label-position="top">
          <Select v-model="drawer.formData.p_id">
            <Option value="0">顶级菜单</Option>
            <Option
              v-for="item in select.list"
              :value="item.value"
              :key="item.value"
              style="display:none;"
            >{{ item.title }}</Option>
            <Tree :data="table.data" @on-select-change="onSelectChange"></Tree>
          </Select>
        </FormItem>
        <!-- </div> -->
        <FormItem label="标题" label-position="right" prop="title">
          <Input v-model="drawer.formData.title" placeholder="请输入菜单标题" />
        </FormItem>
        <FormItem label="key" prop="key">
          <Input v-model="drawer.formData.key" placeholder="请输入菜单key" />
        </FormItem>
        <FormItem label="icon">
          <Input v-model="drawer.formData.icon" placeholder="请输入菜单icon(md-add)">
            <span slot="append" v-if="drawer.formData.icon == ''">
              <a
                target="_blank"
                href="https://www.iviewui.com/components/icon"
                style="font-size:12px;color:#515a6e;"
              >参考方案</a>
            </span>
            <span slot="append" v-else>
              <Icon :type="drawer.formData.icon" />
            </span>
          </Input>
        </FormItem>
        <FormItem label="跳转">
          <Input v-model="drawer.formData.to" placeholder="请输入菜单跳转路由" />
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
import { index, save, update, del } from "../../assets/js/api/menu";
import { LoadingBarConfig } from "view-design";
import { login } from "../../assets/js/api/login";
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
            title: "标题",
            key: "title",
            tree: true,
            ellipsis: true
          },
          {
            title: "key",
            key: "key"
          },
          {
            title: "图标",
            key: "icon",
            slot: "icon",
            width: 80,
            align: "center"
          },
          {
            title: "路由",
            key: "to"
          },
          {
            title: "操作",
            slot: "action",
            width: 150,
            align: "center"
          }
        ],
        data: [],
        total:0,
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
          p_id: "",
          title: "",
          key: "",
          icon: "",
          to: ""
        },
        ruleValidate: {
          title: [
            {
              required: true,
              message: "标题必填",
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
          key: [
            {
              required: true,
              message: "key必填",
              trigger: "blur"
            },
            {
              type: "string",
              min: 2,
              max: 20,
              message: "key必须在2~20字节内",
              trigger: "blur"
            }
          ]
        }
      },

      // 抽屉下拉选择数据
      select: {
        list: []
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
      this.getMenuList();
    },
    // 获取菜单列表数据
    async getMenuList(page) {
      const { data: res,total:total } = await index();
      this.table.data = res;
      this.table.total = total;
    },
    // 关闭抽屉清除数据
    drawerCancel() {
      this.drawer.isShow = false;
      this.drawer.id = 0;
      this.select.list = [];
      this.$refs["ruleValidate"].resetFields();
      this.drawer.formData = {
        p_id: "",
        title: "",
        key: "",
        icon: "",
        to: ""
      };
    },
    // 下拉树选择操作
    onSelectChange(e) {
      let value = "";
      let title = "";
      this.select.list = [];
      value = e[0].id;
      title = e[0].title;
      this.select.list.push({
        value,
        title
      });
      this.drawer.formData.p_id = value;
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
      res = await del({ids:ids})
      // 判断是否成功，成功后清除抽屉等数据
        if (res.code == 200) {
          this.getMenuList();
          msg(res.msg);
        }
    },
    // 编辑操作
    edit(data) {
      this.drawer.isShow = true;
      this.drawer.id = data.id;
      this.drawer.formData = {
        p_id: data.p_id,
        title: data.title,
        key: data.key,
        icon: data.icon,
        to: data.to
      };
      // 获取上级菜单的title值
      let title = "";
      this.table.data.forEach(item => {
        if (item.id === data.p_id) {
          title = item.title;
        }
      });

      this.select.list.push({
        value: data.p_id,
        title: data.p_id == 0 ? data.title : title
      });
      // console.log(data);
    },
    // 保存操作
    saveMenu() {
      // 判断p_id是否存在
      if (this.drawer.formData.p_id === "") {
        msg("上级菜单为必填项", 3);
        return false;
      }

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
          res = await save(this.drawer.formData);
        } else {
          // 把需要修改的id存入数组中
          this.drawer.formData.id = this.drawer.id;
          res = await update(this.drawer.formData);
        }

        // 判断是否成功，成功后清除抽屉等数据
        if (res.code == 200) {
          this.loading = false;
          this.drawerCancel();
          this.getMenuList();
          msg(res.msg);
        } else {
          this.loading = false;
        }
      });
    },
    //删除操作
    delete() {
      console.log("delete");
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