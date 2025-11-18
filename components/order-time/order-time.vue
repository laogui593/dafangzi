<template>
  <view class="order-time">
    <u-popup
      :show="show"
      :customStyle="{ width: '80%' }"
      mode="center"
      bgColor="transparent"
      @close="close"
      @open="open"
    >
      <view class="place-wrap">
        <view class="place-title m-flex m-row-right">
          <u-icon @click="close" name="close" color="#6d6d6d"></u-icon>
        </view>
        <view class="place-content" v-if="type == 0">
          <view
            class="price-wrap m-flex m-flex-col m-row-center text-center p2"
          >
            <!-- <view class="price">{{ info.time }}</view> -->
            <view>{{$t('language.order_time.money_now')}}</view>
            <view :class="info.buy_type ? 'green' : 'red'">{{
              newPrice || 0
            }}</view>
          </view>
          <viwe class="price-list m-flex m-col-top text-center">
            <view>
              <view>{{$t('language.order_time.direct')}}</view>
              <view :class="info.buy_type ? 'green' : 'red'">{{
                info.buy_type == 1
                  ? $t('language.order_time.buy_d')
                  : info.buy_type == 0
                  ? $t('language.order_time.buy_u')
                  : $t('language.order_time.buy_w')
              }}</view>
            </view>
            <view>
              <view>{{$t('language.order_time.money')}}</view>
              <view>{{ info.order_price }}</view>
            </view>
            <view>
              <view>{{$t('language.order_time.handle_money')}}</view>
              <view>{{ info.money }}</view>
            </view>
            <view>
              <view>{{$t('language.order_time.predict')}}</view>
              <view :class="forecastClass">{{
                forecast > 0 ? forecast : forecast * -1
              }}</view>
            </view>
          </viwe>
        </view>
        <view
          class="place-content place-loading m-flex m-row-center"
          v-else-if="type == 1"
        >
          <m-image src="/static/images/feiji.png" width="1.5em"></m-image
          >loading......
        </view>
        <view
          class="place-content place-loading m-flex m-row-center"
          v-else-if="type == 2"
        >
          <m-image src="/static/images/cuowu.png" width="1.5em"></m-image
          ><text class="p4">{{$t('language.order_time.money_emp')}}</text>
        </view>
        <view class="place-content" v-if="type == 3">
          <view
            class="deal-money m-flex m-flex-col m-row-center text-center p2"
          >
            <view class="price" :class="dealMoneyClass"
              >{{$t('language.order_time.symbol')}}{{ dealShouyi }}</view
            >
            <view>{{$t('language.order_time.pre_finish')}}</view>
          </view>
          <viwe class="price-list m-flex m-col-top text-center">
            <view>
              <view>{{$t('language.order_time.direct')}}</view>
              <view :class="info.buy_type ? 'green' : 'red'">{{
                info.buy_type ? $t('language.order_time.buy_d') : $t('language.order_time.buy_u')
              }}</view>
            </view>
            <view>
              <view>{{$t('language.order_time.money')}}</view>
              <view>{{ info.order_price }}</view>
            </view>
            <view>
              <view>{{$t('language.order_time.handle_money')}}</view>
              <view>{{ info.money }}</view>
            </view>
            <view>
              <view>{{$t('language.order_time.deal_money')}}</view>
              <view :class="dealMoneyClass">{{ dealMoney }}</view>
            </view>
          </viwe>
        </view>
        <u-button
          @click="subPlace"
          color="#3b7eff"
          :customStyle="{ color: '#000' }"
          :text="$t('language.order_time.btn')"
        ></u-button>
      </view>
    </u-popup>
  </view>
</template>

<script>
export default {
  name: "order-time",
  data() {
    return {
      time: null,
      dealMoney: 0,
      dealShouyi: 0,
    };
  },
  props: {
    show: {
      type: Boolean,
      default: false,
    },
    info: Object,

    type: {
      type: Number,
      default: 0,
    },
    newPrice: [Number, String],
  },
  watch: {
    show(val) {
      if (val) {
        this.time = setInterval(() => {
          if (this.info.time) {
            this.info.time--;
            if (this.info.time < 1) {
              clearInterval(this.time);
              this.dealMoney = JSON.parse(JSON.stringify(this.newPrice));
              let forecast = this.forecast;
              this.dealShouyi = JSON.parse(
                JSON.stringify(forecast > 0 ? forecast : forecast * -1)
              );
              this.$emit("loading", 3);
            }
          }
        }, 1000);
      } else {
        clearInterval(this.time);
      }
    },
  },
  computed: {
    forecast() {
      // {
      // 	buy_type:para.order_type,
      // 	money:res.data.buyprice,
      // 	order_price:para.order_price,
      // 	time:para.order_sen,
      // 	price:this.prodata_info.Price,
      // 	shouyi:para.order_shouyi,
      // 	kuisun:para.order_kuishun
      // }
      let info = this.info;
      console.log(info.buy_type);
      console.log(info.money);
      console.log(this.newPrice);
      // 0涨 1跌
      //买价和最新价对比
      if (info.money == this.newPrice) return 0; //持平返回0
      if (info.buy_type == 0) {
        if (info.money > this.newPrice) {
          return parseInt(((info.order_price * info.kuisun) / 100) * -1);
        } else {
          return parseInt((info.order_price * info.shouyi) / 100);
        }
      } else {
        if (info.money < this.newPrice) {
          return parseInt(((info.order_price * info.kuisun) / 100) * -1);
        } else {
          return parseInt((info.order_price * info.shouyi) / 100);
        }
      }
    },
    forecastClass() {
      if (this.forecast == 0) return "";
      if (this.forecast > 0) {
        return this.info.buy_type ? "green" : "red";
      } else {
        return this.info.buy_type ? "red" : "green";
      }
    },
    dealMoneyClass() {
      if (this.dealMoney * 1 == 0) return "";
      if (this.dealMoney * 1 > 0) {
        return this.info.buy_type ? "green" : "red";
      } else {
        return this.info.buy_type ? "red" : "green";
      }
    },
  },
  methods: {
    subPlace() {
      this.close();
      this.$emit("subTap");
    },
    open() {
      this.$emit("open");
    },
    close() {
      this.$emit("close");
      clearInterval(this.time);
    },
  },
};
</script>

<style lang="scss">
.place-wrap {
  padding: $m-padding;
  background-color: #000c;
  color: #6d6d6d;

  .place-title {
    padding: 0.5em 0;
    border-bottom: 1px solid #333235;
  }

  .place-loading {
    height: 166px;
    color: #fff;
  }

  .green {
    color: rgb(38, 168, 72) !important;
  }

  .red {
    color: rgb(255, 1, 5) !important;
  }

  .yellow {
    color: #edd33a;
  }

  .price-wrap {
    width: 7rem;
    height: 7rem;
    margin: $m-margin auto;
    border: 2px solid #edd33a;
    border-radius: 50%;

    .price {
      color: #3b7eff;
      font-size: 32px;
    }
  }

  .deal-money {
    width: 7rem;
    height: 7rem;
    margin: $m-margin auto;

    .price {
      font-size: 32px;
    }
  }

  .price-list {
    padding: 0.5em 0;
    border-top: 1px solid #6d6d6d;

    & > view {
      flex: 1;
      border-right: 1px solid #6d6d6d;

      & > view:nth-of-type(2) {
        color: #fff;
      }
    }
  }

  .u-button {
    margin: $m-margin 0;
  }
}
</style>
