<template>
	<view>
		<u-navbar title="充值记录" :titleStyle="{ color: '#fff' }" leftIconColor="#fff" leftIconSize="40" :autoBack="true"
			bgColor="#181f2f"></u-navbar>
		<view class="t_box_accountrecord">
			<view class="t_ul" v-for="(item,index) in rechargeList" :key="index">
				<view class="t_li">
					<text>时间：</text>
					<view class="">
						{{item.time}}
					</view>
				</view>
				<view class="t_li">
					<text>金额：</text>
					<view class="">
						¥{{item.money}}
					</view>
				</view>
				<view class="t_li">
					<text>类型：</text>
					<view class="">
						{{item.type}}
					</view>
				</view>
				<view class="t_li">
					<text>状态：</text>
					<view class="orange" v-if="item.status == 0">
						审核中
					</view>
					<view class="green" v-if="item.status == 1">
						成功
					</view>
					<view class="red" v-if="item.status == 2">
						失败
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		rechargeRecord
	} from '@/config/api.js'
	export default {
		data() {
			return {
				form: {
					uid: uni.getStorageSync('uid'),
					page: 1,
					num: 30
				},
				rechargeList: []
			}
		},
		onLoad() {
			this.rechargeRecord()
		},
		methods: {
			rechargeRecord() {
				rechargeRecord(this.form).then((res) => {
					console.log('res: ', res)
					this.rechargeList = res.data
				}).catch(err => {
					console.log('err: ', err)
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.t_box_accountrecord {
		margin-top: 44px;
		width: 100%;
		height: 100%;

		.t_ul {
			display: flex;
			flex-direction: column;
			padding: 36rpx 56rpx;
			font-size: 28rpx;
			border-bottom: .026666rem solid #000;
			color: #fff;
			background-color: #111723;

			.t_li {
				display: flex;
				text-align: left;
				height: 56rpx;

				.orange {
					color: orange;
				}

				.green {
					color: green;
				}

				.red {
					color: red;
				}
			}
		}
	}
</style>
