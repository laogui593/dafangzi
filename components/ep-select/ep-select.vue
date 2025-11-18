<template>
    <view class="ep-select-box">
        <!-- 蒙版区域 开始 -->
        <view class="ep-mask-box" v-show="show_option" @click="clickMask"></view>
        <!-- 蒙版区域 开始 -->
        
        <!-- 输入框区域 开始 -->
        <view class="ep-input-box" :class="{'disabled':disabled}" @click="openOptions">
            <!-- 文本模式 -->
            <view v-if="!filterable" class="label-item">{{showLabel}}</view>
            <!-- 输入框模式 -->
            <view v-else class="input-item">
                <uni-easyinput type="text" v-model="input" :placeholder="showLabel" :placeholderStyle="placeholderStyle" :clearable="false" :inputBorder="false"></uni-easyinput>
            </view>
            <!-- 箭头图标切换 -->
            <text v-if="!show_option" class="iconfont icon-xiala"></text>
            <text v-else class="iconfont icon-xialashang"></text>
        </view>
        <!-- 输入框区域 结束 -->
        
        <!-- 弹出的下拉区域 开始 -->
        <view v-show="show_option" class="ep-select-content-wrap">
            <scroll-view class="ep-select-content" :scroll-y="true">
                <view v-for="item in filterOptions" :key="item[value_key]" 
                :class="{'disabled':item.disabled,'active':value==item[value_key]}"
                class="option-item"
                @click="itemClick(item)"
                >{{item[label_key]}}</view>
                <view v-if='filterOptions.length==0' class="empty-text">暂无数据</view>
            </scroll-view>
            <text class="triangle"></text>
        </view>
        <!-- 弹出的下拉区域 结束 -->
    </view>
</template>

<script>
    export default{
        data(){
            return {
                show_option: false,//是否展示下拉选项

                input:"",//开启搜索时输入框的值
            }
        },
        props:{
            value:{
                type:[String,Number],
                default:""
            },
            options:{
                type: Array,
                default: function(){
                    return []
                }
            },
            value_key:{
                type:String,
                default:"value"
            },
            label_key:{
                type:String,
                default:"label"
            },
            disabled:{
                type:Boolean,
                default: false
            },
            filterable:{
                type:Boolean,
                default:false
            },	
			"keep-input":{
				type:Boolean,
				default:false
			},
        },
        model:{
            prop:'value',
            event:"valChange"
        },
        mounted(){
            
        },
        methods:{ 
            //点击选中选项
            itemClick(item){
                if(item.disabled) return
                
                //关闭
                this.show_option = false
                //修改v-model的值
                this.$emit('valChange',item[this.value_key])
                //将事件通知父组件
                this.$emit('change',item[this.value_key])
                
                //重置输入框
				if(!this.keepInput){
					this.input = ''
				}
                
            },
            //展开选项
            openOptions(){
                //禁用则不做任何操作
                if(this.disabled) return
                
                //模式判断 select or picker
                console.log(this.mode)
                
                this.show_option  = true
            },
            //点击蒙版
            clickMask(){
                //关闭下拉选项
                this.show_option = false
                //重置输入框
                if(!this.keepInput){
                	this.input = ''
                }
            }
        },
        computed:{
            //输入框要展示的文本 动态计算placeholder
            showLabel(){
                //根据v-model传入的值，查找对应的下拉项
                var target = this.options.find(item=>{
                    return item[this.value_key] == this.value
                })
                //如果目标项存在 则返回其 label
                if(target){
                    return target[this.label_key]
                }else{
                    return "请选择"
                }
            },
            //动态计算placeholder的样式
            placeholderStyle(){
                //如果未选中 则灰色
                if(!this.value){
                    return 'color:#999;'
                }else if(this.show_option){
                    //如果已选中 但是下拉框已弹出 则灰色
                    return 'color:#999;'
                }else{
                    //否则黑色
                    return 'color:#333;'
                }
            },
            //过滤后的列表
            filterOptions(){
                return this.options.filter(item=>{
                    return item[this.label_key].includes(this.input)
                })
            },
            
        }
    }
</script>

<style scoped lang="scss">
	/* 引入字体图标 */
	@import './iconfont.css';
	
    .ep-select-box{
        box-sizing: border-box;
        position: relative;
        width:100%;
		height: 76rpx;
        color: #fff;
		background: #111723;
		font-size: 14px;
        .ep-mask-box{
            position: fixed;
            z-index: 999;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            background:none;
        }
        .ep-input-box{
            border: 1px solid rgb(229, 229, 229);
            border-radius: 4px;
            /* padding-left:10px; */
            position: relative;
            cursor: pointer;
            display: flex;
            align-items: center;
            .label-item{
                display: flex;align-items: center;height: 36px;padding-left:10px;
            }
            .input-item{
                display: flex;align-items: center;min-height: 36px;
            }
            .iconfont{
                position: absolute;
                top: 50%;
                right:5px;
                font-size: 20px;
                transform: translateY(-50%);
                color:#B8B8B8;
            }
        }
        .disabled{
            cursor:not-allowed;
            background-color: #f5f7fa;
            .label-item{
                color:#999;
            }
        }
        .ep-select-content-wrap{
            width: 100%;
            position: absolute;
            top: 45px;
            left: 0;
            
            z-index: 9999;
            padding-top:6px;
            
            .ep-select-content{
                background-color: #767676;
				color: #fff;
                padding:3px 0;
                font-size: 14px;
                
                box-shadow: 0 0 20px 5px rgba(0,0,0,0.3);
                border-radius: 5px;
                max-height:181px;
                
                .option-item{
                    padding: 8px 18px;
                    cursor: pointer;
                    
                    // &:hover{
                    //     background-color: #f5f7fa;
                    // }
                }
                .active{
                    color:#007AFF; 
                }
                .disabled{
                    color:#c0c4cc; 
                    &:hover{
                        background-color: #f5f7fa;
                    }
                }
            }
            
            .triangle{
                width: 0;
                height: 0;
                border-top: 6px solid rgba(0,0,0,0);
                border-right: 6px solid rgba(0,0,0,0);
                border-bottom: 6px solid #fff;
                border-left: 6px solid rgba(0,0,0,0);
                position: absolute;
                top:-6px;
                left:50%;
                transform: translateX(-50%);
                box-sizing: content-box;
            }
        }
    }

    .empty-text{
        color:#999;
        text-align: center;
        padding: 3px 0;
        font-size: 14px;
    }
    
    
    .uni-easyinput__placeholder-class{
        font-size: 14px !important;
        font-weight: 400 !important;
        height: 100%;
        display: flex;
        align-items: center;
		color: #fff !important;
    }
</style>