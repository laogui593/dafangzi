<template>
	<view class="index">
		<u-swiper :list="index_swiper" height="169px" keyName="image"></u-swiper>
		<view class="index-nav model m-flex m-row-between">
			<image src="/static/images/index_charge.png" mode="widthFix"></image>
			<view>
				<view class="m-flex m-row-between"><text class="quick-recharge">快捷充值</text><text>关于我们></text></view>
				<view class="m-flex m-row-between"><text class="recharge-option">银行转账和多种方式选择</text><text>帮助中心></text></view>
			</view>
		</view>
		<view class="index-increase model">
			<view class="index-increase-title p4">涨幅榜</view>
			<view class="increase-list m-flex m-son-shrink m-flex-wrap m-col-top m-row-between">
				<view class="increase-list-item" v-for="item in 3">
					<m-image src="/static/images/temporary/shangpin1.jpg"></m-image>
					<view class="h4">Gold/黄金</view>
					<view class="m-flex m-col-bottom m-row-between"><text>36.5742</text><text class="price-status">交易中</text></view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				index_swiper:[
					{image:'/static/images/temporary/index_banner1.png'},
					{image:'/static/images/temporary/index_banner2.png'}
				]
			}
		},
		onLoad() {

		},
		methods: {

		}
	}
</script>

<style lang="scss" scoped>
	.index-nav{
		color: #d5d7d0;
		.quick-recharge{
			color: #fff;
		}
		.recharge-option{
			color: #999;
		}
		&>image{
			width: 80rpx;
			margin-right: 10px;
		}
		&>view{
			width: 80%;
		}
	}
	.index-increase{
		.index-increase-title{
			color: #6e1dfc;
		}
		.increase-list-item{
			width: 48%;
			margin: $m-margin 0;
			.price-status{
				padding: .2em;
				background-color: red;
			}
		}
	}
</style>
