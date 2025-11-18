<template>
  <view>
   <view class="conter-bitter">
		<view class="conet-item" >
      <view class="flex" @click="toTrade(indexList[0])"  
	  v-if="page_title == indexList[0].title"   style="text-align: center;">
       <text
          :style="page_title == indexList[0].title ? 'color:#fede33;' : ''"
          >{{ indexList[0].title }}
		  </text>
	
		  </view
      >
      <view class="flex" @click="toTrade(indexList[1])"   
	  v-if="page_title == indexList[1].title">
       <text
          :style="page_title == indexList[1].title ? 'color:#fede33;' : ''"
          >{{ indexList[1].title }}</text
        >
		
		</view
      >
      <view class="flex" @click="toTrade(indexList[2])"   
	  v-if="page_title == indexList[2].title">
       <text
          :style="page_title == indexList[2].title ? 'color:#fede33;' : ''"
          >{{ indexList[2].title }}</text
        >
		</view
      >
      <view class="flex" @click="toTrade(indexList[3])"   
	  v-if="page_title == indexList[3].title">
       <text
          :style="page_title == indexList[3].title ? 'color:#fede33;' : ''"
          >{{ indexList[3].title }}</text
        >
		</view>
      <view class="flex" @click="toTrade(indexList[4])"   
	  v-if="page_title == indexList[4].title">
       <text
          :style="page_title == indexList[4].title ? 'color:#fede33;' : ''"
          >{{ indexList[4].title }}</text
        >
		</view
      >
    </view>
	</view>
    <view class="trade-head m-flex m-row-center">
      <view class="trade-price rise">{{ prodata_info.now || 0 }}</view>
      <view class="rise">
        <view>{{ prodata_info.open || 0 }}</view>
       <text style="color: #9a98ad; margin-right: auto"></text>
       <text style="margin-right: 20rpx">{{(prodata_info.now * randomNum || 0).toFixed(2)}}</text>
      </view>
    </view>
    <view class="high-low m-flex text-center">
      <view class="m-flex-1"
        >最高价<text class="rise">{{ prodata_info.highest || 0 }}</text
        ><text class="" style="margin-left: 40px; color: #26a848">{{
          (random.itemMax || 0).toFixed(2)
        }}</text></view
      >
      <view class="m-flex-1"
        >最低价<text class="fall">{{ prodata_info.lowest || 0 }}</text
        ><text class="" style="margin-left: 40px; color: #e45360">{{
          (random.itemMin || 0).toFixed(2)
        }}</text></view
      >
    </view>
    <u-line-progress
      :percentage="random.max"
      activeColor="#26a848"
      inactiveColor="#e45360"
    >
      <text class="u-percentage-slot"></text>
    </u-line-progress>
    <view class="kline-nav m-flex">
      <!-- <view class="kline-type">
        <u-subsection :list="klineType.list"
                      @change="klineTypeChange"
                      :current="klineType.active"
                      keyName="name"
                      mode="button"
                      bgColor="#17171a"
                      inactiveColor="#fff"
                      activeColor="#3b7eff">
        </u-subsection>
      </view> -->
      <view class="m-flex-1">
        <m-section
          :list="timeList.list"
          @change="timeChange"
          keyName="name"
          inactiveColor="#737373"
          activeColor="#edd33a"
        ></m-section>
      </view>
    </view>
    <view class="trade-pay-list m-flex m-row-between">
      <u-button
        @click="buyTap(0)"
        type=""
        text="买涨"
        style="background-color: #e45360; flex: 1; margin-right: 10px;"
      ></u-button>
      <u-button
        @click="buyTap(1)"
        type=""
        text="买跌"
        style="background-color: #0ecb80; flex: 1;"
      ></u-button>
    </view>
    <view class="conter-kx">
      <m-kline
        :type="klineType.active"
        :time="timeList.active"
        :list="kline_list"
        :update="kline_update"
      ></m-kline>
    </view>
    <!-- 下单组件 -->
    <place-order
      @subTap="payOrder"
      :show="placeInfo.show"
      :type="buy_type"
      :info="placeInfo.info"
      :new_price="prodata_info.now"
      @close="placeInfo.show = false"
      :placeInfo="placeInfo"
    >
    </place-order>
    <order-time
      :type="orderInfo.type"
      :show="orderInfo.show"
      :info="orderInfo.info"
      :new-price="prodata_info.now"
      @loading="orderLoading"
      @close="orderInfo.show = false"
    ></order-time>
    <!-- <view class="conet-item" style="margin-top: -100px">
      <view class="conet-item-ot">
        <text style="color: #9a98ad; margin-right: auto">产品</text>
        <text style="margin-right: 20rpx">{{ page_title }}</text>
      </view>
      <view class="conet-item-ot"
        ><text style="color: #9a98ad; margin-right: auto">最低价</text
        ><text style="margin-right: 20rpx">{{
          prodata_info.lowest || 0
        }}</text></view
      >
      <view class="conet-item-ot"
        >
		<text style="color: #9a98ad; margin-right: auto">成交量</text>
		<text style="margin-right: 20rpx">{{(prodata_info.now * randomNum || 0).toFixed(2)}}</text>
		</view
      >
    </view>
    <view class="conet-item">
      <view class="conet-item-ot">
        <text style="color: #9a98ad; margin-right: auto">最高价</text>
        <text style="margin-right: 20rpx">{{ prodata_info.highest || 0 }}</text>
      </view>
      <view class="conet-item-ot"
        ><text style="color: #9a98ad; margin-right: auto">今开</text
        ><text style="margin-right: 20rpx">{{
          prodata_info.open || 0
        }}</text></view
      >
      <view class="conet-item-ot"
        ><text style="color: #9a98ad; margin-right: auto">持仓</text
        ><text style="margin-right: 20rpx">{{
          (prodata_info.open * randomNum || 0).toFixed(2)
        }}</text></view
      >
    </view> -->
  </view>
