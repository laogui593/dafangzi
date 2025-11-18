<template>
	<view>
		<u-navbar title="充值" :titleStyle="{ color: '#fff' }" leftIconColor="#fff" leftIconSize="40" :autoBack="true"
			bgColor="#181f2f"></u-navbar>
		<view class="t_con_pay">
			<view class="t_money">
				<i class="icon"></i>
				<label class="lab">请联系客服充值</label>
			</view>
			<view class="payment">
				<view class="p_ul">
					<view class="p_li repeat_active">
						<i class="p_i"></i>
						<view>客服</view>
						<i class="p_i"></i>
					</view>
					<view class="p_li repeat_active">
						<i class="p_i"></i>
						<view>网站客服</view>
						<i class="p_i"></i>
					</view>
					<view class="p_li repeat_active">
						<i class="p_i"></i>
						<view @click="copy(user.phone)">收款账号：
							<view class="card">
								{{user.phone}}
							</view>
						</view>
						<i class="p_i"></i>
					</view>
					<view class="p_li repeat_active">
						<i class="p_i"></i>
						<view @click="copy(user.name)">收款姓名：
							<view class="card">
								{{user.name}}
							</view>
						</view>
						<i class="p_i"></i>
					</view>
				</view>
			</view>
			<view class="t_money">
				<i class="icon"></i>
				<label class="lab">提示：请填写转账信息</label>
			</view>
			<view class="payment othermon">
				<view class="p_ul">
					<view class="p_li repeat_active">
						<text :decode="true" class="p_text">充值金额：&nbsp;</text>
						<input type="text" v-model="form.money" class="p_input">
					</view>
					<view class="p_li repeat_active">
						<text :decode="true" class="p_text">真实姓名：&nbsp;</text>
						<input type="text" v-model="form.truename" class="p_input">
					</view>
					<view class="p_li repeat_active">
						<text :decode="true" class="p_text">汇款凭证：&nbsp;</text>
						<input type="text" v-model="form.reason" class="p_input">
					</view>
				</view>
			</view>
			<view class="t_pay_btn">
				<button @click="recharges">提交</button>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		recharge,
		recharges
	} from '@/config/api.js'
	export default {
		data() {
			return {
				money: '',
				truename: '',
				reason: '',
				rechargeInfo: {},
				user: {},
				form: {
					uid: uni.getStorageSync('uid'),
					money: '',
					truename: '',
					reason: '',
					type: 'bank'
				}
			}
		},
		onLoad() {
			this.recharge()
		},
		methods: {
			// 账户信息
			recharge() {
				recharge({
					params: {
						uid: uni.getStorageSync('uid')
					}
				}).then((res) => {
					console.log('res: ', res)
					this.rechargeInfo = res.data
					this.user = res.data.user
				}).catch(err => {
					console.log('err: ', err)
				})
			},
			//复制到剪切板
			copy(value) {
				uni.setClipboardData({
					data: value,
					success: function() {
						uni.$u.toast($t('language.recharge.copy_suc'))
					}
				});
			},
			// 充�?
			recharges() {
				recharges(this.form).then((res) => {
					console.log('res: ', res)
					uni.$u.toast(res.info)
					setTimeout(() => {
						this.$Router.push('/pages/mine/recharge_record')
					}, 2000)
				}).catch(err => {
					console.log('err: ', err)
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.t_con_pay {
		margin-top: 44px;
		overflow-y: scroll;

		.t_money {
			height: 84rpx;
			margin: 20rpx 40rpx;

			.icon {
				display: block;
				width: 32rpx;
				height: 32rpx;
				background: url('../../static/images/xiazai.png') no-repeat;
				background-size: cover;
				float: left;
				margin-top: 28rpx;
			}

			.lab {
				height: 84rpx;
				line-height: 84rpx;
				display: block;
				float: left;
				margin-left: 10rpx;
				color: #fff;
				font-size: 24rpx;
			}
		}

		.payment {
			margin: 0 40rpx;
			background: #262633;
			padding: 2rpx 32rpx;

			.p_ul {
				.p_li {
					font-size: 28rpx;
					margin: 32rpx 0;
					color: #fff;
					display: flex;
					flex-direction: row;

					.p_text {
						color: #fff;
						margin-left: 14rpx;
					}

					.p_input {
						padding-left: 10rpx;
						background: #181f2f;
						color: #fff;
						padding: 4rpx;
						line-height: 1.6;
						border: none;
						outline: none;
					}

					.card {
						display: inline-block;
					}

					.p_i {
						font-style: normal;
					}
				}
			}
		}


		.t_pay_btn {
			margin: 40rpx;

			button {
				width: 100%;
				background-color: #edd33a;
				font-size: 32rpx;
				height: 84rpx;
				color: #111;
				border: 0 none;
				border-radius: 10rpx;
				outline: none;
			}
		}
	}
</style>
