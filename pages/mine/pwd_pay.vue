<template>
	<view>
		<u-navbar :title="this.$t('language.pwd_pay.Modify_payment_password')" :titleStyle="{ color: '#fff' }" leftIconColor="#fff" leftIconSize="40" :autoBack="true"
			bgColor="#181f2f"></u-navbar>
		<view class="f_content">
			<view class="ul">
				<view class="li">
					<label>{{$t('language.pwd_pay.old_password')}}</label>
					<view class="input-box">
						<input type="password" v-model="form.oldpwd" :placeholder="this.$t('language.pwd_pay.Enter_the_original_password')" />
					</view>
				</view>
				<view class="li">
					<label>{{$t('language.pwd_pay.New_Password')}}</label>
					<view class="input-box">
						<input type="password" v-model="form.pwd" :placeholder="this.$t('language.pwd_pay.Enter_a_new_password')" />
					</view>
				</view>
				<view class="li">
					<label>{{$t('language.pwd_pay.Confirm_Password')}}</label>
					<view class="input-box">
						<input type="password" v-model="form.pwd2" :placeholder="this.$t('language.pwd_pay.input_confirmation')" />
					</view>
				</view>
			</view>
			<view class="sure">
				<button @click="postPwdPay">{{$t('language.pwd_pay.sure')}}</button>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		pwdPay
	} from '@/config/api.js'
	export default {
		data() {
			return {
				form: {
					uid: uni.getStorageSync('uid'), // uid
					oldpwd: '', // 旧密码
					pwd: '', // 新密码
					pwd2: '', // 确认新密码
				}

			};
		},
		methods: {
			postPwdPay() {
				pwdPay(this.form).then(res => {
					console.log('pwdPay', res);
					uni.$u.toast(res.info)
					setTimeout(() => {
						this.$Router.pushTab('/pages/mine/mine')
					}, 2000)
				}).catch(err => {
					console.log('err: ', err)
				})
			}
		}
	};
</script>

<style scoped lang="scss">
	.f_content {
		width: 100%;
		height: 100%;
		margin-top: 40px;
		overflow: scroll;
		-webkit-overflow-scrolling: touch;

		.ul {
			margin: 40rpx;
			display: flex;
			flex-direction: column;

			.li {
				display: flex;
				flex-direction: row;
				text-align: left;
				height: 92rpx;
				line-height: 92rpx;
				border-bottom: 1px solid #111;
				position: relative;

				label {
					flex: 2;
				}

				.input-box {
					flex: 4;
					position: relative;

					input {
						background: none;
						color: #fff;
						font-size: 14px;
						height: 100%;
					}
				}
			}
		}

		.sure {
			height: 162rpx;
			margin-top: 28rpx;
			position: relative;

			button {
				position: absolute;
				left: 50%;
				top: 50%;
				transform: translate(-50%, -50%);
				margin: 0 auto;
				width: 90%;
				height: 50%;
				font-size: 16px;
				border-radius: 13rpx;
				background-color: #edd33a;
				color: #333;
				border: 0 none;
			}
		}
	}
</style>
