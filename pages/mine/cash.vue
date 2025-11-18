<template>
	<view class="box">
		<view class="jun-content">
			<view class="t_box_withdraw">
				<view class="t_header">
					<u-navbar :title="$t('language.withdraw.title')" :titleStyle="{ color: '#fff' }" leftIconColor="#fff" leftIconSize="40"
						:autoBack="true" bgColor="rgba(255,255,255,0)">
					</u-navbar>
				</view>
				<view class="t_con_withdraw">
					<view class="one">
						<view class="one1">
							{{$t('language.withdraw.money_with')}}（{{$t('language.order_time.symbol')}})
						</view>
						<view class="one2">
							<em>{{$t('language.order_time.symbol')}}</em>
							<text>{{user.money}}</text>
						</view>
					</view>
					<view class="two">
						<view class="two1">
							{{$t('language.withdraw.money')}}
						</view>
						<view class="two2 m-flex">
							<em>{{$t('language.order_time.symbol')}}</em>
							<input type="text" v-model="form.money" :placeholder="$t('language.withdraw.money_input')">
						<label>{{$t('language.withdraw.handle_fee')}}</label>
						<text>{{ cashInfo.txsxf || 0 }}%</text>
						</view>
					<view class="two3">
						<text>最低提现金额</text>
						<text class="color-yellow">￥{{ cashInfo.cash_min || 100 }}</text>
					</view>
					</view>
					<view class="item three">
						<label for="paswd">支付密码</label>
						<input type="password" id="paswd" v-model="form.pwd" placeholder="请输入支付密码">
					</view>
					<view class="item three" v-if="user.idcard">
						<label for="paswd">选择银行卡</label>
						<ep-select label_key="banks" value_key="account" filterable keep-input v-model="select"
							:options="bankOptions" @change="change">
						</ep-select>
						<!-- <input type="text" id="card" :value="user.idcard | hiddenIdCard()" placeholder="请输入银行卡号"> -->
					</view>
					<view class="model-row" style="margin-top: 30rpx;">
						<u-button @click="postCashs" color="#edd33a" :customStyle="{color:'#000',fontSize:'32rpx'}"
							text="确认提现"></u-button>
					</view>
					<view class="model cash-hint">
						温馨提示:
						<view>1.提现限额：{{cashInfo.cash_min}}-{{cashInfo.cash_max}}</view>
						<view class="red">2.提现时间：{{cashInfo.cash_start}}-{{cashInfo.cash_end}}</view>
						<view>3.忘记密码<text class="red">请联系客服</text></view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		cash,
		cashs
	} from '@/config/api.js'
	export default {
		data() {
			return {
				money: '',
				truename: '',
				reason: '',
				cashInfo: {},
				user: {},
				form: {
					uid: '',
					bank: '',
					money: '',
					pwd: '',
				},
				select: '',
				bankOptions: [],
				isSubmitting: false // 防止重复提交
			}
		},
		onLoad() {
			// 在onLoad时获取uid
			const uid = uni.getStorageSync('uid')
			console.log('获取到的uid:', uid)
			this.form.uid = uid
			this.postCash()
		},
		methods: {
			// 账户信息
			postCash() {
				const uid = this.form.uid
				console.log('postCash 使用的uid:', uid)
				
				cash({
					params: {
						uid: uid
					}
				}).then((res) => {
					console.log('提现页面数据 res: ', res)
					this.cashInfo = res.data
					this.user = res.data.user
					this.bankOptions = res.data.bank
					console.log('银行卡列表 bankOptions: ', this.bankOptions)
					
					// 处理银行卡显示
					if(res.data.bank && res.data.bank.length > 0) {
						res.data.bank.map((item) => {
							item.banks = item.bank + ' ' + '****' + item.account.substring(item.account.length - 4)
						})
						// 默认选择第一张银行卡
						this.select = res.data.bank[0].account
						this.form.bank = res.data.bank[0].account
						console.log('默认选择银行卡账号: ', this.form.bank)
					} else {
						console.log('未绑定银行卡,bankOptions为空')
					}
				}).catch(err => {
					console.log('获取提现信息失败 err: ', err)
				})
			},
			// 提现
			postCashs() {
				// 防止重复提交
				if(this.isSubmitting) {
					console.log('正在提交中,请勿重复点击')
					return
				}
				
				console.log('开始提现')
				console.log('this.form:', JSON.stringify(this.form))
				console.log('uid:', this.form.uid)
				console.log('bank:', this.form.bank)
				console.log('money:', this.form.money)
				console.log('pwd:', this.form.pwd)
				
				// 前端基本验证
				if(!this.form.uid) {
					console.log('uid为空,重新获取')
					this.form.uid = uni.getStorageSync('uid')
				}
				
				if(!this.form.money) return uni.$u.toast('请输入提现金额')
				if(!this.form.pwd) return uni.$u.toast('请输入提现密码')
				if(!this.form.bank) return uni.$u.toast('请选择银行卡')
				
				console.log('验证通过,最终提交的form:', JSON.stringify(this.form))
				
				this.isSubmitting = true
				
				// 创建一个新对象传递,避免响应性问题
				const submitData = {
					uid: this.form.uid,
					bank: this.form.bank,
					money: this.form.money,
					pwd: this.form.pwd
				}
				
				console.log('即将调用 cashs 接口...')
				console.log('提交的数据 submitData:', JSON.stringify(submitData))
				
				const cashPromise = cashs(submitData)
				console.log('cashs 返回值:', cashPromise)
				
				cashPromise.then(res => {
					console.log('✅ 提现成功 res: ', res)
					this.isSubmitting = false
					uni.$u.toast(res.info)
					setTimeout(() => {
						this.$Router.push('/pages/mine/cash_record')
					}, 2000)
				}).catch(err => {
					console.log('❌ 提现失败 err: ', err)
					this.isSubmitting = false
					// HTTP拦截器已经自动显示了错误提示,这里不需要重复显示
					// 只需要记录日志即可
				})
				
				console.log('cashs 接口已调用,等待响应...')
			},
			// 下拉选择
			change(e) {
				console.log('select = ', this.select)
				this.form.bank = this.select
			},
		}
	}
