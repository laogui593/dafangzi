<template>
  <view class="place-wrap" v-if="Object.keys(info).length > 0">
    <u-popup
      :show="show"
      :customStyle="{ width: '80%' }"
      mode="center"
      bgColor="transparent"
      @close="close"
      @open="open"
    >
      <view class="place-wrap">
        <view class="place-title m-flex m-row-center">
          {{$t('language.order.title')}}
          <u-icon @click="close" name="close" color="#6d6d6d"></u-icon>
        </view>
        <view class="place-label">{{$t('language.order.time')}}</view>
        <view class="m-flex m-son-shrink m-flex-wrap place-nav p2">
          <view
            class="place-nav-item"
            @click="timeChoose(item)"
            v-for="(item, index) in order_price"
            :key="index"
            :class="item == timeActive ? 'active' : ''"
          >
            {{ item.time }}s{{ odds[index] ? `|${odds[index]}` : "" }}
          </view>
          <view class="place-nav-item" @click="fillAllMoney">
            全部
          </view>
        </view>
        <view class="place-label">{{$t('language.order.money')}}</view>
        <view class="input-wrapper" style="margin-bottom: 10px;">
          <u--input
            v-model.number="other_money"
            :placeholder="$t('language.order.money_pla')"
            type="number"
            inputAlign="left"
            color="#fff"
            border="surround"
            clearable
          ></u--input>
        </view>
		
		<view class="price-input">
		  <view    class="luobo" style="margin-top: 10px">
		  			  <view  class="luobo2" >{{this.$t('language.luobo.name')}}</view>
		  			  <view class="luobo2">{{this.$t('language.luobo.direction')}}</view>
		  			  <view  class="luobo2">{{this.$t('language.luobo.new')}}</view>
					  <view  class="luobo2">{{this.$t('language.luobo.price')}}</view>
		  </view>
		  <view  class="luobo">
		  			  <view  class="luobo2" style="">{{info.info.title}}</view>
		  			  <view class="luobo2"  v-if="type==0" style="color:red;">{{this.$t('language.luobo.up')}}</view>
					  <view class="luobo2"  v-if="type==3" style="color:blue;">{{this.$t('language.luobo.contracts')}}</view>
					  <view class="luobo2"  v-if="type==1" style="color:green;">{{this.$t('language.luobo.loss')}}</view>
		  			  <view class="luobo2"  style="color:red;">{{info.info.Price}}</view>
					  <view class="luobo2"  style="">{{money}}</view>
		  </view>
        </view>
        <view class="place-label price-hint m-flex p2">
          <view class="m-flex-3">{{$t('language.order.balance')}}：{{ info.user.money }}</view>
          <view class="m-flex-3">{{$t('language.order.fee')}}：{{ feeRate }}%{{ feeAmount > 0 ? ` (¥${feeAmount})` : '' }}</view>
        </view>
        <u-button
          @click="subPlace"
          color="#edd33a"
          :customStyle="{ color: '#000' }"
          :text="$t('language.order.btn')"
        ></u-button>
        <view class="m-table place-table text-center">
          <!-- <view class="m-tr p2">
            <view class="m-td"> 名称 </view>
            <view class="m-td"> 方向 </view>
            <view class="m-td"> 现价 </view>
            <view class="m-td"> 金额 </view>
          </view> -->
          <!-- <view class="m-tr p2">
						<view class="m-td">
							{{info.info.title}}
						</view>
						<view class="m-td" :class="type?'green':'red'">
							{{type?'买跌':'买涨'}}
						</view>
						<view class="m-td" :class="type?'green':'red'">
							{{new_price}}
						</view>
						<view class="m-td yellow">
							￥{{money||0}}
						</view>
					</view> -->
        </view>
        <!-- <view class="income p2 text-center">预期收益：￥{{income}} 保底金额：￥{{lowest}}</view> -->
      </view>
    </u-popup>
    <order-time :type="2" :show="warning" @close="warning = false"></order-time>
  </view>
</template>

