<template>
	<view>
		<u-navbar :title="$t('language.cash_record.title')" :titleStyle="{ color: '#fff' }" leftIconColor="#fff" leftIconSize="40" :autoBack="true"
			bgColor="#181f2f"></u-navbar>
		<view class="t_box_accountrecord">
			<view class="t_ul" v-for="(item,index) in cashRecordList" :key="item.id">
				<view class="t_li">
					<text>{{$t('language.cash_record.time')}}：</text>
					<view class="">
						{{item.time}}
					</view>
				</view>
				<view class="t_li">
					<text>{{$t('language.cash_record.money')}}：</text>
					<view class="">
						{{$t('language.order_time.symbol')}}{{item.money}}
					</view>
				</view>
				<view class="t_li">
					<text>{{$t('language.cash_record.fee')}}：</text>
					<view class="">
						{{item.sxfbfb}}%
					</view>
				</view>
				<view class="t_li">
					<text>{{$t('language.cash_record.get')}}：</text>
					<view class="">
						{{item.dzje}}
					</view>
				</view>
				<view class="t_li">
					<text>{{$t('language.cash_record.type')}}：</text>
					<view class="">
						{{item.name}}
					</view>
				</view>
				<view class="t_li">
					<text>{{$t('language.cash_record.state')}}：</text>
					<view class="orange" v-if="item.status == 0">
						{{$t('language.cash_record.ing')}}
					</view>
					<view class="red"  v-if="item.status == 2">
						{{$t('language.cash_record.fail')}}
					</view>
					<view class="green" v-if="item.status == 1">
						{{$t('language.cash_record.suc')}}
					</view>
				</view>
				<view class="t_li" v-if="item.status==2">
					<text>{{$t('language.cash_record.refuse')}}：</text>
					<view class="">
						{{item.reaolae}}
					</view>
				</view>
			</view>
			
		</view>
	</view>
</template>

<script>
	import {
		cashRecord
	} from '@/config/api.js'
	export default {
		data() {
			return {
				form: {
					uid: uni.getStorageSync('uid'),
					page: 1,
					num: 30
				},
				cashRecordList: []
			}
		},
		onLoad() {
			this.cashRecord()
		},
		methods: {
			cashRecord() {
				cashRecord(this.form).then((res) => {
					console.log('res: ', res)
					this.cashRecordList = res.data
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
				
				.red{
					color: red;
				}
			}
		}
	}
</style>
