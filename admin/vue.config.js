module.exports = {
    pages: {
      index: {
        entry: 'src/main.js',
        template: 'index.html',
        filename: 'index.html',
        chunks: ['chunk-vendors', 'chunk-common', 'index']
      }
    },
    css: {
      loaderOptions: {
        less: {
          // globalVars
        }
      }
    },
    // configureWebpack: {
    //   resolve: {
    //     alias: {
    //       model: path.resolve(__dirname, 'src/js/model/'),
    //       js: path.resolve(__dirname, 'src/js/'),
    //       components: path.resolve(__dirname, 'src/components/')
    //     }
    //   },
    //   plugins: [
    //     new webpack.ProvidePlugin({
    //       R: [path.resolve(__dirname, 'src/js/common/request'), 'default'],
    //       C: [path.resolve(__dirname, 'src/js/config/config'), 'default']
    //     })
    //   ]
    // },
    devServer: {
      proxy: {
      // 此处应该配置为开发服务器的后台地址
      // 配置文档： https://cli.vuejs.org/zh/config/#devserver-proxy
      //   '/applet': {
      //     target: 'https://xyd.youline.cn/'
      //   },
        //测试
        '/admin': {
          target: 'http://www.itp.com/index.php/',
          changeOrigin: true,
        }
        //开发
        //   '/applet': {
        //     target: 'http://localhost:8763/'
        //   },
  
      }
    }
  };
  