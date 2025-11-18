<template>
	<view>
		<u-navbar :title="title" :titleStyle="{ color: '#fff' }" leftIconColor="#fff" leftIconSize="40" :autoBack="true"
			bgColor="#181f2f"></u-navbar>
		<view class="uni-common-mt" style="padding:20rpx;">
			<rich-text :nodes="strings"></rich-text>
		</view>
	</view>
</template>

<script>
	import {
		aboutUs,
		privacyPolicy
	} from "@/config/api.js"
	export default {
		data() {
			return {
				title: '',
				id: 0,
				strings: ''
			};
		},
		onLoad(op) {
			console.log('op: ', op);
			if (op.id) {
				this.id = op.id;
				if (op.id == 1) {
					this.title = this.$t('language.msg_view.Privacy_Policy');
					console.log(this.title);
					this.getPrivacyPolicy()
				} else if (op.id == 2) {
					this.title = this.$t('language.msg_view.about_Us');
					console.log(this.title);
					this.getAboutUs()
				}
			}
		},
		methods: {
			// 关于我们
			getPrivacyPolicy() {
				privacyPolicy({
					params: {
						uid: uni.getStorageSync('uid')
					}
				}).then(res => {
					console.log('res: ', res)
					this.strings = res.data.content
					console.log('this.strings: ', this.strings)
				}).catch(err => {
					console.log('err: ', err)
				})
			},
			// 隐私政策
			getAboutUs() {
				aboutUs({
					params: {
						uid: uni.getStorageSync('uid')
					}
				}).then(res => {
					console.log('res: ', res)
					this.strings = res.data.content
				}).catch(err => {
					console.log('err: ', err)
				})
			}
		}
	};
</script>

<style>
	.uni-common-mt {
		margin-top: 44px;
	}
</style>
