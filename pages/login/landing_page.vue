<template>
  <view class="landing-index">
   <!-- <view class="landing-head-top">
      <image src="/static/images/log1.png"
             mode="widthFix"></image>
    </view> -->
   <!-- <view class="landing-banner img-row">
      <image src="/static/images/landing_banner.bmp"
             mode="widthFix"></image>
    </view> -->
    <view class="landing-brand">
      <video class="video-background"
             src="/static/images/MP4/video.mp4"
             autoplay
             loop
             muted
             :controls="false"
             object-fit="cover"></video>
    </view>
    <view class="landing-service">
      <view class="p7 text-center">{{$t('language.landing_page.service')}}</view>
      <!-- <view class="p2 text-center">CHARACTERISTIC SERVICE</view> -->
      <view class="m-flex m-row-between m-col-top landing-service-list">
        <view class="service-list-item img-row text-center"
              @click="toService(item)"
              v-for="(item,index) in service_list"
              :key="index">
          <image :src="item.img"
                 mode="widthFix"></image>
          <view style="margin-top: 20rpx;">{{item.title}}</view>
          <!-- <view>{{item.content}}</view> -->
        </view>
      </view>
    </view>
    <view class="landing-introduce img-row">
      <image :src="lang=='zh-CN'?'/static/images/gongsijieshao.png':'/static/images/gongsijieshaoeng.png'"
             mode="widthFix"></image>
    </view>
	<view class="landing-introduce img-row">
	  <image :src="lang=='zh-CN'?'/static/images/landing_footereng.png':'/static/images/landing_footereng.png'"
	         mode="widthFix"></image>
	</view>
  <!--  <view class="landing-footer img-row">
      <image :src="lang=='zh-CN'?'/static/images/landing_footer.png':'/static/images/landing_footereng.png'"
             mode="widthFix"></image>
    </view> -->
  </view>
</template>

<script>
export default {
  data () {
    return {
		lang:uni.getStorageSync('lang'),
      service_list: [
        { img: '/static/images/wangshangkaihu.png', title: this.$t('language.landing_page.open_account'), content: 'Open an account online' },
        { img: '/static/images/xianhuoshangpin.png', title: this.$t('language.landing_page.goods'), content: 'Spot financing' },
        { img: '/static/images/lianxikefu.png', title: this.$t('language.landing_page.guide'), content: 'Billing guide' },
        { img: '/static/images/gerenzhongxin.png', title: this.$t('language.landing_page.person'), content: 'Warehousing logistics' }
      ]
    }
  },
  methods: {
    toService (item) {
      this.$Router.push('/pages/login/login')
    }
  }
}
</script>

<style lang="scss" scoped>
  page {
    background-color: $uni-bg-color;
  }
  .landing-head-top {
    padding: $m-padding 0;
    image {
      width: 200rpx;
    }
  }
  .landing-service {
    padding: $m-padding 0;
    line-height: 1.5;
    .landing-service-list {
      margin: $m-margin 0;
      .service-list-item {
        width: 22%;
        line-height: 1.5;
      }
    }
  }
  .landing-brand {
      position: relative;
      width: 100%;
      height: 50vw; /* 您可以根据需要调整视频的高度 */
      overflow: hidden;
    }
    .video-background {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }
    .landing-head-top {
      padding: $m-padding 0;
      image {
        width: 200rpx;
      }
    }
</style>
