<template>
	<view>
		<u-navbar title="账户安全" :titleStyle="{ color: '#fff' }" leftIconColor="#fff" leftIconSize="40" :autoBack="true"
			bgColor="#181f2f"></u-navbar>
		<view class="f_content">
			<view class="list">
				<view class="item">
					<text>用户名</text>
					<text>{{userInfo.name}}</text>
				</view>
				<view class="item">
					<navigator url="/pages/mine/pwd_login" class="nav">
						<text>修改登录密码</text>
						<!-- <u-icon name="arrow-right" color="#fff" size="28"></u-icon> -->
					</navigator>
				</view>
				<view class="item">
					<navigator url="/pages/mine/pwd_pay" class="nav">
						<text>修改支付密码</text>
						<!-- <u-icon name="arrow-right" color="#fff" size="28"></u-icon> -->
					</navigator>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		index
	} from '@/config/api.js'
	export default {
		data() {
			return {
				userInfo: {}
			};
		},
		onShow() {
			this.postIndex()
		},
		methods: {
			// 账户信息
			postIndex() {
				index({
					uid: uni.getStorageSync('uid')
				}).then((res) => {
					console.log('res: ', res)
					this.userInfo = res.data.user
				}).catch(err => {
					console.log('err: ', err)
				})
			},
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

		.list {
			margin: 20rpx;
			padding: 20rpx 32rpx;
			background-color: #262633;
			border-radius: 32rpx;

			.item {
				display: flex;
				height: 84rpx;
				align-items: center;
				justify-content: space-between;
				color: #fff;

				.nav {
					display: flex;
					background: url('../../static/images/arrow_right.png') no-repeat 98% 50%/6px auto;
					width: 100%;
					height: 84rpx;
					align-items: center;
					justify-content: space-between;
				}
			}
		}
	}
</style>
