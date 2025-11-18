<template>
	<view class="m-image-wrap m-flex " :style="{width:width}">
		<view class="m-image-block" :style="{paddingBottom:`${height*100}%`}"></view>
		<image :src="src" mode="widthFix" style=""></image>
	</view>
</template>

<script>
	export default {
		name: "m-image",
		data() {
			return {

			}
		},
		props: {
			width: {
				type: String,
				default: '100%'
			},
			height: {
				type: [String, Number],
				default: 1
			},
			src: String
		}
	}
</script>

<style lang="scss">
	.m-image-wrap {
		position: relative;
		overflow: hidden;

		image {
			position: absolute;
			width: 100%;
			border-radius: 10rpx;
		}
	}
</style>
