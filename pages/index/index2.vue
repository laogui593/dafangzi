<template>
  <view class="index">
    <u-swiper :list="index_swiper"
              height="169px"
              keyName="path"></u-swiper>
    <view class="index-nav model m-flex m-row-between">
      <view class="fast_buy">
        <view @click="openKF(KF)">
          <image src="/static/images/index_charge.png"
                 mode="widthFix"></image>
        <view @click="openKF(KF)"
              class="text-cz">
          <view class="luick-recharge">{{$t('language.home.Quick_recharge')}}</view>
          <view style="color:#828095">{{$t('language.home.Transfer_option')}}</view>
        </view>
        <view @click="openKF(KF)"
              class="">
          <image src="data:image/png;base64,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"
                 mode=""
                 style=""
                 class="image-tz" />
        </view>
      </view>
      <!-- <view class="recharge-option">
				<navigator class="m-flex c-box c-right-item" url="/pages/mine/msg_view?id=2">
					<m-image src="/static/images/guanyu.png" width="1.5em"></m-image>关于我们
				</navigator>
				<navigator @click="openKF(KF)" class="m-flex c-box c-right-item">
					<m-image src="/static/images/my2.png" width="1.5em"></m-image>在线客服
				</navigator>
			</view> -->
    </view>
    <view class="index-increase">
      <!-- <view class="index-increase-title p4 model">涨幅榜</view>
			<view class="" style="padding: 0 30rpx;">
				<view class="table-title cc-row-between">
					<view class="cc-cols-1">
						LOGO
					</view>
					<view class="cc-cols-2">
						名称
					</view>
					<view class="cc-cols-3">
						最新价
					</view>
					<view class="cc-cols-4">
						交易状态
					</view>
					</view> -->
					
      <view class="product-list">
		 <view class="luobo">
			 <view  style="float:left;margin-left: 20px;">Product</view>
			 <view  style="float:right;margin-right: 30px;">Price</view>
		 </view> 
        <view class="product-info"
              v-for="item in products"
              :key="item.id"
              @click="toTrade(item)"
              v-if="item.isdelete==0">
          <view class="info">
            <!-- <view class=" ">
              <image :src="item.img|filterImg"
                     class="info-img"></image>
            </view> -->
            <view class="info-name ">
              <view class="">
                {{item.title}}
              </view>
            </view>
            <view class="info-price">
              <!-- <text class="jy">{{item.is_deal==0?$t('language.home.Closed'):$t('language.home.trade')}}</text> -->
              <text :class="item.is_rise==2?'lvse':'honse'">{{item.Price}}</text>
            </view>
          </view>
          <!-- <view class="cc-line">
          </view> -->
        </view>
      </view>
    </view>
  </view>
  </view>
</template>

<script>
// 引入请求
import {
  url,
  getMenu,
  getProducts,
  updateProducts
} from '../../config/api.js'
export default {
  data () {
    return {
      index_swiper: [], //轮播图
      products: [], //交易对
      productsTime: 0, //交易对定时器
      KF: uni.getStorageSync('KF'),
    }
  },
  // 计算属性
  computed: {
    // 过滤图片
    userkf () {
      // return this.KF
      // 读取缓存中的数据
      let KF = uni.getStorageSync('KF')
      if (KF) {
        return KF
      } else {
        return ''
      }
    }
  },
  onLoad () {
	  uni.setTabBarItem({
	  	index: 0,
	  	text: uni.getStorageSync('lang')=='zh-CN' ? '首页' : 'home'
	  })
	  uni.setTabBarItem({
	  	index: 1,
	  	text: uni.getStorageSync('lang')=='zh-CN' ? '交易' : 'trade'
	  })
	  uni.setTabBarItem({
	  	index: 2,
	  	text: uni.getStorageSync('lang')=='zh-CN' ? '账户' : 'account'
	  })
	  uni.setTabBarItem({
	  	index: 3,
	  	text: uni.getStorageSync('lang')=='zh-CN' ? '我的' : 'mine'
	  })
    this.getSwiper() //获取轮播图
  },
  onShow () {
    this.myGetProducts() //获取底部交易对

  },
  onHide () {
    this.myClearInterval(this.productsTime)
    this.productsTime = null
  },
  methods: {
    GotoKF () {
      uni.navigateTo({
        url: this.userkf
      })
    },
    // 前往交易市场
    toTrade (item) {
      this.$Router.pushTab(`/pages/trade/trade`)
      uni.setStorageSync('trade_pid', {
        id: item.id,
        title: item.title
      })
    },
    // 获取轮播图
    getSwiper () {
      getMenu().then(res => {
        // console.log('轮播图', res);
        // 拼接路径
        res.data.map(value => {
          value.path = url + value.path
        })
        this.index_swiper = res.data
      })
    },
    // 获取底部交易对
    myGetProducts () {
      const that = this
      getProducts().then(async res => {
        // console.log('底部交易对', res);
        this.products = res.data
        // 存入缓存
        uni.setStorageSync('storage_key', res.data);
        this.myUpdateProducts()
        // 开启定时更新任务
        this.productsTime = setInterval(() => {
          this.myUpdateProducts()
        }, 2000)
      })
    },
    // 更新产品波动
    myUpdateProducts () {
      updateProducts().then(res => {
        // console.log('更新产品波动', res);
        res.data.map(value => {
          let index = this.products.findIndex(item => item.id == value.id)
          if (index != -1) {
            this.products[index].Price = value.Price
            this.products[index].isdelete = value.isdelete
            this.products[index].is_rise = value.is_rise
            this.products[index].is_deal = value.is_deal
          }
        })
      })
    },
    // 删除多余定时器
    myClearInterval (end) {
      if (!end) {
        end = 1000
      }
      for (let i = 0; i <= end; i++) {
        clearInterval(i)
      }
    },
    // 客服
    getService () {
      service().then(res => {
        console.log('客服', res);
        this.KF = res.data
        uni.setStorageSync('KF', res.data)
      })
    },
    // 打开客服
    openKF (value) {
      window.open(value)
    },
  }
}
</script>

