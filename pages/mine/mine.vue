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
		<view class="f_mine_box">
			<view class="f_mine">
			<view class="f_mine_head">
			<!-- 用户信息卡片 -->
			<view class="user-info-card">
				<view class="user-avatar">
					<image :src="verifiedInfo.headimg || verifiedInfo.avatar || '/static/images/touxiang.png'" mode="aspectFill"></image>
				</view>
				<view class="user-details">
					<view class="user-name">{{ verifiedInfo.name || '未设置' }}</view>
					<view class="user-id">账号: {{ verifiedInfo.phone || '-' }}</view>
					</view>
				</view>
				<!-- <view class="img_cion">
					<image src="/static/images/log1.png" mode=""></image>
				</view>
				<view class="xinyong">
					<text class="line">{{$t('language.mine.my_credit_score')}}</text>
				</view>
				<view class="jindu">
					<text class="fen">{{ verifiedInfo.xinyongfen }}分</text>
					<view class="jindutiao"></view>
				</view> -->
			</view>
				<view class="myslide">
					<u-swiper :list="swiperList" height="174rpx" radius="30" keyName="path"></u-swiper>
				</view>
				<view class="f_mine_body">
					<view class="f_table">
						<!-- <view class="item">
							<navigator url="/pages/mine/verified" class="nav">
								<image src="/static/images/idcard.png" mode=""></image>
								<view class="" v-if="verifiedInfo.rz_status == 0">
									{{$t('language.mine.Verified')}}
									<text style="background-color:#919189;margin-left:40rpx;padding: 0 15rpx;"
										:decode="true">&nbsp;&nbsp;{{$t('language.mine.not_certified')}}&nbsp;&nbsp;</text>
								</view>
								<view class="" v-if="verifiedInfo.rz_status == 1">
									{{$t('language.mine.Verified')}}
									<text style="background-color:#ffeb3b;margin-left:40rpx;padding: 0 15rpx;"
										:decode="true">&nbsp;&nbsp;{{$t('language.mine.under_review')}}&nbsp;&nbsp;</text>
								</view>
								<view class="" v-if="verifiedInfo.rz_status == 2">
									{{$t('language.mine.Verified')}}
									<text style="background-color:#39E230;margin-left:40rpx;padding: 0 15rpx;"
										:decode="true">&nbsp;&nbsp;{{$t('language.mine.verified')}}&nbsp;&nbsp;</text>
								</view>
							</navigator>
						</view> -->
						<view class="item">
							<navigator url="/pages/mine/set_account" class="nav">
								<image src="/static/images/my1.png" mode=""></image>
								<view class="">
									修改密码
								</view>
							</navigator>
						</view>
						<view class="item">
							<navigator url="" class="nav" @click="openKF(KF)">
								<image src="/static/images/my2.png" mode=""></image>
								<view class="">
									在线客服
								</view>
							</navigator>
						</view>
						<view class="item">
							<navigator url="/pages/mine/add_bank_card" class="nav">
								<image src="/static/images/my5.png" mode=""></image>
								<view class="">
									添加银行卡
								</view>
							</navigator>
						</view>
						<view class="item">
							<navigator url="/pages/mine/bank_card_list" class="nav">
								<image src="/static/images/my6.png" mode=""></image>
								<view class="">
									银行卡列表
								</view>
							</navigator>
						</view>
						<view class="item">
							<navigator url="" class="nav" @click="signOut">
								<image src="/static/images/my4.png" mode=""></image>
								<view class="">
									退出登录
								</view>
							</navigator>
						</view>
					</view>
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
		<!-- <u-picker v-model="show" mode="time"></u-picker> -->
		<!-- <u-picker mode="selector" v-model="show"  :default-selector="[0]" :range="lang_arr" range-key="title" @confirm="langChangeHandle"></u-picker> -->
	</view>
</template>

