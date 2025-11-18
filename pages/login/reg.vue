<template>
	<view class="content">
		<view class="sgin-in">
			<view class="m-flex input-box">
				<label class="input-title">{{$t('language.register.account')}}</label>
				<input type="text" v-model="form.phone" :placeholder="this.$t('language.register.account_empty')" />
			</view>
			<view class="m-flex input-box">
				<label class="input-title">姓名</label>
				<input type="text" v-model="form.name" placeholder="请输入真实姓名" />
			</view>
			<view class="m-flex input-box">
				<label class="input-title">{{$t('language.register.login_password')}}</label>
				<input type="text" v-model="form.password" :placeholder="this.$t('language.register.login_password_empty')" />
			</view>
			<view class="m-flex input-box">
				<label class="input-title">{{$t('language.register.Confirm_login')}}</label>
				<input type="text" v-model="form.password2" :placeholder="this.$t('language.register.login_again')" />
			</view>
			<view class="m-flex input-box">
				<label class="input-title">{{$t('language.register.payment_password')}}</label>
				<input type="text" v-model="form.password3" :placeholder="this.$t('language.register.Payment_password_is_empty')" />
			</view>
			<view class="m-flex input-box">
				<label class="input-title">{{$t('language.register.confirm_payment')}}</label>
				<input type="text" v-model="form.password4" :placeholder="this.$t('language.register.re_enter')" />
			</view>
			<view class="m-flex input-box">
				<label class="input-title">{{$t('language.register.referral_code')}}</label>
				<input type="text" v-model="form.top" :placeholder="this.$t('language.register.Recommended_ID')" />
			</view>
			<view class="btn">
				<button @click="sginUp">{{$t('language.register.register_log_in')}}</button>
			</view>
			<view class="reg">
				<text>{{$t('language.register.Already_have_an_account')}}{{' '}}</text>
				<text class="color-yellow" @click="goLogin">{{$t('language.register.Direct_Login')}}</text>
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