<style lang="scss" scoped>
	.luobo{
		width:100%;
		color:#F8F8F8;
		font-size: 16px;
	}
  .product-list {
    display: flex;
    flex-wrap: wrap;
    padding-left: 20rpx;
    padding-right: 20rpx;
    // margin: 0 auto;
    .product-info {
      width: 100%;
      margin-top: 10px;
	  
      .info {
        padding: 5px;
        background-color: #262630;
        font-size: 12px;
        font-weight: 700;
        color: #828095;
		height:50px;
        // .info-img {
        //   background-color: #2d2d38;
        //   width: 100%;
        //   height: 100px;
        //   border-radius: 5px;
        // }
      }
      .info-name {
        margin-top: 5px;
        margin-bottom: 10px;
        background-color: #262630;
        font-size: 12px;
        font-weight: 700;
        color: #ffffff;
		float:left;
		font-size: 20px;
		margin-left: 20px;
		
      }
      .info-price {
        // display: flex;
		margin-top: 5px;
		margin-right: 30px;
		float:right;
		font-size: 20px;
        .lvse {
          color: green;
        }
        .honse {
          color: #ff0000;
        }
        .jy {
          color: #ffffff;
          margin-right: auto;
        }
      }
    }
  }

  .fast_buy {
    height: 71px;
    display: flex;
    align-items: center;
    background-color: #25252f;
    margin: 0 -15px;
    padding: 0 15px;

    width: 100%;
    margin: 0 auto;
    .text-cz {
      font-size: 14px;
      color: #fff;
      margin-left: 10px;
      margin-right: auto;
    }
    .image-tz {
      width: 26px;
      height: 26px;
    }
  }

  // ============================== //
  .table-item {
    margin-bottom: 30rpx;
  }

  .cc-line {
    height: 6rpx;
    background-image: linear-gradient(to right, #464646, #fff, #464646);
    margin-top: 30rpx;
  }

  .cc-row-between {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .cc-cols-1 {
    width: 20%;
  }

  .cc-cols-2 {
    width: 35%;
  }

  .cc-cols-3 {
    width: 25%;
    max-width: 25%;
    min-width: 25%;
    text-align: center;
  }

  .cc-cols-4 {
    width: 20%;
    text-align: right;
  }

  .c-box {
    background-color: rgba(255, 255, 255, 0.5);
    border-radius: 12rpx;
  }

  .c-left {
    width: 60%;
    height: 100%;
  }

  .c-row-around {
    display: flex;
    justify-content: space-around;
    align-items: center;
  }

  .c-right-item {
    height: 45%;
    width: 95%;
    justify-content: center;
  }

  .index-nav {
    color: #d5d7d0;
    line-height: 1.5;
    height: 160rpx;

    .luick-recharge {
      color: #fff;
      font-size: 36rpx;
      font-weight: 600;
    }

    .recharge-option {
      color: #fff;
      width: 40%;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: flex-end;

      .m-image-wrap {
        margin-right: 0.5em;
      }
    }

    image {
      width: 80rpx;
      margin-right: 10px;
    }
  }

  .rise {
    background-color: rgb(38, 168, 72);
    padding: 0.6rem 0.8rem;
    border-radius: 0.6rem;
  }

  .fall {
    background-color: rgb(255, 1, 5);
    padding: 0.6rem 0.8rem;
    border-radius: 0.6rem;
  }

  .index-increase {
    .index-increase-title {
      color: #00ffff;
    }

    .increase-list {
      padding: 0 $m-padding;
      background-color: rgb(34, 38, 41);

      .new-money {
        min-width: 9rem;
      }

      .increase-title {
        max-width: 8rem;
      }
    }
  }
</style>
