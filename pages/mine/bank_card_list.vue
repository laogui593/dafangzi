<template>
	<view>
		<u-navbar title="银行卡管理" :titleStyle="{ color: '#fff' }" leftIconColor="#fff" leftIconSize="40" :autoBack="true"
			bgColor="#181f2f">
		</u-navbar>
		<view class="f_content">
			<view class="ul">
				<view class="li m-flex m-row-between" v-for="(item,index) in banks" :key="item.id">
					<view class="m-flex-1 pdd-left">
						<image class="img" src="../../static/images/yinlian.png" mode=""></image>
					</view>
					<view class="m-flex-1">
						{{item.bankName}}
					</view>
					<view class="m-flex-1 text-right pdd-right">
						{{item.name}}
					</view>
				</view>
			</view>
			<view class="sure">
				<button @click="goAddBank">绑定银行卡</button>
				<button @click="openKF(KF)">修改请联系客服</button>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		bankCard
	} from '@/config/api.js'
	export default {
		data() {
			return {
				banks: [],
				KF: uni.getStorageSync('KF')
			};
		},
		onShow() {
			this.postBankCard()
		},
		methods: {
			postBankCard() {
				bankCard({
					uid: uni.getStorageSync('uid')
				}).then(res => {
					console.log('bankCard', res);
					res.data.banks.map((item) => {
						item.bankName = item.bank + ' ' + '****' + item.account.substring(item.account
							.length - 4)
					})
					this.banks = res.data.banks;
					console.log('this.banks: ', this.banks)
				}).catch(err => {
					console.log('err: ', err)
				})
			},
			// 打开客服
			openKF(value) {
				window.open(value)
			},
			goAddBank(){
				this.$Router.push('/pages/mine/add_bank_card')
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
				height: 120rpx;
				line-height: 120rpx;
				background-color: #262633;
				border-radius: 15rpx;
				margin-bottom: 30rpx;

				.img {
					width: 100rpx;
					height: 100rpx;
				}

				.pdd-left {
					padding-left: 40rpx;
				}

				.pdd-right {
					padding-right: 40rpx;
				}
			}
		}

		.sure {
			height: auto;
			position: relative;

			button {
				margin: 0 auto;
				width: 90%;
				height: 60%;
				line-height: 70rpx;
				font-size: 32rpx;
				border-radius: 13rpx;
				background-color: #c73131;
				color: #fff;
				border: 0 none;
				margin-top: 40rpx;
			}
		}
	}
</style>
