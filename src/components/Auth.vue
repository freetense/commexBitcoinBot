<template>
  <div class="message">
    <v-app-bar
        color="primary"
        density="compact"
        app
        style="height: 57px;"
    >
      <v-app-bar-title class="mt-2 flex" style="position: absolute">
        <v-icon style="float:left;padding-right: 10px; margin-top:7px" class="mr-4">mdi mdi-menu</v-icon>
        <span style="float:left;padding-right: 10px; margin-top:4px">{{items[model].title}} |</span>
        <div class="mr-4 mt-1" style="float:left;padding-right: 10px;">Курс:
          <span style="color: #7e57c2;">{{parseFloat(price[0].price).toFixed(2)}} |</span>
        </div>

        <div class="mr-4 mt-1" style="float:left;padding-right: 10px;">Сокет:
          <span style="color: red;">{{wsCount ? 'on' : 'off'}}</span> |
        </div>
        <div class="mr-4 mt-1" style="float:left;padding-right: 10px;">Статус:
          <span style="color: red;">{{stop ? 'off' : 'on'}}</span>
        </div>
        <b-button class="ml-4" @click="createdBit(false)">{{stop ? 'Запустить бота' : 'Остановить бота'}}</b-button>
        <b-button class="ml-4">{{currentTime}}</b-button>
      </v-app-bar-title>
      <v-app-bar-title class="mt-1 mr-4" style="right: 20px; position: absolute; height: 57px;">
        <b-button variant="dark">Было: {{toom}}</b-button>
        <b-button variant="dark">Сейчас: {{count}}</b-button>
        <b-button variant="dark" style="color: red;">Прибыль, убыток: {{parseFloat(plus).toFixed(2)}}</b-button>
        <b-button class="ml-4" @click="logOut()">Выход</b-button>
      </v-app-bar-title>
    </v-app-bar>
    <v-main>
      <v-container  fluid style="max-width: unset !important;box-shadow: unset !important;">
        <div v-if="model == 0">
          <b-button @click="audioStart()" class="ml-8">Звук монет <span style="color: red;">{{audioOn}}</span></b-button>
          <audio id="audio" style="visibility: hidden; height: 0px" controls src="https://test.sacura.info/web/sound.mp3">
          </audio>
          <Menu :settings="settings" :key="countMenu" />
          <OrderSell :buyInfoBTC="buyInfoBTC" :sell="sell" :price="price[0].price" />
          <OrderBy :buyInfoUSDT="buyInfoUSDT" :buy="buy" :price="price[0].price" />
         <!-- <GraphProduct /> -->
        </div>
      </v-container>
    </v-main>
    <v-sheet
        height="100%"
        class="overflow-hidden"
        style="position: relative;"
        :elevation="0"
    >
      <v-navigation-drawer
          class="accent-4"
          width="300"
          height="100vh"
          color="rgba(56, 95, 115,0.5)"
          :mini-variant.sync="mini"
          app permanent
      >
        <v-list-item class="px-2">
          <v-list-item-avatar>
            <v-img src="../web/img/img.jpg"></v-img>
          </v-list-item-avatar>

          <v-list-item-title style="margin-left: 12px">
            <b>
              Касаткин Сергей
            </b>
          </v-list-item-title>

          <v-btn
              icon
              @click.stop="mini = !mini"
          >
            <v-icon>mdi-chevron-left</v-icon>
          </v-btn>

        </v-list-item>

        <v-divider></v-divider>

        <v-list density="compact" nav>
          <v-list-item-group v-model="model">
            <v-list-item
                v-for="item in items"
                :key="item.title"
                @click="reloadItem()"
                link
            >
              <v-list-item-icon>
                <v-icon>{{ item.icon }}</v-icon>
              </v-list-item-icon>

              <v-list-item-content>
                <v-list-item-title style="margin-left: 12px;">{{ item.title }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>

          </v-list-item-group>
        </v-list>
      </v-navigation-drawer>
    </v-sheet>
  </div>

</template>

<script>
import {
  VSheet,
  VNavigationDrawer,
  VListItem,
  VListItemAvatar,
  VImg,
  VListItemTitle,
  VBtn,
  VList,
  VListItemIcon,
  VIcon,
  VListItemContent,
  VBadge,
  VBreadcrumbs,
  VTabs,
  VTab,
  VCardText,
  VWindow,
  VWindowItem,
} from 'vuetify/lib';
import 'vuetify/dist/vuetify.min.css';
import { VueEditor } from "vue2-editor";
import Menu from "./links/Menu.vue";
import GraphProduct from "./links/GraphProduct.vue";
import OrderBy from "./links/OrderBy.vue";
import OrderSell from "./links/OrderSell.vue";
import axios from "axios";
import hmacSHA256 from 'crypto-js/hmac-sha256';
import Base64 from 'crypto-js/enc-base64';
import { useSound } from '@vueuse/sound';
import buttonSfx from '../../web/sound.mp3';
var audio = new Audio('../../web/sound.mp3');
audio.play()

export default {
  components: {
    OrderBy,
    OrderSell,
    Menu,
    GraphProduct,
    VueEditor,
    VNavigationDrawer,
    VSheet,
    VListItem,
    VListItemAvatar,
    VImg,
    VListItemTitle,
    VBtn,
    VList,
    VListItemIcon,
    VIcon,
    VListItemContent,
    VBadge,
    VBreadcrumbs,
    VTabs,
    VTab,
    VCardText,
    VWindow,
    VWindowItem
  },
  data() {
    return {
      audioOn: 'off',
      items: [
        {title: 'Главная', icon: 'mdi-home-city'},
        {title: 'Настройки', icon: 'mdi-cogs'},
      ],
      countMenu: 0,
      model: 0,
      mini: true,
      price: [{price: 0}],
      currentTime: 15,
      stop: false,
      timer: null,
      timerPrice: null,
      buy: [],
      sell: [],
      buyInfoBTC: 0,
      buyInfoUSDT: 0,
      count: 0,
      plus: 0,
      toom: 0,
      stopCount: true,
      settings: [{
        max: 1,
        plecho: 1,
        intervals: 1,
        new: 1,
        api: '',
        secret: ''
      }],
      ws: null,
      audio: null,
      wsCount: false
    }
  },
  computed: {

  },
  mounted() {
    this.audio = document.getElementById('audio');
    this.createdBit(true)
  },
  watch: {
    currentTime(time) {
      if (time <= 0) {
        this.currentTime = 15;
      }
    }
  },
  methods: {
    audioStart() {
      if(this.audioOn == 'off') {
        this.audioOn = 'on';
        this.audio.play();
      } else {
        this.audioOn = 'off'
      }
    },
    socketWS() {
      let _this = this;
      _this.wsCount = false;
     // let wss = window.location.protocol === 'https:' ? 'wss' : 'ws';
    //  _this.ws = new WebSocket(wss+'://test.sacura.info:61111');
     // _this.ws.addEventListener('message', (event) => {
     //   _this.wsCount = event.data == 'ok' ? true : false; // get from server
    //  })
    },
    stopSave() {
      let _this = this;
      _this.settings[0].dis = _this.stop ? 1 : 0;
      let item = _this.settings[0];

      axios.post( '/settings/stop', item, {
      }).then(function(res){
      });
    },
    paaramsApi() {
      return {
        Accept: "application/json",
        "X-MBX-APIKEY": this.settings[0].api,
      };
    },
    async openOrders() {
      let _this = this;
      let secret = _this.secret();
      let curTime = Number(new Date().getTime()).toFixed(0);
      let string = "symbol=BTCUSDT&RecvWindow=60000&timestamp=" + curTime;
      let hmacDigest = hmacSHA256(string, secret);
      let url = "https://api.commex.com/api/v1/openOrders?" + string + "&signature=" + hmacDigest;

      await fetch(url, {
        method: "GET",

        headers: _this.paaramsApi(),
      })
      .then(function (res) {
        let buy = _this.buy.length;
        let sell = _this.sell.length;
        res.json().then(function (data) {
          _this.buy = data.filter(el => el.side == "BUY");
          _this.sell = data.filter(el => el.side == "SELL");

          if(buy != _this.buy.length || sell != _this.sell.length) {
            if (_this.audioOn != 'off') {
              _this.audio.play();
            }
          }
        });
      }).then(function (body) {
      });
    },
    async account() {
      let _this = this;
      let secret = _this.secret();
      let curTime = Number(new Date().getTime()).toFixed(0);
      var stringAccount = "RecvWindow=60000&timestamp=" + curTime;
      let hmacDigestAccount = hmacSHA256(stringAccount, secret);
      let urlAccount = "https://api.commex.com/api/v1/account?" + stringAccount + "&signature=" + hmacDigestAccount;
      await fetch(urlAccount, {
        method: "GET",

        headers: _this.paaramsApi(),
      })
      .then(function (res) {
        res.json().then(function (data) {
          _this.buyInfoUSDT = data.balances.filter(el => el.asset == "USDT")[0]?.free ? data.balances.filter(el => el.asset == "USDT")[0]?.free : 0;
          _this.buyInfoBTC = data.balances.filter(el => el.asset == "BTC")[0]?.free ? data.balances.filter(el => el.asset == "BTC")[0]?.free : 0;

        });
      })
      .then(function (body) {
      });
    },
    async axiosPrice() {
      let _this = this;
      await axios({
        method: 'get',
        url: "https://api.commex.com/api/v1/ticker/price",
        config: {
          headers: {
            'Accept': 'application/json',
          }
        }
      }).then(function (response) {
        _this.price = response.data.filter(el => el.symbol == 'BTCUSDT');
        let by = 0;
        let sell = 0;
        _this.buy.map(function (el) {
          by += parseFloat(el.origQty)*parseFloat(_this.price[0].price)
        });
        _this.sell.map(function (el) {
          sell += parseFloat(el.origQty)*parseFloat(_this.price[0].price)
        });
        setTimeout(() => {
          _this.count = (parseFloat(_this.buyInfoBTC)*parseFloat(_this.price[0].price) + by + sell + + parseFloat(_this.buyInfoUSDT)).toFixed(2);
          _this.plus = _this.count - _this.toom;
        }, "500");

      });
    },
    async axiosStatistic() {
      let _this = this;
      await axios({
        method: 'get',
        url: "/statistics/all"
      }).then(function (response) {
        _this.toom = response.data[0]?.curs ? response.data[0]?.curs : 0;
      });
    },
    secret() {
      return this.settings[0].secret;
    },
    createdBit(info) {
      let _this = this;
      if(!info) {
        this.stop = !this.stop;
        _this.stopSave();
      }

      if (!this.stop) {
        axios({
          method: 'get',
          url: "/settings/all"
        }).then(function (response) {
          _this.settings = response.data;
          if(_this.stopCount) {
            if (_this.settings[0]?.dis === 1) {
              _this.createdBit(false)
            } else {
              _this.stop = false;
            }
            _this.stopCount = false;
          }
          _this.countMenu++;
          _this.socketWS();
          _this.openOrders();
          _this.account();
          _this.axiosStatistic();
        });

        this.timer = setInterval(() => {
          this.currentTime--
        }, 1000);

        let time = this.currentTime;
        _this.axiosPrice();
        this.timerPrice = setInterval(() => {
            _this.socketWS();
            _this.openOrders();
            _this.account();
            _this.axiosStatistic();
            _this.axiosPrice();

        }, time*1000);

      } else {
        clearTimeout(this.timer)
        clearTimeout(this.timerPrice)
        this.currentTime = 15;
      }

      return this.stop;
    },
    logOut() {
      window.location.href = '/logouts';
    }
  }
}
</script>

<style>
.message {
  height: 100%;
}

.v-badge__badge {
  background-color: #4caf50 !important;
  border-color: #4caf50 !important;
}

.v-toolbar__content, .v-toolbar__extension {
  align-items: center;
  display: table;
  position: relative;
  z-index: 0;
  width: 100%;

}

.calcs {
  right: 0;
  width: 100px;
  z-index: 12;
  position: absolute;
}

.v-app-bar-title__content {
  width: 800px;
}
</style>
<style>
.iconStyle {
  color: white !important;
  margin-top: -3px;
}
.dropdown-menu {
  max-height: 300px;
  overflow-y: scroll;
}
</style>