<script>
export default {
  name: "place-order",
  data() {
    return {
      order_price: [], //结算时间列表
      timeActive: null, //结算时间选中
      priceList: [], //投资资金列表
      priceActive: null, //结算资金选中
      other_money: "", //金额
      odds: ["", "", ""],
      warning: false, //余额不足警告
    };
  },
  computed: {
    money() {
      if (!this.other_money) return 0;
      return this.other_money;
    },
    // 手续费率
    feeRate() {
      if (this.info && this.info.config && this.info.config.order_charge) {
        return parseFloat(this.info.config.order_charge);
      }
      return 0;
    },
    // 手续费金额
    feeAmount() {
      if (!this.money || this.feeRate <= 0) return 0;
      return (this.money * this.feeRate / 100).toFixed(2);
    },
    income() {
      if (!this.timeActive || !this.money) return 0;
      let profit = calculation(this.money * (this.timeActive.shouyi / 100));
      return calculation(profit * 1 + this.money * 1);

      function calculation(num) {
        let str = (num * 1).toFixed(3);
        return str.substring(0, str.length - 2);
      }
    },
    lowest() {
      if (!this.timeActive || !this.money) return 0;
      let profit = calculation(this.money * (this.timeActive.kuisun / 100));
      return calculation(this.money * 1 - profit * 1);

      function calculation(num) {
        let str = (num * 1).toFixed(3);
        return str.substring(0, str.length - 2);
      }
    },
    infodata() {
      if (this.type == 3) {
        this.order_price = ["60"];
      }
    },
  },
  props: {
    show: {
      type: Boolean,
      default: false,
    },
    info: Object,
    type: Number, //下单类型
    new_price: [Number, String], //现价
  },
  watch: {
    show(val) {
      if (val == true) {
        this.order_price = this.info.info.order_price;
        this.timeActive = this.order_price[0];
        this.priceList = this.timeActive.prices;
        this.other_money = "";
		console.log(this.info)
      }
    },
	
  },
  methods: {
    // 时间选择
    timeChoose(item) {
      this.timeActive = item;
      this.priceList = item.prices;
    },
    // 填充全部金额
    fillAllMoney() {
      // 计算扣除手续费后的最大可用金额
      if (this.feeRate > 0) {
        // 公式: 实际金额 = 总余额 / (1 + 手续费率/100)
        const maxAmount = this.info.user.money / (1 + this.feeRate / 100);
        this.other_money = Math.floor(maxAmount * 100) / 100; // 保留两位小数并向下取整
      } else {
        this.other_money = this.info.user.money;
      }
    },
    // 设置全部金额
    setAllMoney() {
      this.other_money = this.info.user.money;
    },
    subPlace() {
      if (!this.money || this.money <= 0) {
        return uni.showToast({
          title: this.$t('language.order.money_pla'),
          icon: "none",
        });
      }
      // 修改判断条件,加上手续费判断
      const totalAmount = parseFloat(this.money) + parseFloat(this.feeAmount);
      if (totalAmount > parseFloat(this.info.user.money)) return (this.warning = true);
      // 检查最小和最大金额限制
      let min_price = 0, max_price = 0;
      
      // 优先使用全局配置的最小最大金额
      if (this.info.config) {
        min_price = parseFloat(this.info.config.order_min) || 0;
        max_price = parseFloat(this.info.config.order_max) || 0;
      }
      
      // 如果全局配置没有设置,则使用产品的prices数组
      if ((min_price === 0 || max_price === 0) && this.priceList && this.priceList.length >= 2) {
        min_price = min_price || parseFloat(this.priceList[0]);
        max_price = max_price || parseFloat(this.priceList[this.priceList.length - 1]);
      }
      
      // 验证金额范围
      if (min_price > 0 && this.money < min_price) {
        return uni.showToast({
          title: `最小金额：${min_price}`,
          icon: "none",
        });
      }
      if (max_price > 0 && this.money > max_price) {
        return uni.showToast({
          title: `最大金额：${max_price}`,
          icon: "none",
        });
      }
      let para = {
        order_type: this.type,
        order_sen: this.timeActive.time,
        order_price: this.money,
        order_shouyi: this.timeActive.shouyi,
        order_kuishun: this.timeActive.kuisun,
      };
      this.$emit("subTap", para);
    },
    open() {
      this.$emit("open");
    },
    close() {
      this.$emit("close");
    },
  },
};
</script>

<style lang="scss">
.place-wrap {
  background-color: #000c;

  .place-title {
    position: relative;
    padding-bottom: 0.5em;
    border-bottom: 1px solid #fff;

    .u-icon {
      position: absolute;
      right: 0;
    }
  }

  .place-label {
    color: #b2b0c7;
    line-height: 2;
  }
  .luobo {
    width:100%;
	height:15px;
	
  }
  .luobo2 {
    width:25%;float:left;
	text-align: center;
  }

  .place-nav {
    .place-nav-item {
      padding: 0.5em;
      border-radius: 0.5em;
      border: 1px solid #fff;
      margin-right: 1em;
      margin-bottom: 0.5em;

      &.active {
        background-color: #edd33a;
        border-color: #edd33a;
        color: #000;
      }
    }
  }

  .price-input {
    padding-bottom: 1.2em;
    // border-bottom: 1px solid #fff;
  }

  .price-hint {
    border-bottom: 1px solid #333235;
  }

  .place-table {
    .m-td {
      padding: 0.5em 0;
    }

    .green {
      color: rgb(38, 168, 72);
    }

    .red {
      color: rgb(255, 1, 5);
    }

    .yellow {
      color: #edd33a;
    }
  }

  .income {
    line-height: 2;
    color: #edd33a;
  }

  .input-wrapper {
    ::v-deep .u-input__content__field-wrapper__field {
      font-size: 16px !important;
      height: 45px !important;
      line-height: 45px !important;
    }
    ::v-deep .u-input {
      height: 45px !important;
    }
    ::v-deep .u-input__content {
      height: 45px !important;
      padding: 0 10px !important;
    }
  }
}
</style>