<script>
	import {
		url,
		getMenu,
		verified,
		service
	} from '@/config/api.js'
	export default {
		data() {
			return {
				show: false,
				lang_arr: [{
					title: '简体中文',
					code: 'zh-CN'
				}, {
					title: 'English',
					code: 'en-US'
				}],
				current_lang: 0,
				swiperList: [],
				KF: uni.getStorageSync('KF'),
				verifiedInfo: {},
				value: 650, // 假设当前值为 650
				percent: 0, // 初始进度为 0
				level: '', // 初始信用度等级为空
				valueColor: '', // 初始数值颜色为空
				levelColor: '' // 初始等级颜色为空
			};
		},
		onShow() {
			this.current_lang = uni.getStorageSync('lang') == 'zh-CN' ? 0 : 1
			uni.setTabBarItem({
				index: 0,
				text: uni.getStorageSync('lang') == 'zh-CN' ? '首页' : 'home'
			})
			uni.setTabBarItem({
				index: 1,
				text: uni.getStorageSync('lang') == 'zh-CN' ? '交易' : 'trade'
			})
			uni.setTabBarItem({
				index: 2,
				text: uni.getStorageSync('lang') == 'zh-CN' ? '账户' : 'account'
			})
			uni.setTabBarItem({
				index: 3,
				text: uni.getStorageSync('lang') == 'zh-CN' ? '我的' : 'mine'
			})
			this.getSwiper()
			this.getVerified()
			this.getService()
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
			// 实名信息查询接口
			getVerified() {
				verified({
					params: {
						uid: uni.getStorageSync('uid')
					}
				}).then(res => {
					console.log('实名信息', res);
					this.verifiedInfo = res.data
					this.value = res.data.xinyongfen
					// this.percent = Math.floor(this.value / 1000 * 100)
					if (this.value < 500) {
						this.level = this.$t('language.mine.bad_credit')
						this.levelColor = '#eb4a2f'
						this.valueColor = '#eb4a2f'
					} else if (this.value < 650 && this.value > 500) {
						this.level = this.$t('language.mine.Good_credit')
						this.levelColor = '#007aff'
						this.valueColor = '#007aff'
					} else if (this.value >= 650) {
						this.level = this.$t('language.mine.very_good_credit')
						this.levelColor = '#42b983'
						this.valueColor = '#42b983'
					}
				}).catch(err => {
					console.log('err: ', err)
				})
			},
			// 获取轮播图
			getSwiper() {
				getMenu().then(res => {
					console.log('轮播图', res);
					// 拼接路径
					res.data.map(value => {
						value.path = url + value.path
					})
					this.swiperList = res.data
				})
			},
			signOut() {
				uni.clearStorage()
				this.$Router.push('/pages/login/landing_page')
			},
			// 打开客服
			openKF(value) {
				window.open(value)
			},
		}
	};
</script>

<style scoped lang="scss">
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

	.btn-outer {
		display: flex;
		justify-content: flex-end;
		padding: 20rpx;
		width: 100%;
		position: absolute;
		top: 0;
		left: 0;
	}

	.langbtn {
		width: 80rpx;
		height: 80rpx;
		border-radius: 50%;
		overflow: hidden;
	}

	.f_mine_box {
		width: 100%;
		display: flex;
		flex-direction: column;

		.f_mine {
			color: #fff;

			.f_mine_head {
				background: url("../../static/images/myorderbkg.png") no-repeat 50% / cover;
				margin-bottom: 10px;
				padding: 40rpx 40rpx 60rpx;

				.img_cion {
					margin-right: 10px;

					image {
						width: 200rpx;
						height: 40rpx;
					}
				}

				.user-info-card {
					display: flex;
					align-items: center;
					padding: 40rpx 30rpx;
					
					.user-avatar {
						width: 120rpx;
						height: 120rpx;
						border-radius: 50%;
						overflow: hidden;
						background-color: #fff;
						margin-right: 30rpx;
						
						image {
							width: 100%;
							height: 100%;
						}
					}
					
					.user-details {
						flex: 1;
						
						.user-name {
							font-size: 36rpx;
							font-weight: bold;
							color: #fff;
							margin-bottom: 10rpx;
						}
						
						.user-id {
							font-size: 26rpx;
							color: rgba(255, 255, 255, 0.7);
						}
					}
				}

				.container {
					display: flex;
					flex-direction: column;
					align-items: center;

					.value {
						margin-top: 20rpx;
						font-size: 40rpx;
						color: #666;
					}

					.level {
						margin-top: 10rpx;
						font-size: 32rpx;
						color: #06c;
					}
				}

				.xinyong {
					font-size: 24rpx;
					margin-top: 36rpx;
					text-align: center;
					font-weight: 700;
					color: #fff;

					.line {
						position: relative;
						font-size: 26rpx;
						letter-spacing: 2rpx;
					}

					.line::before {
						position: absolute;
						content: "";
						width: 33px;
						top: 50%;
						right: -43px;
						height: 1px;
						background-color: #fff;
					}

					.line::after {
						position: absolute;
						content: "";
						width: 33px;
						top: 50%;
						left: -43px;
						right: -43px;
						height: 1px;
						background-color: #fff;
					}
				}

				.jindu {
					margin-top: 24px;
					height: 10px;
					background-color: #fff;
					border-radius: 11px;
					overflow: hidden;
					position: relative;

					.fen {
						position: absolute;
						right: 11px;
						z-index: 22;
						top: 0;
						font-size: 9px;
						line-height: 9px;
						font-weight: 700;
						color: #ffffff;
					}

					.jindutiao {
						background-image: url("../../static/images/jindubkg.png");
						background-repeat: no-repeat;
						height: 10px;
						border-radius: 3px;
						background-size: 102%;
					}
				}
			}

			.myslide {
				padding: 0 16px;
			}

			.f_mine_body {
				.f_table {
					margin: 20rpx 30rpx;
					padding: 10rpx 32rpx;
					background-color: #262633;
					border-radius: 32rpx;

					.item {
						color: #fff;
						background: url("../../static/images/arrow_right.png") no-repeat 98% 50%/6px auto;

						.nav {
							display: flex;
							height: 102rpx;
							align-items: center;

							image {
								width: 14px;
								height: 14px;
								margin-right: 20px;
							}
						}
					}
				}
			}
		}
	}
</style>