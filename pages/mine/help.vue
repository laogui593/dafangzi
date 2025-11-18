<template>
	<view>
		<u-navbar title="帮助" bgColor="#21212b" placeholder leftIconColor="#fff" leftIconSize="20px" titleStyle="color:#fff" :autoBack="true"></u-navbar>
		<view class="help-content">
			如有任何问题，请联系客服
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				
			};
		}
	}
</script>

<style lang="scss">
.help-content{
	padding: $m-padding;
}
</style>
