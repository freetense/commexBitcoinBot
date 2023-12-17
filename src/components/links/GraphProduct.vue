<template>
<div>
    <div class="w-full" :style="widthResize" style="float: left;">
      <div class="m-8px p-16px bg-white radius-16 border-arrival">
        <div class="flex text-h20-24" style="margin-bottom: 20px;">
          <div>
            Среднее количестов дней между закупками продукции
          </div>
        </div>
        <div v-if="!uploadGraph">
          <div class="inline-flex pointer items-center ml-40px" style="display: inline-flex;">
            <div class="ml-12px radius-100 flex-none"
                 style="width: 8px; height: 8px; margin-left: 12px;background-color: rgb(187, 154, 244);
                 border-radius: 100%;margin-top: 28px;padding-top: 4px;"></div>
            <div class="opacity-72 whitespace-nowrap text-c13 color-titanic" style="margin-left: 12px">
              Среднее количество дней
            </div>
          </div>
          <div class="inline-flex pointer items-center" style="display: inline-flex;">
            <div class="ml-12px radius-100 flex-none"
                 style="width: 8px; height: 8px; margin-left: 12px;background-color: rgb(141, 201, 94);
                 border-radius: 100%;margin-top: 28px;padding-top: 4px;"></div>
            <div class="opacity-72 whitespace-nowrap text-c13 color-titanic" style="margin-left: 12px">
              Прошло дней с последней покупки
            </div>
          </div>
          <div class="inline-flex pointer items-center" style="display: inline-flex;">
            <div class="ml-12px radius-100 flex-none"
                 style="width: 8px; height: 8px; margin-left: 12px;background-color: rgb(100,149,237);
                 border-radius: 100%;margin-top: 28px;padding-top: 4px;"></div>
            <div class="opacity-72 whitespace-nowrap text-c13 color-titanic" style="margin-left: 12px">
              На сколько должно хватить продукции (дни)
            </div>
          </div>
          <div v-if="notGraph">
            <div class="mt-20px" :key="count">
              <apexchart
                class="isSingleItem"
                :type="'bar'"
                :options="chartOptions"
                :series="series"
                height="300"></apexchart>
            </div>
          </div>
          <div v-else class="overflow-hidden" style="height: 223px; position: relative;margin-bottom: 52px;">
            <div width="100%" style="min-height: 238px;">
              <div
                   class="apexcharts-canvas apexchartsiiyag009 apexcharts-theme-light"
                   style="width: 476px; height: 223px;margin: auto;">
                <svg width="476" height="223" xmlns="http://www.w3.org/2000/svg"
                     version="1.1" class="apexcharts-svg" transform="translate(0, 0)" style="background: transparent;margin-top: 26px;">
                  <g class="apexcharts-annotations"></g>
                  <g class="apexcharts-inner apexcharts-graphical">
                    <defs></defs>
                  </g>
                  <text font-family="Golos UI, Trebuchet MS, Verdana, sans-serif"
                        x="238" y="129.5" text-anchor="middle" dominant-baseline="auto"
                        font-size="16px"  fill="RGBA(4,21,62,0.49)"
                        class="apexcharts-text "
                        style="font-family: 'Golos UI';, 'Trebuchet MS', Verdana, sans-serif; opacity: 1;">
                    {{ text }}
                  </text>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import apexchart from 'vue-apexcharts';
import axios from "axios";
const dataEndTr = [];

