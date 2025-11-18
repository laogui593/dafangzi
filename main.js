import App from './App'
import Vue from 'vue'

// 使用 VueX
import store from './store'
Vue.prototype.$store = store
// 使用 uView
import uView from 'uview-ui'
Vue.use(uView)
uni.$u.config.unit = 'rpx'
// 使用 uni-simple-router
import {
    router,
    RouterMount
} from '@/router/router.js' //路径换成自己的
Vue.use(router)
// 使用 utils 函数
import util from '@/utils/util'

//引入中英文切换插件vue-i18n
import VueI18n from "vue-i18n";
// 应用
Vue.use(VueI18n);
// if (!uni.getStorageSync('lang')) {
// 	uni.setStorageSync('lang', 'en-US')
// }
uni.setStorageSync('lang', 'zh-CN') // 默认并固定语言为中文
uni.setTabBarItem({
    index: 0,
    text: '首页'
})
uni.setTabBarItem({
    index: 1,
    text: '交易'
})
uni.setTabBarItem({
    index: 2,
    text: '账户'
})
uni.setTabBarItem({
    index: 3,
    text: '我的'
})
const i18n = new VueI18n({
    locale: 'zh-CN', // 语言标识
    messages: {
        "zh-CN": require("./lang/zh"), // 通过require引入中文语言包
        // "en-US": require("./lang/en"), // 通过require引入英文语言包
    },
})

Vue.prototype.$util = util;

Vue.config.productionTip = false


// 使用 filter 过滤
import mFilter from '@/utils/filters.js'
Object.keys(mFilter).forEach(key => {
    Vue.filter(key, mFilter[key])
});

App.mpType = 'app'
const app = new Vue({
    store,
    i18n,
    ...App
})

// 引入请求封装，将app参数传递到配置中
require('@/config/request.js')(app)

//v1.3.5起 H5端 你应该去除原有的app.$mount();使用路由自带的渲染方式
// #ifdef H5
RouterMount(app, router, '#app')
// #endif

// #ifndef H5
app.$mount(); //为了兼容小程序及app端必须这样写才有效果
// #endif