</template>

<script>
import {
  getkdata,
  getKupdate,
  getprodata,
  weimai,
  getGoods,
  addorder,
  getProducts,
} from "../../config/api.js";
export default {
  computed: {
    randomNum() {
      return Math.floor(Math.random() * (1000 - 100 + 1)) + 100;
    },
    random() {
      let max = Math.floor(Math.random() * 100);
      let random = Math.floor(Math.random() * 20);
      let min = 100 - max;
      let itemMax = (max / 100) * this.prodata_info.now;
      let itemMin = (min / 100) * this.prodata_info.now;
      return {
        max: 40 + random,
        min,
        itemMax,
        itemMin,
      };
    },
    // 获取缓存中的数据
    indexList() {
      return uni.getStorageSync("storage_key");
    },
  },
  data() {
    return {
      itemshow: false,
      products: "",
      page_title: "", //页面标题
      klineType: {
        list: [
          {
            name: 'K线',
          },
          {
            name: '分时',
          },
        ],
        active: 0,
      }, //图表类型
      timeList: {
        list: [
          // {
          //   name: '1分钟',
          //   value: 1,
          // },
          {
            name: '5分钟',
            value: 5,
          },
          {
            name: '15分钟',
            value: 15,
          },
          {
            name: '30分钟',
            value: 30,
          },
          {
            name: '1小时',
            value: 60,
          },
          {
            name: '1天',
            value: "d",
          },
        ],
        active: 1,
      }, //图表时间
      placeInfo: {
        show: false,
        info: {}, //信息
      }, //购买弹窗
      orderInfo: {
        show: false,
        type: 0,
        info: {},
      }, //下单弹窗
      buy_type: 0, //下单类型
      trade_pid: "",
      prodata_info: {}, //页面顶部波动信息
      prodate_time: null, //波动时间
      kline_list: [], //k线图数据
      kline_update: {}, //k线图数据更新
      kline_time: null, //k线图更新时间计时器
    };
  },
  onShow() {
    let trade_pid = uni.getStorageSync("trade_pid");
    if (!trade_pid) {
      this.getTradePid();
      console.log("没有缓存");
    } else {
      this.trade_pid = trade_pid.id;
      this.page_title = trade_pid.title;
      this.getKlineList();
    }
    // 延迟执行
    setTimeout(() => {
      this.itemshow = true;
    }, 2000);
  },
  onHide() {
    // uni.removeStorageSync('trade_pid')
    clearInterval(this.kline_time);
    clearInterval(this.prodate_time);
    if (!this.kline_time || !this.prodate_time) {
      this.myClearInterval();
    }
  },
  mounted() {
    // 获取缓存
  },

  methods: {
    toTrade(item) {
      this.$Router.pushTab(`/pages/trade/trade`);
      uni.setStorageSync("trade_pid", {
        id: item.id,
        title: item.title,
      });
      // 刷新页面
      location.reload();
    },
    // 获取默认pid
    getTradePid() {
      getProducts().then((res) => {
        console.log(res);
        for (let i = 0; i < res.data.length; i++) {
          const value = res.data[i];
          if (value.isdelete == 0) {
            console.log(value.id);
            this.page_title = value.title;
            this.trade_pid = value.id;
            this.getKlineList();
            return;
          }
        }
      });
    },
    // 获取底部交易对
    myGetProducts() {
      const that = this;
      getProducts().then(async (res) => {
        // console.log('底部交易对', res);
        this.products = res.data;
        // this.myUpdateProducts()
        // // 开启定时更新任务
        // this.productsTime = setInterval(() => {
        //   this.myUpdateProducts()
        // }, 2000)
      });
    },
    // 买涨买跌
    buyTap(type) {
      uni.showLoading();
      // 0买涨 1买跌
      this.buy_type = type;
      let uid = uni.getStorageSync("uid");
      getGoods({
        params: {
          uid,
          id: this.trade_pid,
        },
      }).then((res) => {
        this.placeInfo.info = res.data;
        // console.log(res.data);
        uni.hideLoading();
        this.placeInfo.show = true;
      });
    }, // 买涨买跌
    payOrder(e) {
      this.placeInfo.show = false;
      this.orderInfo.type = 0;
      let para = e;
      para.uid = uni.getStorageSync("uid");
      para.order_pid = this.trade_pid;
      para.newprice = this.prodata_info.now;
      console.log("交易信息", para);
      addorder(para).then((res) => {
        console.log("交易数据", res);
        uni.showToast({
          title: res.info,
          icon: "none",
        });
        if (res.code == 1) {
          this.orderInfo.info = {
            buy_type: para.order_type,
            money: res.data.buyprice,
            order_price: para.order_price,
            time: para.order_sen,
            price: this.prodata_info.Price,
            shouyi: para.order_shouyi,
            kuisun: para.order_kuishun,
          };
          console.log(this.orderInfo.info);
          this.orderInfo.show = true;
        }
      });
    },
    // 更改下单弹窗状态
    orderLoading(e) {
      this.orderInfo.type = e;
    },
    // 获取页面顶部信息
    getProdata() {
      this.prodate_time = setInterval(() => {
        getprodata({
          params: {
            pid: this.trade_pid,
          },
        }).then((res) => {
          // console.log('页面顶部波动信息',res);
          this.prodata_info = res.data;
          // console.log(this.prodata_info.now); //最新价
        });
      }, 1000);
    },
    // 获取k线图
    getKlineList() {
      getkdata({
        params: {
          pid: this.trade_pid,
          interval: this.timeList.active,
        },
      }).then((res) => {
        console.log("k线图数据", res);
        let list = [];
        res.data.items.map((value) => {
          list.push({
            timestamp: value[0] * 1000,
            open: value[1],
            close: value[2],
            low: value[3],
            high: value[4],
          });
        });
        const { topdata, open, lowest, highest, close, now } = res.data.topdata;
        list.push({
          timestamp: topdata * 1000,
          open: open * 1,
          close: close * 1,
          low: lowest * 1,
          high: highest * 1,
          volume: now * 1,
        });
        // console.log('k线图',list);
        this.kline_list = list;
        clearInterval(this.kline_time);
        clearInterval(this.prodate_time);
        if (!this.kline_time || !this.prodate_time) {
          this.myClearInterval();
        }
        this.getProdata();
        // 更新k线图数据
        this.kline_time = setInterval(() => {
          getkdata({
            params: {
              pid: this.trade_pid,
              interval: this.timeList.active,
              num: 1,
            },
          }).then((update) => {
            // console.log('最新k线图数据', update);
            let value = update.data.items[0];
            this.kline_update = {
              timestamp: value[0] * 1000,
              open: value[1],
              close: value[2],
              low: value[3],
              high: value[4],
            };
          });
        }, 1000);
      });

      function getTime(val) {
        let time = new Date(),
          time_text = `${time.getFullYear()}-${
            time.getMonth() + 1
          }-${time.getDate()} ${val}`;
        console.log("zhuanhuanshijian", time_text);
        return new Date(time_text).getTime();
      }
    },
    // 切换k线图类型
    klineTypeChange(e) {
      this.klineType.active = e;
    },
    // 切换k线图时间导航
    timeChange(e) {
      console.log(e);
      this.timeList.active = e.value;
      this.getKlineList();
    },

    // 删除多余定时器
    myClearInterval(end) {
      if (!end) {
        end = 1000;
      }
      for (let i = 0; i <= end; i++) {
        clearInterval(i);
      }
    },
    leftClick() {
      // 跳转首页
      uni.switchTab({
        url: "/pages/index/index",
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.conter-bitter {
  width: 100%;
  height: 100px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: #828095;
  font-size: 28rpx;
  font-weight: 700;
  overflow: auto;
	text-align: center;
  // flex-wrap: wrap;
  .flex {
    display: flex;
    align-items: center;
    // margin-left: 20px;
    // margin-right: 20px;
    width: 100%;
	

    text {
      // 居中
	  width: 100%;
      text-align: center;
	  font-size: 20px;
    }
  }
}

.conter-kx {
  // 相对定位
  position: relative;
  width: 100%;
  height: 450px;

  .conter-dh {
    position: absolute;
    top: 30%;
    /* 将子元素的上边缘移到父元素的中心位置 */
    left: 50%;
    /* 将子元素的左边缘移到父元素的中心位置 */
    transform: translate(-50%, -50%);
    /* 将子元素的中心位置移到父元素的中心位置 */
  }
}

.conet-item {
  display: block;
  margin-top: 20upx;
	width:100%;
	
  .conet-item-ot {
    width: 33.3%;
    display: flex;
  }
}

.rise {
  color: rgb(38, 168, 72);
}

.fall {
  color: rgb(255, 1, 5);
}

.trade-head {
  padding: $m-padding * 2 0;
  font-weight: 600;

  .trade-price {
    font-size: 32px;
    margin-right: 0.2em;
  }
}

.high-low {
  border: 1px solid $uni-border-color;
  color: #828095;

  & > view {
    padding: 0.5em 0;

    text {
      margin-left: 1em;
    }
  }

  & > view:nth-child(1) {
    border-right: 1px solid $uni-border-color;
  }
}

.kline-nav {
  background-color: #252529;

  .kline-type {
    width: 8em;
  }

  ::v-deep {
    .u-subsection--button,
    .u-subsection--button__bar {
      border-radius: 0.8em !important;
    }

    .u-subsection--button__bar {
      background-color: #323237;
    }
  }
}

.trade-pay-list {
  position: fixed;
  z-index: 9;
  bottom: 50px;
  width: 100%;
  padding: 0 $m-padding;

  .u-button {
    margin-right: 0.5em;

    &:last-child {
      margin: 0;
    }
  }
}
</style>
