<template>
  <div>
    <div class="w-full" :style="widthResize" style="float: left;">
      <div class="m-8px p-16px bg-white radius-16 border-arrival">
        <div class="flex text-h20-24" style="margin-bottom: 20px;">
          <div class="mb-4">
            Продажа {{buyInfoBTC}} (Биткоин)
          </div>
        </div>
        <div style="display: flex;margin-bottom: 12px">
          <b-table
              :items="sell"
              :fields="fields"
          >
            <template #cell(priceWallet)="row">
              <div style="color: #7e57c2; display: table;width: 100%; heigth: 100% ">{{ price }}</div>
            </template>
            <template #cell(origQty)="row">
              <div style="color: #13653f; display: table;width: 100%; heigth: 100% ">{{ row.value }}</div>
            </template>
            <template #cell(price)="row">
              <div style="color: #3e4451;display: table;width: 100%; heigth: 100% ">{{ row.value }}</div>
            </template>
            <template #cell(time)="row">
              <div style="color: red; display: table;width: 100%; heigth: 100% ">{{ timeConverter(row.value) }}</div>
            </template>
          </b-table>
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
  name: 'Menu',
  props: {
    price: {
      default: 0
    },
    sell: {
      default: []
    },
    buyInfoBTC: {
      default: 0
    },
  },
  data() {
    return {
      baksCount: 1,
      baks: 1,
      count: 1,
      widthResize: 'width: 50%',
      fields: [
        {key: 'time', label: 'Время покупки', sortable: false, class: 'text-center'},
        { key: 'priceWallet', label: 'Курс сейчас(доллары)', sortable: false, class: 'text-center'},
        { key: 'price', label: 'За сколько купим(доллары)', sortable: false, class: 'text-center' },
        { key: 'origQty', label: 'Сколько отдали(биткоины)', sortable: false, class: 'text-center'},
      ],
      items: []
    };
  },
  methods: {
    timeConverter(UNIX_timestamp){
      var a = new Date(UNIX_timestamp);
      var year = a.getFullYear();
      var month = a.getMonth()+1;
      var date = a.getDate();
      var hour = a.getHours();
      var min = a.getMinutes();
      var sec = a.getSeconds();
      var time = date + '.' + month + '.' + year + ' ' + hour + ':' + min;
      return time;
    }
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