</script>

<style lang="scss" scoped>
	.box {
		height: 100%;
		display: flex;
		flex-direction: column;

		.jun-content {
			height: 92%;
			width: 100%;
			display: flex;

			.t_box_withdraw {
				flex: 1;
				display: flex;
				flex-direction: column;
				background: url('../../static/images/dealBKG.png') no-repeat 50% -100px/745px;

				.t_con_withdraw {
					padding-top: 44px;

					.one {
						text-align: center;
						color: #fff;
						margin: 60rpx 0 100rpx;

						.one1 {
							font-size: 32rpx;
							font-weight: 500;
						}

						.one2 {
							font-size: 56rpx;
							font-weight: 700;

							em {
								font-size: 28rpx;
								vertical-align: baseline;
								font-style: normal;
							}

							text {
								display: inline-block;
							}
						}
					}

					.two {
						margin: 0 40rpx 80rpx;
						padding: 32rpx 22rpx;
						box-sizing: border-box;
						background: #262732;
						color: #fff;
						box-shadow: 0 14rpx 20rpx 2rpx #262732;
						border-radius: 10rpx;

						.two1 {
							display: block;
							margin-bottom: 20rpx;

						}

						.two2 {
							em {
								font-size: 48rpx;
								margin-right: 20rpx;
								font-style: normal;
							}

							input {
								background: transparent;
								color: #fff;
								font-size: 28rpx;
							}
						}

						.two3 {
							margin-top: 40rpx;
							font-size: 26rpx;
							font-weight: 400;
							color: #999;

							.color-yellow {
								color: #edd33a;
							}
						}
					}


					.item {
						margin: 0 40rpx;
						height: 94rpx;
						font-size: 28rpx;
						border-bottom: 1px solid #333;
						position: relative;
						display: flex;
						flex-direction: row;

						label {
							display: inline-block;
							margin-left: 28rpx;
							color: #fff;
							width: 360rpx;
							font-size: 22rpx;
							height: 100%;
							line-height: 94rpx;
						}

						input {
							background: transparent;
							color: #333;
							margin-top: 10rpx;
							height: 74rpx;
							font-size: 28rpx;
							text-indent: 52rpx;
							// background-color: #111723;
							border: none;
							outline: none;
							color: #fff;
						}
					}
				}
			}
		}
	}

	.cash-hint {
		.red {
			color: red;
		}
	}
</style>
