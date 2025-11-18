<template>
	<view class="content">
		<view class="sgin-in">
			<view class="m-flex input-box">
				<label class="input-title">账号</label>
				<input type="text" v-model="form.phone" placeholder="请输入账号" />
			</view>
			<view class="m-flex input-box">
				<label class="input-title">姓名</label>
				<input type="text" v-model="form.name" placeholder="请输入真实姓名" />
			</view>
			<view class="m-flex input-box">
				<label class="input-title">登录密码</label>
				<input type="text" v-model="form.password" placeholder="请输入登录密码" />
			</view>
			<view class="m-flex input-box">
				<label class="input-title">确认登录密码</label>
				<input type="text" v-model="form.password2" placeholder="请再次输入登录密码" />
			</view>
			<view class="m-flex input-box">
				<label class="input-title">支付密码</label>
				<input type="text" v-model="form.password3" placeholder="请输入支付密码" />
			</view>
			<view class="m-flex input-box">
				<label class="input-title">确认支付密码</label>
				<input type="text" v-model="form.password4" placeholder="请再次输入支付密码" />
			</view>
			<view class="m-flex input-box">
				<label class="input-title">推荐码</label>
				<input type="text" v-model="form.top" placeholder="请输入推荐码(选填)" />
			</view>
			<view class="btn">
				<button @click="sginUp">注册</button>
			</view>
			<view class="reg">
				<text>已有账号?</text>
				<text class="color-yellow" @click="goLogin">直接登录</text>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		reg
	} from "@/config/api.js"
	export default {
		data() {
			return {
				form: {
					phone: '', //账号
					name: '', //姓名
					password: '', //密码
					password2: '', //确认密码
					password3: '', //交易密码
					password4: '', //确认交易密码
				}
			}
		},
		onLoad() {
		},
		methods: {
			goLogin() {
				this.$Router.push('/pages/login/login')
			},
			sginUp() {
				reg(this.form, {
					custom: {
						modal: true
					}
				}).then(res => {
					console.log('res: ', res)
					uni.$u.toast(res.info)
					uni.setStorageSync('uid',res.data)
					setTimeout(()=>{
						this.$Router.pushTab('/pages/mine/mine')
					},1500)
				}).catch(err => {
					console.log('err: ', err)
				})
			}
		}
	}
</script>

<style scoped lang="scss">
	/* 移除 scoped 中的 page 背景，放到非 scoped 样式块 */
	.content {
		width: 100%;
		height: 100%;

		.sgin-in {
			width: 100%;
			height: 100%;
			box-sizing: border-box;
			padding: 60rpx 40rpx;

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
			}
		}
	}
</style>
<style lang="scss">
/* 全局 page 背景（注册页） */
page {
	background: url('/static/images/login.jpg') center/cover no-repeat;
	min-height: 100vh;
}
</style>