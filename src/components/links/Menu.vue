<template>
  <div>
    <div class="w-full" :style="widthResize" style="float: left;">
      <div class="m-8px p-16px bg-white radius-16 border-arrival">
        <div class="flex text-h20-24" style="margin-bottom: 20px;">
          <div class="mb-4">
            Меню
          </div>
        </div>
        <div style="margin-bottom: 12px;">
          <div style="display: flex;">
            <div style=" padding-right: 20px;">Максимальное количество ставок: </div>
            <b-form-spinbutton id="demo-sb" min="1" max="500" style="width: 110px;" v-model="count"></b-form-spinbutton>
            <div style=" padding-left: 20px;padding-right: 20px;">Плечо ставок в долларах: </div>
            <b-form-spinbutton id="demo-sb" min="1" max="5000" style="width: 110px;" v-model="baks"></b-form-spinbutton>
          </div>
        </div>
        <div style="margin-bottom: 12px;padding-top: 25px;">
          <div style="display: flex;">
            <div style="padding-right: 20px;">Интервал ставок в долларах: </div>
            <b-form-spinbutton id="demo-sb" min="1" max="2000" style="width: 110px;" v-model="baksCount"></b-form-spinbutton>
            <div style=" padding-left: 20px;padding-right: 20px;">Между новой сделкой в долларах: </div>
            <b-form-spinbutton id="demo-sb" min="1" max="2000" style="width: 110px;" v-model="baksAndCount"></b-form-spinbutton>
          </div>
        </div>
        <div style="display: flex;padding-top: 25px;">
          <div style=" padding-right: 20px; width: 250px; padding-top: 5px;">API ключ: </div>
          <b-form-input style="margin-right: 12px" placeholder="API ключ" :type="'text'" v-model="api"></b-form-input>
          <div style=" padding-left: 20px;padding-right: 20px;width: 400px;padding-top: 5px;">Секретный ключ: </div>
          <b-form-input style="margin-right: 12px" placeholder="Секретный ключ" :type="'text'" v-model="secret"></b-form-input>
        </div>
        <b-button block style="width: 100%; margin-top: 20px;" variant="primary" @click="save()">Сохранить</b-button>
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
    settings: {
      default: [{
        max: 1,
        plecho: 1,
        intervals: 1,
        new: 1,
        api: '',
        secret: ''
      }]
    }
  },
  components: {
    apexchart
  },
  data() {
    return {
      baksCount: 1,
      baks: 1,
      count: 1,
      baksAndCount: 1,
      api: '',
      secret: '',
      widthResize: 'width: 100%',
    };
  },
  mounted() {
    this.baksCount = Number(this.settings[0].intervals);
    this.baks = Number(this.settings[0].plecho);
    this.count = Number(this.settings[0].max);
    this.baksAndCount = Number(this.settings[0].new);
    this.api = this.settings[0].api;
    this.secret = this.settings[0].secret;
  },
  methods: {
    save() {
      let _this = this;

      let item = {
        intervals: String(this.baksCount),
        plecho: String(this.baks),
        maxs: String(this.count),
        news: String(this.baksAndCount),
        api: String(this.api),
        secret: String(this.secret),
      };

      axios.post( '/settings/updates', item, {
      }).then(function(res){
      });
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
