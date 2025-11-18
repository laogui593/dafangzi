<template>
	<view>
		<u-navbar :title="$t('language.history.title')" :titleStyle="{ color: '#fff' }" leftIconColor="#fff" leftIconSize="40" :autoBack="true"
			bgColor="#181f2f"></u-navbar>
		<view class="accound-tabs">
			<u-tabs :list="tabsList" @click="tabsChange" lineWidth="0" :activeStyle="{ color: '#fff' }"
				:inactiveStyle="{ color: '#828095' }" itemStyle="width:50%;padding:20rpx;"></u-tabs>
		</view>
		<view class="tabs-list">
			<view class="list-ul">
				<scroll-view :scroll-top="scrollTop" :show-scrollbar="true" scroll-y="true" class="scroll-Y"
					@scrolltolower="lower">
					<view class="list-li m-row-between" v-for="(item, index) in orderList" :key="item.id">
						<view style="padding-left: 20rpx;">
							<view class="s1">
								<text class="ng-binding">{{item.ptitle}}</text>
								<text class="ng-binding in_money" v-if="item.ploss > 0">
									<i class="buytop"></i>
									{{$t('language.history.buy_u')}}（{{$t('language.order_time.symbol')}}{{item.fee}}）
								</text>
								<text class="ng-binding out_money" v-if="item.ploss < 0">
									<i class="buydown"></i>
									{{$t('language.history.buy_d')}}（{{$t('language.order_time.symbol')}}{{item.fee}}）
								</text>
								<text class="ng-binding" v-if="item.ploss == 0">
									<i class="buydown"></i>
									{{$t('language.history.buy_d')}}（{{$t('language.order_time.symbol')}}{{item.fee}}）
								</text>
							</view>
							<view class="ng-binding s2">
								{{item.buyprice}}-
								<text class="ng-binding" :class="item.ploss == 0?'':item.ploss>0?'in_money':'out_money'">{{item.sellprice}}</text>
							</view>
							<view class="ng-binding s3">{{$u.timeFormat(item.buytime, 'yyyy-mm-dd hh:MM:ss')}}</view>
						</view>
						<view>
							<view class="ng-binding text-right in_money" v-if="item.ploss > 0">+{{item.ploss}}</view>
							<view class="ng-binding text-right out_money" v-if="item.ploss < 0">{{item.ploss}}</view>
							<view class="ng-binding text-right" v-if="item.ploss == 0">{{item.ploss}}</view>
							<view class="ng-binding text-right">{{$u.timeFormat(item.selltime, 'yyyy-mm-dd hh:MM:ss')}}
							</view>
						</view>
					</view>
				</scroll-view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		account,
		ajaxorderList
	} from '@/config/api.js'
	export default {
		data() {
			return {
				isShow: true,
				tabsList: [{
						id: 0,
						name: this.$t('language.history.detial_ha')
					},
					{
						id: 1,
						name: this.$t('language.history.detial_hi')
					}
				],
				scrollTop: 0,
				orderList: [],
				form: {
					uid: uni.getStorageSync('uid'),
					type: 1,
					page: 1,
					num: 30
				}
			};
		},
		onLoad() {
			this.ajaxorderList() 
		},
		methods: {
			// 交易记录
			ajaxorderList() {
				ajaxorderList(this.form).then((res) => {
					console.log('res: ', res)
					this.orderList = [...this.orderList, ...res.data];
					this.form.page++
				}).catch(err => {
					console.log('err: ', err)
				})
			},
			// tabs切换
			tabsChange(item) {
				this.form.type = item.id;
				this.form.page = 1;
				this.orderList = [];
				this.ajaxorderList()
				console.log('this.form.type: ', this.form.type)
			},
			// 滚动到底部触发
			lower() {
				this.ajaxorderList();
			}
		}
	};
</script>

<style scoped lang="scss">
	page {
		background-color: #21212b !important;
	}

	.account {
		margin: 0 32rpx;

		.account-title {
			font-size: 18px;
			font-weight: 700;
			color: #fff;
			line-height: 37px;
		}

		.account-head {
			width: 100%;
			background: url(@/static/images/zhanghubg.png) no-repeat 30% 20%/960px;
			height: 296rpx;
			padding: 42rpx 32rpx;
			box-sizing: border-box;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			box-sizing: border-box;
			border-radius: 28rpx;

			.top {
				display: flex;
				justify-content: space-between;
				color: #fff;

				.top-left {
					position: relative;

					.t11 {
						font-size: 10px;
						font-weight: 700;
						color: #a5b7d3;
					}

					.t22 {
						font-size: 27px;
						line-height: 29px;
						margin-top: 10px;
						font-weight: 700;
					}

					.refresh {
						position: absolute;
						top: 20px;
						right: -50px;
						display: inline-block;
						height: 30px;
						line-height: 30px;
						font-size: 12px;
						color: #a5b7d3;
					}
				}

				.top-right {
					width: 26rpx;

					image {
						width: 100%;
						-webkit-user-drag: none;
					}
				}
			}

			.down {
				display: flex;
				justify-content: space-between;

				.it {
					.h5 {
						font-size: 10px;
						font-weight: 700;
						color: #a5b7d3;
						line-height: 18px;
					}

					.h6 {
						margin-top: 10px;
						font-size: 11px;
						line-height: 11px;
						font-weight: 700;
						color: #fff;
					}
				}
			}
		}

		.account-list {
			padding: 38rpx 0;

			.ul {
				display: flex;
				flex-wrap: wrap;
				justify-content: space-between;

				.li {
					width: 25%;

					.navtor {
						padding: 10rpx 0;
						display: inline-block;

						image {
							width: 36rpx;
							vertical-align: middle;
							margin-right: 20rpx;
						}

						text {
							font-size: 22rpx;
							vertical-align: middle;
							font-weight: 700;
							color: #fff;
						}
					}
				}

				.left {
					text-align: left;
				}

				.center {
					text-align: center;
				}

				.right {
					text-align: right;
				}
			}
		}
	}

	.accound-tabs {
		width: 100%;
		border-bottom: 1px solid #181818;
		margin-top: 44px;
	}

	.tabs-list {
		width: 100%;
		font-size: 24rpx;

		.list-ul {
			width: 100%;

			.scroll-Y {
				height: calc(100vh - 84px);

				.list-li {
					display: flex;
					margin: 10rpx 0;

					.s1 {
						height: 40rpx;
					}

					.s2 {
						height: 40rpx;
					}

					.s3 {
						height: 40rpx;
						color: #696969;
					}

					.buydown {
						display: inline-block;
						width: 24rpx;
						height: 24rpx;
						background: url('@/static/images/buydown.png');
						background-size: 100% 100%;
						vertical-align: middle;
						margin-left: 100rpx;
						margin-right: 15rpx;
						vertical-align: middle;
					}

					.buytop {
						display: inline-block;
						width: 24rpx;
						height: 24rpx;
						background: url('@/static/images/buytop.png');
						background-size: 100% 100%;
						vertical-align: middle;
						margin-left: 100rpx;
						margin-right: 15rpx;
						vertical-align: middle;
					}
				}
			}
		}


		.in_money {
			color: #fa2e42 !important;
		}

		.out_money {
			color: #1fc65b !important;
		}

		.color-gray {
			color: #696969;
		}
	}
</style>
