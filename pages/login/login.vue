<template>
	<view>
		<view class="btn-outer">
			<!-- <picker mode="selector" :range="lang_arr" @change="langChangeHandle" range-key="title">
				<view class="langbtn">
					<image :src="'/static/images/'+lang_arr[current_lang].code+'.png'"
						style="width: 100%;height: 100%;"></image>
				</view>
			</picker> -->
			<view class="langbtn" @click="showSelector">
				<image :src="'/static/images/'+lang_arr[current_lang].code+'.png'" style="width: 100%;height: 100%;">
				</image>
			</view>
		</view>
		<view class="logo">
			<image src="/static/images/log1.png" mode="widthFix" class="logo-img"></image>
		</view>
		<view class="sgin-in">
			<view class="m-flex input-box">
				<label class="input-title">账号</label>
				<input type="text" v-model="form.phone" placeholder="请输入账号" />
			</view>
			<view class="m-flex input-box">
				<label class="input-title">密码</label>
				<input type="password" v-model="form.password" placeholder="请输入密码" />
			</view>
			<view class="btn">
				<button @click="sginIn">登录</button>
			</view>
			<view class="reg">
				<text>还没有账号?</text>
				<text class="color-yellow" @click="sginUP">立即注册</text>
			</view>
			<view class="reg">
				<text>在线人数：</text>
				<text class="color-red">{{randomMath}}</text>
				<text>人</text>
			</view>
			<view class="m-flex m-row-between">
				<view @click="forget">
					忘记密码
				</view>
				<view @click="openKF(KF)">
					联系客服
				</view>
			</view>
		</view>
		<view class="mask" v-if="show" @click="show=!show">
			<view class="picker">
				<view class="picker-btn">
					<view class="cancel" @click="show=!false">
						×
					</view>
					<!-- <view class="sure">
						√
					</view> -->
				</view>
				<view :style="{color:current_lang==index?'blue':'#000'}" class="text" v-for="(item,index) in lang_arr"
					:key="index" @click="langChangeHandle(index)">
					{{item.title}}
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		login,
		service
	} from "@/config/api.js"
	export default {
		data() {
			return {
				show: false,
				loading: false, // 添加登录加载状态
				lang_arr: [{
					title: '简体中文',
					code: 'zh-CN'
				}, {
					title: 'English',
					code: 'en-US'
				}],
				current_lang: 0,
				form: {
					phone: '',
					password: ''
				},
				timer: null,
				randomMath: 0,
				KF: ''
			}
		},
		onLoad() {
			this.current_lang = uni.getStorageSync('lang') == 'zh-CN' ? 0 : 1
			this.getRandomMath()
			this.getService()
		},
		onUnload() {
			if (this.timer) {
				clearTimeout(this.timer);
				this.timer = null;
			}
		},
		methods: {
			showSelector() {
				this.show = true
			},
			langChangeHandle(e) {
				// this.current_lang = e.detail.value
				this.current_lang = e
				this.$i18n.locale = this.lang_arr[this.current_lang].code
				uni.setStorageSync('lang', this.lang_arr[this.current_lang].code)
				uni.setTabBarItem({
					index: 0,
					text: this.current_lang == 0 ? '首页' : 'home'
				})
				uni.setTabBarItem({
					index: 1,
					text: this.current_lang == 0 ? '交易' : 'trade'
				})
				uni.setTabBarItem({
					index: 2,
					text: this.current_lang == 0 ? '账户' : 'account'
				})
				uni.setTabBarItem({
					index: 3,
					text: this.current_lang == 0 ? '我的' : 'mine'
				})
			},
			// 客服
			getService() {
				service().then(res => {
					console.log('客服', res);
					this.KF = res.data
					uni.setStorageSync('KF', res.data)
				})
			},
			// 登入
			sginIn() {
				const that = this;
				const {
					phone,
					password
				} = this.form;
				if (!phone) return uni.$u.toast('请输入用户名')
				if (!password) return uni.$u.toast('请输入密码')
				
				// 防止重复提交
				if (this.loading) return;
				this.loading = true;
				
				// 显示加载提示
				uni.showLoading({
					title: '登录中...',
					mask: true
				});
				
				login({
					phone: phone,
					password: password
				}, {
					custom: {
						toast: true
					}
				}).then((res) => {
					console.log('res: ', res)
					uni.hideLoading();
					this.loading = false;
					
					if (res.code == 1) {
					// 保存uid
					uni.setStorageSync('uid', res.data.id)
					uni.showToast({
						title: '登录成功',
							icon: 'success',
							duration: 2000,
							success: () => {
								setTimeout(() => {
									that.$Router.pushTab('/pages/index/index')
								}, 2000)
							}
						})
						// uni.showModal({
						// 	title: '温馨提示',
						// 	content: '登录成功',
						// 	showCancel: false,
						// 	success: res => {
						// 		that.$Router.pushTab('/pages/mine/mine')
						// 	},
						// });
					}
				}).catch((err) => {
					console.log('err: ', err)
					uni.hideLoading();
					this.loading = false;
				})
			},
		// 忘记密码
		forget() {
			uni.showModal({
				title: '提示',
				content: '请联系客服',
				confirmText:'确定',
					showCancel: false,
					success: res => {},
					fail: () => {},
					complete: () => {}
				});
			},
			// 打开客服
			openKF(value) {
				window.open(value)
			},
			// 注册
			sginUP() {
				this.$Router.push('/pages/login/reg')
			},
			// 获取随机数
			getRandomMath() {
				this.timer = setInterval(() => {
					this.randomMath = '412' + Math.ceil(Math.random() * 1000)
					// console.log('this.randomMath: ', this.randomMath)
				}, 1000)
			}
		}

	}
