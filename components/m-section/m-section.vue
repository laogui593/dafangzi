<template>
	<view class="section-wrap m-flex m-row-between" :style="{backgroundColor:bgColor,color:inactiveColor}">
		<view class="section-item" @click="sectionChange(item)" v-for="(item,index) in list" :key="index" :style="{color:activeValue[keyName]==item[keyName]?activeColor:inactiveColor}">
			{{item[keyName]}}
		</view>
	</view>
</template>

<script>
	export default {
		name:"m-section",
		data() {
			return {
				activeValue:{}
			}
		},
		props:{
			list:Array,
			keyName:{
				type:String,
				default:'name'
			},
			bgColor:[String,Object],	//背景色
			inactiveColor:String,	//未选中颜色
			activeColor:String,		//选中颜色
		},
		created(){
			this.activeValue=this.list[0]
		},
		methods:{
			sectionChange(item){
				this.activeValue=item
				this.$emit('change',item)
			}
		}
	}
</script>

<style lang="scss">
.section-wrap{
	.section-item{
		padding: .5em;
	}
}
</style>