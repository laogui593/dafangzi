<template>
	<view>
		<u-navbar :title="$t('language.bill.title')" bgColor="#181f2f" placeholder leftIconColor="#fff" leftIconSize="20px"
			titleStyle="color:#fff" :autoBack="true"></u-navbar>
		<view class="bill" v-for="(item,index) in fundList" :key="item.id">
			<view class="u-line-1 bill-padding"><text>{{$t('language.bill.time')}}：</text><text>{{item.time}}</text></view>
			<view class="u-line-1 bill-padding"><text>{{$t('language.bill.note')}}：</text><text>{{item.reason}}</text></view>
			<view class="u-line-1 bill-padding">
				<text>{{$t('language.bill.money')}}：</text>
				<text v-if="item.type == 1">+</text>
				<text v-if="item.type == 2">-</text>
				<text>{{$t('language.order_time.symbol')}}{{item.money}}</text>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		fund
	} from '@/config/api.js'
	export default {
		data() {
			return {
				form: {
					uid: uni.getStorageSync('uid'),
					page: 1,
					num: 30
				},
				fundList: []
			}
		},
		onLoad() {
			this.fund()
		},
		onPullDownRefresh() {
			this.form.page == 1
			this.fundList = []
		},
		onReachBottom() {
			this.fund()
		},
		methods: {
			fund() {
				fund(this.form).then((res) => {
					console.log('res: ', res)
					this.fundList = [...this.fundList, ...res.data]
					this.form.page++
				}).catch(err => {
					console.log('err: ', err)
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.bill {
		display: flex;
		flex-direction: column;
		padding: 36rpx 56rpx;
		font-size: 28rpx;
		border-bottom: 2px solid #000;
		color: #fff;
		background-color: #111723;

		.bill-padding {
			line-height: 2;
		}
	}
</style>