</script>

<style scoped lang="scss">
	/* 将 page 背景移到全局非 scoped 样式块，避免被后面的深色覆盖 */
	.mask {
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, 0.5);
		position: fixed;
		top: 0;
		left: 0;
		display: flex;
		flex-direction: column;
		justify-content: flex-end;

		.picker {
			padding: 30rpx;
			background-color: #fff;
			border-radius: 20rpx 20rpx 0 0;
			width: 100%;
			padding-bottom: 200rpx;
			color: #000;

			.picker-btn {
				display: flex;
				justify-content: flex-end;
				margin-bottom: 50rpx;
				font-size: 36rpx;
				font-weight: bold;

				.sure {
					color: blue;
				}
			}

			.text {
				padding: 20rpx;
				text-align: center;
			}
		}

	}

/* 删除了 scoped 内的第二个 page 背景色设置 */

	.btn-outer {
		display: flex;
		justify-content: flex-end;
		padding: 20rpx;
	}

	.langbtn {
		width: 50rpx;
		height: 50rpx;
		border-radius: 50%;
		overflow: hidden;
	}

	.logo {
		width: 100%;
		height: 450rpx;
		box-sizing: border-box;
		padding: 140rpx 0 0 30rpx;
		// border: 1px solid #fff;
		display: flex;
		    justify-content: center;
		    align-items: center;

		.logo-img {
			width: 300rpx;
		}
	}

	.sgin-in {
		width: 100%;
		height: 100%;
		box-sizing: border-box;
		padding: 48rpx 40rpx;
		// border: 1px solid #fff;

		.input-box {
			width: 100%;
			height: 116rpx;
			line-height: 116rpx;
			border-bottom: 1px solid $uni-border-color;
			margin-bottom: 44rpx;

			.input-title {
				flex: 0 0 120rpx;
				padding: 30rpx 60rpx 30rpx 30rpx;
			}
		}

		.btn {
			width: 100%;
			height: 100%;

			button {
				background-color: $uni-btn-color;
			}
		}

		.reg {
			margin: 60rpx 0;
			text-align: center;
			color: #fff;

			.color-yellow {
				color: $uni-text-color-yellow;
			}

			.color-red {
				color: red;
			}
		}
	}
</style>
<style lang="scss">
/* 全局 page 背景（非 scoped 才能真正作用于页面根节点） */
page {
	background: url('/static/images/login.jpg') center/cover no-repeat;
	min-height: 100vh;
}
</style>