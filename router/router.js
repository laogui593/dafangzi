// router.js
import {
	RouterMount,
	createRouter
} from 'uni-simple-router';

const router = createRouter({
	platform: process.env.VUE_APP_PLATFORM,
	routes: [...ROUTES]
});
//全局路由前置守卫
router.beforeEach((to, from, next) => {
	const uid = uni.getStorageSync('uid')
	const isLoginPath = to.path.includes('login')
	// console.log('uid: ', uid)
	// console.log('isLoginPath: ', isLoginPath)
	if (!uid) {
		if (!isLoginPath) {
			next('/pages/login/login')
		} else {
			next()
		}
	} else {
		next();
	}
});
// 全局路由后置守卫
router.afterEach((to, from) => {
	console.log('跳转结束')
})

export {
	router,
	RouterMount
}
