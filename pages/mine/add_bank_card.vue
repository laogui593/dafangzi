<template>
	<view>
		<u-navbar title="添加银行卡" :titleStyle="{ color: '#fff' }" leftIconColor="#fff" leftIconSize="40" :autoBack="true"
			bgColor="#181f2f"></u-navbar>
		<view class="f_content">
			<view class="ul">
				<view class="li">
					<label>姓名</label>
					<view class="input-box">
						<input type="text" v-model="form.name" placeholder="请输入姓名" />
					</view>
				</view>
				<view class="li">
					<label>银行名称</label>
					<view class="input-box">
						<input type="text" v-model="form.bank" placeholder="请输入银行名称" />
					</view>
				</view>
				<view class="li">
					<label>开户行</label>
					<view class="input-box">
						<input type="text" v-model="form.area" placeholder="请输入开户行" />
					</view>
				</view>
				<view class="li">
					<label>银行卡号</label>
					<view class="input-box">
						<input type="text" v-model="form.account" oncut="return false" onpaste="return false" oncopy="return false" placeholder="请输入银行卡号" />
					</view>
				</view>
			</view>
			<view class="sure">
				<button @click="postAddCard">确定</button>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		bankCard,
		addCard,
	} from '@/config/api.js'
	export default {
		data() {
			return {
				form: {
					uid: uni.getStorageSync('uid'), // uid
					name: '', // 姓名
					bank: '', // 所属银行
					area: '', // 开户行
					account: '', // 卡号
				}

			};
		},
		methods: {
			postAddCard() {
					bankCard({
						uid: uni.getStorageSync('uid')
					}).then(res => {
						console.log('bankCard', res);
						if(res.data.banks.length<1){
							addCard(this.form).then(res => {
								console.log('addCard', res);
								uni.$u.toast(res.info)
								setTimeout(() => {
									this.$Router.pushTab('/pages/mine/mine')
								}, 2000)
							}).catch(err => {
								console.log('err: ', err)
							})
						}else{
							this.$u.toast(this.$t('language.add_bank.over_limit'))
						}
					}).catch(err => {
						console.log('err: ', err)
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

		.ul {
			margin: 40rpx;
			display: flex;
			flex-direction: column;

			.li {
				display: flex;
				flex-direction: row;
				text-align: left;
				height: 92rpx;
				line-height: 92rpx;
				border-bottom: 1px solid #111;
				position: relative;

				label {
					flex: 2;
				}

				.input-box {
					flex: 4;
					position: relative;

					input {
						background: none;
						color: #fff;
						font-size: 14px;
						height: 100%;
					}
				}
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