export default {
  name: 'GraphZakaz',
  props: {},
  components: {
    apexchart
  },
  data() {
    return {
      uploadGraph: true,
      text: '',
      dataCheck: true,
      dataEndCheck: true,
      days: [],
      dataRec: {},
      dataEndRec: {},
      dataEndRecToo: {},
      periodCheck: [],
      data: [],
      dataEnd: [],
      widthResize: 'width: 100%',
      resize: true,
      graf: true,
      count: 0,
      countDays: 0,
      notGraph: false,
      period: null,
      selectedPeriod: 'Месяц',
      periodList: [
        {id: 'today', active: false, value: 'Сегодня'},
        {id: 'thisWeek', active: false, value: 'Неделя'},
        {id: 'thisMonth', active: true, value: 'Месяц'},
        {id: 'thisPeriod', active: false, value: 'Период'}
      ],
      selectedPeriodId: 'thisMonth',
      series: [],
      chartOptions: {}
    };
  },
  computed: {
    notData() {
      this.text = 'Нет данных за выбранный период';
    },
    dataTimeFunc() {
      this.series = [{
        name: 'Среднее количество дней',
        data: this.dataRec ? this.dataRec : []
      }, {
        name: 'Прошло дней с последней покупкия',
        data: this.dataEndRec ? this.dataEndRec : []
      }, {
        name: 'На сколько должно хватить продукции (дни)',
        data: this.dataEndRecToo ? this.dataEndRecToo : []
      }];
      this.uploadGraph = false;
      this.notData;
      this.chartOptions = {
        legend: {
          show: false,
        },
        chart: {
          width: '200px',
          toolbar: {
            show: false,
          },
          fontFamily: 'Golos UI, Trebuchet MS, Verdana, sans-serif',
          zoom: {
            enabled: false,
          },
        },
        // fill: {
        //   colors: [ ({ value, index, w }) => {
        //     return (typeof upLvl === 'number' && typeof downLvl === 'number')
        //       ? (value < upLvl && value > downLvl ? '#81ABEE' : '#F196A5')
        //       : ('#81ABEE')
        //   } ],
        // },
        grid: {
          show: true,
          borderColor: '#E4EDFB',
          xaxis: {
            lines: {
              show: false,
            },
          },
        },
        colors: ['rgb(187, 154, 244)', 'rgb(141, 201, 94)', 'rgb(100,149,237)'],
        plotOptions: {
          bar: {
            columnWidth: '80%',
            borderRadius: 1
          }
        },
        dataLabels: {
          enabled: false
        },
        states: {
          active: {
            filter: {
              type: 'none',
            },
          },
        },
        xaxis: {
          showForNullSeries: true,
          categories: Object.keys(this.days),
          type: 'category',
          tickPlacement: 'between',
          labels: {
            offsetX: 3,
            rotate: -25,
            rotateAlways: true,
            showDuplicates: false,
            hideOverlappingLabels: false,
            style: {
              fontSize: '9px',
              fontFamily: 'Golos UI, Trebuchet MS, Verdana, sans-serif',
              fontWeight: 400,
            },
          },
          axisTicks: {
            show: false,
          },
          axisBorder: {
            show: false,
          },
          crosshairs: {
            show: false,
          },
          tooltip: {
            enabled: false,
          },
        },
        yaxis: {
          show: true,
          showForNullSeries: true,
          labels: {
            show: true,
            formatter: function (val) {
              return parseInt(val);
            },
            style: {
              fontSize: '11px',
              fontFamily: 'Golos UI, Trebuchet MS, Verdana, sans-serif',
              fontWeight: 400,
            },
          },
        },
        noData: {
          text: 'Нет данных за выбранный период',
          align: 'center',
          verticalAlign: 'middle',
          offsetX: 0,
          offsetY: 0,
          style: {
            color: 'RGBA(4,21,62,0.49)',
            fontSize: '16px',
            fontFamily: 'Golos UI, Trebuchet MS, Verdana, sans-serif',
          },
        },
        markers: {
          size: 4,
          colors: '#fff',
          strokeWidth: 2,
          strokeColors: ['rgb(187, 154, 244)', 'rgb(141, 201, 94)', 'rgb(100,149,237)'],
          hover: {
            size: 4,
            //sizeOffset: 1,
          },
        },
        stroke: {
          show: true,
          // curve: 'smooth',
          width: 2,
        },
      }
    },
  },
  mounted() {
    let _this = this;
    axios.get('/products/graph').then(function (response) {
      _this.dataRec = Object.values(response.data).map(function (el) {
        return el[0];
      })
      _this.dataEndRec = Object.values(response.data).map(function (el) {
        return el[1];
      })
      _this.dataEndRecToo = Object.values(response.data).map(function (el) {
        return el[2];
      })
      _this.days = response.data
      if(_this.dataRec.length) {
        _this.notGraph = true;
      }
      _this.dataTimeFunc;
      _this.count++;
    });

  },
  methods: {
    resizeOff() {
      this.resize = false;
      this.widthResize = 'width: 50%';
      this.count++;
    },
    resizeOn() {
      this.resize = true;
      this.widthResize = 'width: 100%';
      this.count++;
    },
    grafOff() {
      this.graf = false;
      this.count++;
    },
    grafOn() {
      this.graf = true;
      this.count++;
    },
  }
};
</script>

<style>
.color-titanic {
  margin-top: 16px;
  padding-top: 4px;
  opacity: .72;
  white-space: nowrap;
  color: #04153e;
  font-size: 13px;
  line-height: 24px;
  font-weight: 500;
}
.rir-drag-upload-file__uploading{
  position:relative;
  display:flex;
  align-items:center;
  justify-content:center;
  height:96px;
  border-radius:8px;
}
.tooltip .tooltiptext {
  margin-top: -60px;
  margin-left: 10px;
  visibility: hidden;
  height: 30px;
  background: #FFFFFF;
  border-radius: 12px;
  font-family: Golos UI;
  font-style: normal;
  font-weight: normal;
  font-size: 11px;
  line-height: 16px;
  color: #04153E;
  padding: 8px 8px 8px 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);

  /* Разместите всплывающую подсказку */
  position: absolute;
  z-index: 1;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}

.tooltip .tooltipicon {
  content: "";
  position: absolute;
  width: 0;
  height: 0;
  border-width: 6px;
  border-style: solid;
  border-color: #FFFFFF transparent  transparent transparent;
  top: 30px;
  left: 10px;
}
.border-arrival {
  border: 1px solid #e4edfb;
}
.radius-16 {
  border-radius: 16px;
}
.bg-white {
  background-color: #fff;
}
.m-8px {
  margin: 8px;
}
.p-16px {
  padding: 16px;
}
.text-h20-24 {
  font-size: 20px;
  line-height: 24px;
  font-weight: 700;
}
</style>
