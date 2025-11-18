<template>
	<view>
		<u-navbar :title="this.$t('language.verified.Verified')" :titleStyle="{ color: '#fff' }" leftIconColor="#fff" leftIconSize="40" :autoBack="true"
			bgColor="#181f2f"></u-navbar>
		<view class="f_content">
			<view class="f_content-box">
				<view class="box1">
					<image v-if="verifiedInfo.rz_status == 0" src="/static/images/status_0.png" mode="widthFix"></image>
					<image v-if="verifiedInfo.rz_status == 1" src="/static/images/status_1.png" mode="widthFix"></image>
					<image v-if="verifiedInfo.rz_status == 2" src="/static/images/status_2.png" mode="widthFix"></image>
				</view>
			</view>
			<view class="f_ul">
				<view class="f_li">
					<label>{{$t('language.verified.name')}}</label>
					<input v-if="verifiedInfo.rz_status == 0" type="text" v-model="form.name">
					<text v-else>{{verifiedInfo.name}}</text>
				</view>
				<view class="f_li">
					<label>{{$t('language.verified.ID_number')}}</label>
					<input v-if="verifiedInfo.rz_status == 0" type="text" v-model="form.idcard">
					<text v-else>{{verifiedInfo.idcard}}</text>
				</view>
			</view>
			<view class="upload">
				<view class="m-flex" v-if="verifiedInfo.rz_status == 0">
					<image class="labe" :src="form.z_id_card || '/static/images/z_idcard.png'" mode=""
						@click="upLoad(1)"></image>
					<image class="labe" :src="form.f_id_card || '/static/images/f_idcard.png'" mode=""
						@click="upLoad(2)"></image>
				</view>
				<view class="m-flex" v-else>
					<image class="labe" :src="verifiedInfo.z_id_card" mode=""></image>
					<image class="labe" :src="verifiedInfo.f_id_card" mode=""></image>
				</view>
			</view>
			<view class="sure" v-if="verifiedInfo.rz_status == 0">
				<button @click="submit">{{$t('language.verified.sure')}}</button>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		verified,
		verifieds
	} from '@/config/api.js'
	export default {
		data() {
			return {
				verifiedInfo: {},
				form: {
					uid: uni.getStorageSync('uid'),
					name: '',
					idcard: '',
					z_id_card: '',
					f_id_card: ''
				},

			};
		},
		onLoad() {
			this.getVerified()
		},
		methods: {
			// 上传实名信息
			submit() {
				const {
					name,
					idcard,
					z_id_card,
					f_id_card
				} = this.form;
				if (!name) return uni.$u.toast(this.$t('language.verified.fill_in_name'))
				if (!idcard) return uni.$u.toast(this.$t('language.verified.Fill_in_the_ID_number'))
				if (!z_id_card) return uni.$u.toast(this.$t('language.verified.ID_card_front'))
				if (!f_id_card) return uni.$u.toast(this.$t('language.verified.reverse_side_of_ID_card'))
				verifieds(this.form).then(res => {
					console.log('verifieds', res);
					uni.$u.toast(res.info)
					this.getVerified()
				}).catch(err => {
					console.log('err: ', err)
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
				}).catch(err => {
					console.log('err: ', err)
				})
			},
			upLoad(value) {
				let opt = {
					url: uni.$u.http.config.baseURL + '/index/plugs/upload',
				}
				this.$util.uploadImageOne(opt, (data) => {
					console.log('uploadImageOne: ', data)
					if (value == 1) {
						this.form.z_id_card = uni.$u.http.config.baseURL + data.url
					} else if (value == 2) {
						this.form.f_id_card = uni.$u.http.config.baseURL + data.url
					}

				})
			}
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

		.f_content-box {
			padding: 70rpx;
			text-align: center;

			.box1 {
				image {
					width: 195px;
				}
			}
		}

		.f_ul {
			margin: 40rpx;
			display: flex;
			flex-direction: column;

			.f_li {
				display: flex;
				align-items: center;
				flex-direction: row;
				text-align: left;
				height: 96rpx;
				line-height: 96rpx;
				border-bottom: 1px solid #111;
				position: relative;

				label {
					flex: 2;
				}

				text {
					flex: 4;
					position: relative;
				}
			}
		}

		.upload {
			width: 100%;
			height: 400rpx;
			overflow: hidden;
			background-color: transparent;

			.labe {
				margin-left: 2.5%;
				width: 46%;
				position: relative;
				height: 200rpx;
				border-radius: 14rpx;
			}
		}

		.sure {
			height: 162rpx;
			margin-top: 28rpx;
			position: relative;

			button {
				position: absolute;
				left: 50%;
				top: 50%;
				transform: translate(-50%, -50%);
				margin: 0 auto;
				width: 90%;
				height: 50%;
				font-size: 16px;
				border-radius: 13rpx;
				background-color: #edd33a;
				color: #333;
				border: 0 none;
			}
		}
	}
</style>
