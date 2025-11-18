<template>
	<view>
		<u-navbar :title="$t('language.help.title')" bgColor="#21212b" placeholder leftIconColor="#fff" leftIconSize="20px" titleStyle="color:#fff" :autoBack="true"></u-navbar>
		<view class="help-content">
			{{$t('language.help.dec')}}
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
