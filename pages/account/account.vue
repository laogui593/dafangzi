<template>
  <view>
    <view class="account">
      <view class="account-title">{{$t('language.account.my_account')}}</view>
      <view class="account-head">
        <view class="top">
          <view class="top-left">
            <view class="t11">{{$t('language.account.Total_assets')}} CNH</view>
            <text class="t22">{{ account.wait_money }}</text>
            <text class="refresh">{{$t('language.account.to_refresh')}}</text>
          </view>
          <view class="top-right mimi" @click="isShow = !isShow">
            <image src="/static/images/icon_eye.png" mode="widthFix"></image>
          </view>
        </view>
        <view class="down">
          <view class="it">
            <h5 class="h5">{{$t('language.account.profit_and_loss')}}</h5>
            <h6 class="a h6" v-if="isShow">{{ account.yingkui }}</h6>
            <h6 class="b h6" v-else>*****</h6>
          </view>
          <view class="it">
            <h5 class="h5">{{$t('language.account.Position_amount')}}</h5>
            <h6 class="a h6" v-if="isShow">{{ account.chicang }}</h6>
            <h6 class="b h6" v-else>*****</h6>
          </view>
          <view class="it" v-if="account.user">
            <h5 class="h5">{{$t('language.account.Available_funds')}}</h5>
            <h6 class="a h6" v-if="isShow">{{ account.user.money }}</h6>
            <h6 class="b h6" v-else>*****</h6>
          </view>
        </view>
      </view>
      <view class="account-list">
        <view class="ul">
          <view class="li left">
            <navigator url="/pages/mine/recharge_record" class="navtor">
              <image src="/static/images/ch6.png" mode="widthFix"></image>
              <text>{{$t('language.account.Recharge_record')}}</text>
            </navigator>
          </view>
          <view class="li center">
            <view class="navtor" @click="openKF(KF)">
              <image src="/static/images/ch5.png" mode="widthFix"></image>
              <text>{{$t('language.account.recharge')}}</text>
            </view>
          </view>
          <view class="li center">
            <navigator url="/pages/mine/cash" class="navtor">
              <image src="/static/images/ch4.png" mode="widthFix"></image>
              <text>{{$t('language.account.withdraw')}}</text>
            </navigator>
          </view>
          <view class="li right">
            <navigator url="/pages/mine/cash_record" class="navtor">
              <image src="/static/images/ch4.png" mode="widthFix"></image>
              <text>{{$t('language.account.Withdrawals_record')}}</text>
            </navigator>
          </view>
          <view class="li left">
            <navigator url="/pages/mine/history_entrust" class="navtor">
              <image src="/static/images/ch3.png" mode="widthFix"></image>
              <text>{{$t('language.account.history')}}</text>
            </navigator>
          </view>
          <view class="li center">
            <navigator url="" class="navtor" @click="openKF(KF)">
              <image src="/static/images/ch1.png" mode="widthFix"></image>
              <text>{{$t('language.account.customer_service')}}</text>
            </navigator>
          </view>
        </view>
      </view>
    </view>
    <view class="accound-tabs">
      <u-tabs
        :list="tabsList"
        @click="tabsChange"
        lineWidth="0"
        :activeStyle="{ color: '#fff' }"
        :inactiveStyle="{ color: '#828095' }"
        itemStyle="width:50%;padding:20rpx;"
      ></u-tabs>
    </view>
    <view class="tabs-list">
      <view class="list-ul">
        <scroll-view
          :scroll-top="scrollTop"
          :show-scrollbar="true"
          scroll-y="true"
          class="scroll-Y"
          @scrolltolower="lower"
        >
          <view
            class="list-li m-row-between"
            :class="form.type == 0 ? 'paddingB' : ''"
            v-for="(item, index) in orderList"
            :key="item.id"
          >
            <template v-if="form.type == 0 && item.moveLeft != 100">
              <view style="padding-left: 20rpx">
                <view class="s1">
                  <text class="ng-binding">{{ item.ptitle }}</text>
                  <text class="ng-binding in_money" v-if="item.ostyle == 0">
                    <i class="buytop"></i>
                    {{$t('language.account.buy_up')}}（￥{{ item.fee }}）
                  </text>
                  <text class="ng-binding out_money" v-if="item.ostyle == 1">
                    <i class="buydown"></i>
                    {{$t('language.account.buy_short')}}（￥{{ item.fee }}）
                  </text>
                  <text class="ng-binding out_money" v-if="item.ostyle == 3">
                    <i class="buydown"></i>
                    {{$t('language.account.commissioned_to_buy')}}（￥{{ item.fee }}）
                  </text>
                </view>
                <view class="ng-binding s2">
                  <text>{{ item.buyprice }}</text>
                  <text>-</text>
                  <text
                    class="ng-binding"
                    :class="item.ostyle == 0 ? 'in_money' : 'out_money'"
                    >{{ item.zPrice }}
                  </text>
                </view>
                <view class="ng-binding s3"
                  >{{ $u.timeFormat(item.buytime, "yyyy-mm-dd hh:MM:ss") }}
                </view>
              </view>
              <view>
                <view class="ng-binding text-right" v-if="item.ostyle == 0">
                  <text v-if="item.zPrice > item.buyprice" class="in_money">{{
                    $util.$h.Mul(item.fee, item.endloss / 100)
                  }}</text>
                  <text v-else class="out_money">{{
                    $util.$h.Mul(item.fee, item.lossrate / 100)
                  }}</text>
                </view>
                <view class="ng-binding text-right" v-if="item.ostyle == 1">
                  <text v-if="item.zPrice < item.buyprice" class="out_money">{{
                    $util.$h.Mul(item.fee, item.endloss / 100)
                  }}</text>
                  <text v-else class="in_money">{{
                    $util.$h.Mul(item.fee, item.lossrate / 100)
                  }}</text>
                </view>
                <!-- <view class="ng-binding text-right">
                  <u-count-down
                    :time="
                      ($util.$h.Add(item.buytime, item.endprofit) - time) * 1000
                    "
                    format="HH:mm:ss"
                    @change="countdown($event, item, index)"
                  ></u-count-down>
                </view> -->
              </view>
              <view class="point-box">
                <view
                  class="point-line"
                  :style="{ width: item.moveLeft + '%' }"
                >
                </view>
                <view class="point" :style="{ left: item.moveLeft + '%' }">
                </view>
              </view>
            </template>
            <template v-if="form.type == 1">
              <view style="padding-left: 20rpx">
                <view class="s1">
                  <text class="ng-binding">{{ item.ptitle }}</text>
                  <!-- <text class="ng-binding in_money" v-if="item.ploss > 0">
                    <i class="buytop"></i>
                    买涨（￥{{ item.fee }}）
                  </text>
                  <text class="ng-binding out_money" v-if="item.ploss < 0">
                    <i class="buydown"></i>
                    买跌（￥{{ item.fee }}）
                  </text>
                  <text class="ng-binding" v-if="item.ploss == 0">
                    <i class="buydown"></i>
                    买跌（￥{{ item.fee }}）
                  </text> -->
                  <text class="ng-binding in_money" v-if="item.ostyle == 0">
                    <i class="buytop"></i>
                    {{$t('language.account.buy_up')}}（￥{{ item.fee }}）
                  </text>
                  <text class="ng-binding out_money" v-if="item.ostyle == 1">
                    <i class="buydown"></i>
                    {{$t('language.account.buy_short')}}（￥{{ item.fee }}）
                  </text>
                  <text class="ng-binding out_money" v-if="item.ostyle == 3">
                    <i class="buydown"></i>
                    {{$t('language.account.commissioned_to_buy')}}（￥{{ item.fee }}）
                  </text>
                </view>
                <view class="ng-binding s2">
                  <text>{{ item.buyprice }}</text>
                  <text>-</text>
                  <text
                    class="ng-binding"
                    :class="
                      item.ploss == 0
                        ? ''
                        : item.ploss > 0
                        ? 'in_money'
                        : 'out_money'
                    "
                    >{{ item.sellprice }}
                  </text>
                </view>
                <view class="ng-binding s3"
                  >{{ $u.timeFormat(item.buytime, "yyyy-mm-dd hh:MM:ss") }}
                </view>
              </view>
              <view>
                <view
                  class="ng-binding text-right in_money"
                  v-if="item.ploss > 0"
                  >+{{ item.ploss }}
                </view>
                <view
                  class="ng-binding text-right out_money"
                  v-if="item.ploss < 0"
                  >{{ item.ploss }}
                </view>
                <view class="ng-binding text-right" v-if="item.ploss == 0">{{
                  item.ploss
                }}</view>
                <view class="ng-binding text-right">
                  <text>{{
                    $u.timeFormat(item.selltime, "yyyy-mm-dd hh:MM:ss")
                  }}</text>
                </view>
              </view>
            </template>
          </view>
        </scroll-view>
      </view>
    </view>
  </view>
