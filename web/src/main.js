import babelpolyfill from 'babel-polyfill'
import Vue from 'vue'
import App from './App'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
import VueRouter from 'vue-router'
import store from './vuex/store'
import Vuex from 'vuex'

import routes from './routes'

//请求模拟模块
//import Mock from './mock'
//Mock.bootstrap();

import 'font-awesome/css/font-awesome.min.css'

Vue.use(ElementUI)
Vue.use(VueRouter)
Vue.use(Vuex)


const router = new VueRouter({
    //mode: 'history',
    base: __dirname,
    routes
})

router.beforeEach((to, from, next) => {
    if (to.path == '/login') {
        sessionStorage.removeItem('userInfo');
    }
    let userInfo = sessionStorage.getItem('userInfo');
    if (!userInfo && to.path != '/login') {
        next({ path: '/login' });
    } else {
        next()
    }
})

router.afterEach(transition => {
});

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')