<template>
  <view class="chart-wrap">
    <!-- <div class="chart-block"></div> -->
    <div class="chart"
         ref="chart">
      <!-- <view class="chart-tag m-flex">
				<view class="diff">
					1
				</view>
				<view class="dea">
					2
				</view>
				<view class="macd">
					3
				</view>
			</view> -->
    </div>
  </view>
</template>

<script>
import {
  init,
  registerStyles
} from '@/utils/klinecharts.min.js'
export default {
  name: "m-kline",
  data () {
    return {
      chart: null, //k线图实例化
      itemshow: false
    }
  },
  // 计算属性
  // computed: {
  //   // 获取当前时间
  //   itemshow () {
  //     setTimeout(() => {
  //       return true
  //     }, 3000)
  //   }
  // },
  props: {
    list: Array,
    update: Object,
    type: {
      type: Number,
      default: 0
    },
    time: [String, Number]
  },
  watch: {
    list (newVal, oldVal) {
      if (this.chart) {
        this.applyNewData(newVal)
      } else {
        let i = 0
        const time = setInterval(() => {
          console.log('数据加载中', newVal);
          this.applyNewData(newVal)
          if (this.chart || i >= 3) {
            clearInterval(time)
          }
          i++
        }, 1000)
      }
    },
    update (val) {
      let list = this.chart.getDataList(),
        time_arr = {
          1: 60,
          5: 5 * 60,
          15: 15 * 60,
          30: 30 * 60,
          60: 60 * 60,
          d: 24 * 60 * 60
        }
      const time = time_arr[this.time] * 1000,
        last_list = list[list.length - 1],
        val_str = JSON.parse(JSON.stringify(val))
      if (val_str.timestamp - time < last_list.timestamp) {
        val.timestamp = last_list.timestamp
      }
      // console.log(val.timestamp);
      this.updateData(val)
    },
    type (val) {
      let style = this.chart.getStyles()
      if (val) {
        style.candle.type = "area"
        this.chart.removeIndicator('candle_pane', 'MA')
      } else {
        style.candle.type = "candle_down_stroke"
        this.chart.createIndicator({
          name: 'MA',
          calcParams: [1, 5, 10],
        }, false, {
          id: 'candle_pane'
        })
      }
      this.chart.setStyles(style)
    }
  },



  mounted () {
    this.klineInit()
    this.getitemshow()
  },


  methods: {
    getitemshow () {

    },
    // 初始化k线图
    klineInit () {
      const chart_dom = this.$refs.chart
      let options = require('./options.js')
      this.chart = init(chart_dom, options)
      let style = this.chart.getStyles()
      style.grid.show = false
      style.grid.horizontal.color = "transparent"
      style.grid.vertical.color = "#ededed2e"
      style.xAxis.axisLine.color = "transparent"
      style.yAxis.axisLine.color = "transparent"
      style.yAxis.tickLine.color = "#ededed2e"
      style.separator.color = '#ededed2e'
      let line_color = ['red', 'blue', 'green']
      style.indicator.lines.map((value, index) => {
        value.smooth = true
        if (line_color[index]) {
          value.color = line_color[index]
        }
      })
      style.candle.margin = {
        top: 0.2,
        bottom: 0.2
      }
      style.candle.bar = {
        upColor: '#6ed79a',
        downColor: '#e95b61'
      }
      style.candle.area = {
        lineSize: 1,
        lineColor: '#d2c01e',
        value: 'high',
        backgroundColor: [{
          offset: 0,
          color: 'rgba(110, 215, 154, 0.01)'
        }, {
          offset: 10,
          color: 'rgba(233, 91, 97, 0.2)'
        }]
      }
      style.indicator.tooltip.showRule = "none"
      style.candle.tooltip.custom = (e) => {
        const {
          close,
          high,
          low,
          open,
          timestamp
        } = e,
          time = new Date(timestamp),
          time_text = `${time.getHours()}:${time.getMinutes()}:${time.getSeconds()}`
        return [{
          title: {
            text: 'Time：',
            color: '#fff'
          },
          value: {
            text: time_text,
            color: '#fff'
          }
        },
        {
          title: '',
          value: {
            text: close,
            color: 'red'
          }
        },
        {
          title: '',
          value: {
            text: open,
            color: 'blue'
          }
        },
        {
          title: '',
          value: {
            text: low,
            color: 'green'
          }
        },
        {
          title: '',
          value: {
            text: high,
            color: 'yellow'
          }
        },
        ]
      }
      style.candle.type = "candle_solid"
      this.chart.setStyles(style)
      this.chart.setScrollEnabled(false)
      this.chart.setBarSpace(6)
      // this.chart.setPriceVolumePrecision(2)
      // this.chart.resize(10)

      // this.chart.priceMark.show = false
      this.chart.setOffsetRightDistance(10)
    },
    // 添加k线图数据
    applyNewData (list) {
      // this.chart.removeIndicator('candle_pane', 'MA')
      // this.chart.removeIndicator('candle_macd', 'MACD')
      this.chart.applyNewData(list || [])
      // this.chart.createIndicator({
      //   name: "MACD",
      //   calcParams: [1, 2, 3]
      // }, false, {
      //   id: 'candle_macd',
      //   height: 100
      // })
      // this.chart.createIndicator({
      //   name: 'MA',
      //   calcParams: [1, 5, 10],
      // }, false, {
      //   id: 'candle_pane'
      // })
      this.chart.setPriceVolumePrecision(4, 4)
    },
    updateData (list) {
      // console.log('更新图表',list);
      this.chart.updateData(list)
      // this.chart.overrideIndicator({
      //   name: "MACD",
      //   calcParams: [1, 2, 3]
      // }, false, {
      //   id: 'candle_macd',
      //   height: 100
      // })
      // this.chart.overrideIndicator({
      //   name: 'MA',
      //   calcParams: [1, 5, 10],
      // }, false, {
      //   id: 'candle_pane'
      // })
    }
  }
}
</script>

<style lang="scss" scoped>
  .chart-wrap {
    position: relative;
    width: 100%;

    .chart-block {
      padding-bottom: 100% * (452/414);
    }

    .chart {
      position: absolute;
      top: 0;
      width: 100%;
      height: 300px;
      .chart-tag {
        position: absolute;
        bottom: 0;

        & > view {
          margin-left: 0.5em;
        }

        .dea {
          color: red;
        }

        .macd {
          color: blue;
        }
      }
    }
  }
</style>