</template>

<script>
import { account, ajaxorderList, updateProducts } from "@/config/api.js";
export default {
  data() {
    return {
      isShow: true,
      tabsList: [
        {
          id: 0,
          name: this.$t('language.account.Position_Details'),
        },
        {
          id: 1,
          name: this.$t('language.account.History_Details'),
        },
      ],
      scrollTop: 0,
      account: {},
      orderList: [],
      form: {
        uid: uni.getStorageSync("uid"),
        type: 0,
        page: 1,
        num: 30,
      },
      updateList: [],
      time: this.$util.time(),
      timeShow: true,
      KF: uni.getStorageSync("KF"),
      refreshTimer: null, // 定时刷新器
    };
  },
  onShow() {
    this.postAccount();
    this.updateProducts();
    console.log("time: ", this.time);
    // 启动定时刷新(每5秒刷新一次)
    this.refreshTimer = setInterval(() => {
      this.postAccount();
      this.updateProducts();
    }, 5000);
  },
  onHide() {
    // 页面隐藏时清除定时器
    if (this.refreshTimer) {
      clearInterval(this.refreshTimer);
      this.refreshTimer = null;
    }
  },
  onUnload() {
    // 页面卸载时清除定时器
    if (this.refreshTimer) {
      clearInterval(this.refreshTimer);
      this.refreshTimer = null;
    }
  },
  methods: {
    // 倒计时
    countdown(e, item, index) {
      // console.log('index: ', index)
      // console.log('e: ', e)
      // time 当前还剩多少秒
      let time =
        this.$util.$h.Add(item.buytime, item.endprofit) - this.$util.time();
      // console.log('time: ', time)
      // 当前百分比
      let percent = this.$util.GetPercent(time, item.endprofit);
      let value = this.$util.$h.Sub(100, percent);
      console.log("value: ", value);
      this.$set(this.orderList[index], "moveLeft", value);
    },
    // 获取首页产品最新价格
    updateProducts() {
      updateProducts({
        uid: uni.getStorageSync("uid"),
      })
        .then((res) => {
          console.log("获取首页产品最新价格: ", res);
          this.updateList = res.data;
          this.ajaxorderList();
        })
        .catch((err) => {
          console.log("err: ", err);
        });
    },
    // 账户信息
    postAccount() {
      account({
        uid: uni.getStorageSync("uid"),
      })
        .then((res) => {
          console.log("账户信息: ", res);
          this.account = res.data;
        })
        .catch((err) => {
          console.log("err: ", err);
        });
    },
    // 交易记录
    ajaxorderList() {
      ajaxorderList(this.form)
        .then((res) => {
          console.log("交易记录: ", res);
          this.updateList.map((item, index) => {
            res.data.map((item1, index1) => {
              if (item.id == item1.pid) {
                item1.zPrice = item.Price;
              }
            });
          });
          this.orderList = [...this.orderList, ...res.data];
          this.form.page++;
          console.log("this.orderList: ", this.orderList);
        })
        .catch((err) => {
          console.log("err: ", err);
        });
    },
    // tabs切换
    tabsChange(item) {
      this.form.type = item.id;
      this.form.page = 1;
      this.orderList = [];
      this.ajaxorderList();
      console.log("this.form.type: ", this.form.type);
    },
    // 滚动到底部触发
    lower() {
      this.ajaxorderList();
    },
    // 打开客服
    openKF(value) {
      window.open(value);
    },
  },
};
</script>

