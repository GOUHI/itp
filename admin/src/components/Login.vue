<template>
  <div class="page-content bg-c-f7">
    <div class="page-account-header">
      <div class="page-login-top"></div>
    </div>
    <div class="page-account-container">
      <div class="page-account-top">
        <div class="page-account-top-logo">
          <img src="../assets/img/logo.png" alt="logo" />
        </div>
        <div class="page-account-top-desc">iView Admin Pro 企业级中台前端/设计解决方案</div>
      </div>
      <div class="login">
        <Form ref="formInfo" :model="formInfo" :rules="ruleInfo">
          <FormItem prop="account">
            <Input type="text" prefix="ios-contact-outline" v-model="formInfo.account" size="large"></Input>
          </FormItem>
          <FormItem prop="password">
            <Input
              type="password"
              prefix="ios-lock-outline"
              v-model="formInfo.password"
              size="large"
            ></Input>
          </FormItem>
          <FormItem>
            <Checkbox v-model="automatic" class="fl" size="large">自动记录</Checkbox>
            <a class="fr">忘记密码</a>
          </FormItem>
          <FormItem>
            <Button type="primary" size="large" long @click="handleSubmit('formInfo')">登录</Button>
          </FormItem>
        </Form>
        <!-- <div class="page-account-other">
          <span class="fl">
            其他登录方式
            <img src="../assets/img/icon-social-wechat.svg" alt="wechat" />
            <img src="../assets/img/icon-social-qq.svg" alt="qq" />
            <img src="../assets/img/icon-social-weibo.svg" alt="weibo" />
          </span>
          <a href class="fr">注册账户</a>
        </div>-->
      </div>
    </div>
    <div class="global-footer">Copyright © 2019 北京视图更新科技有限公司</div>
  </div>
</template>
<script>
import { login } from "../assets/js/api/login";
import { getMd5, msg, setStorage } from "../assets/js/utils";

export default {
  data() {
    return {
      // 表单数据
      formInfo: {
        account: "admin",
        password: "123456"
      },
      //   验证规则
      ruleInfo: {
        account: [
          {
            required: true,
            message: "请输入用户名",
            trigger: "blur"
          }
        ],
        password: [
          {
            required: true,
            message: "请输入密码",
            trigger: "blur"
          },
          {
            type: "string",
            min: 6,
            max: 15,
            message: "密码必须6~15位之间",
            trigger: "blur"
          }
        ]
      },
      automatic: true
    };
  },
  mounted() {},
  methods: {
    //表单提交
    handleSubmit(formName) {
      // 再次验证规则
      this.$refs[formName].validate(async valid => {
        if (!valid) {
          msg("请正确输入", 3);
          return false;
        }
        //处理密码字段
        // this.formInfo.password = getMd5(this.formInfo.password)
        const { data: res } = await login({
          account: this.formInfo.account,
          password: getMd5(this.formInfo.password)
        });

        setStorage("token", res.token);
        setStorage("menu", res.menu);
        setStorage(
          "navTag",
          {
            list: [],
            index: 0
          },
          2
        );
        // 进行跳转
        this.$router.push("/home");
      });
    }
  }
};
</script>

<style lang="less" scoped>
.page-content {
  background-image: url(../assets/img/body.8aa7c4a6.svg);
  background-repeat: no-repeat;
  background-position: 50%;
  background-size: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  height: 100vh;
  overflow: auto;
}

.page-account-container {
  flex: 1;
  padding: 32px 0 24px 0;
  text-align: center;
  width: 384px;
  margin: 0 auto;
  .page-account-top {
    padding: 32px 0;
    img {
      height: 75px;
    }
    .page-account-top-desc {
      font-size: 14px;
      color: #808695;
    }
  }
  .page-account-other {
    img {
      width: 24px;
      margin-left: 16px;
      cursor: pointer;
      vertical-align: middle;
      opacity: 0.7;
      transition: all 0.2s ease-in-out;
    }
  }
}

.global-footer {
  margin: 48px 0 24px 0;
  padding: 0 16px;
  text-align: center;
}
</style>