<style scoped lang="scss">
page {
  background-color: #21212b !important;
}

.account {
  margin: 0 32rpx;

  .account-title {
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    line-height: 37px;
  }

  .account-head {
    width: 100%;
    background: url(@/static/images/zhanghubg.png) no-repeat 30% 20%/960px;
    height: 296rpx;
    padding: 42rpx 32rpx;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-sizing: border-box;
    border-radius: 28rpx;

    .top {
      display: flex;
      justify-content: space-between;
      color: #fff;

      .top-left {
        position: relative;

        .t11 {
          font-size: 10px;
          font-weight: 700;
          color: #a5b7d3;
        }

        .t22 {
          font-size: 27px;
          line-height: 29px;
          margin-top: 10px;
          font-weight: 700;
        }

        .refresh {
          position: absolute;
          top: 20px;
          right: -50px;
          display: inline-block;
          height: 30px;
          line-height: 30px;
          font-size: 12px;
          color: #a5b7d3;
        }
      }

      .top-right {
        width: 26rpx;

        image {
          width: 100%;
          -webkit-user-drag: none;
        }
      }
    }

    .down {
      display: flex;
      justify-content: space-between;

      .it {
        .h5 {
          font-size: 10px;
          font-weight: 700;
          color: #a5b7d3;
          line-height: 18px;
        }

        .h6 {
          margin-top: 10px;
          font-size: 11px;
          line-height: 11px;
          font-weight: 700;
          color: #fff;
        }
      }
    }
  }

  .account-list {
    padding: 38rpx 0;

    .ul {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;

      .li {
        width: 25%;

        .navtor {
          padding: 10rpx 0;
          display: inline-block;

          image {
            width: 36rpx;
            vertical-align: middle;
            margin-right: 20rpx;
          }

          text {
            font-size: 22rpx;
            vertical-align: middle;
            font-weight: 700;
            color: #fff;
          }
        }
      }

      .left {
        text-align: left;
      }

      .center {
        text-align: center;
      }

      .right {
        text-align: right;
        padding-right: 20rpx;
      }
    }
  }
}

.accound-tabs {
  width: 100%;
  border-bottom: 1px solid #181818;
}

.tabs-list {
  width: 100%;
  font-size: 24rpx;

  .list-ul {
    width: 100%;

    .scroll-Y {
      height: 55vh;

      .paddingB {
        padding-bottom: 25rpx;
      }

      .list-li {
        display: flex;
        margin: 10rpx 0;
        position: relative;

        .point-box {
          width: 100%;
          height: 25rpx;
          // border: 1px solid #fff;
          position: absolute;
          top: 125rpx;
          display: flex;
          align-items: center;

          .point {
            width: 20rpx;
            height: 20rpx;
            border-radius: 50%;
            background-color: #ffe88a;
            position: absolute;
          }

          .point-line {
            height: 12rpx;
            background-color: #3aa0ff;
          }
        }

        .s1 {
          height: 40rpx;
        }

        .s2 {
          height: 40rpx;
        }

        .s3 {
          height: 40rpx;
          color: #696969;
        }

        .buydown {
          display: inline-block;
          width: 24rpx;
          height: 24rpx;
          background: url("@/static/images/buydown.png");
          background-size: 100% 100%;
          vertical-align: middle;
          margin-left: 100rpx;
          margin-right: 15rpx;
          vertical-align: middle;
        }

        .buytop {
          display: inline-block;
          width: 24rpx;
          height: 24rpx;
          background: url("@/static/images/buytop.png");
          background-size: 100% 100%;
          vertical-align: middle;
          margin-left: 100rpx;
          margin-right: 15rpx;
          vertical-align: middle;
        }
      }
    }
  }

  /* .ng-binding {
    // padding: 10rpx 0;
  } */

  .text-right {
    padding-right: 20rpx;
  }

  .in_money {
    color: #fa2e42 !important;
  }

  .out_money {
    color: #1fc65b !important;
  }

  .color-gray {
    color: #696969;
  }
}
</style>
