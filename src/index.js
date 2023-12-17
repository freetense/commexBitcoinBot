import Vue from 'vue';
import Auth from "./components/Auth.vue";
import Index from "./components/Index.vue";
import vuetify from "./plugins/vuetify";
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css';
import Vue2Editor from "vue2-editor";
import VueCryptojs from 'vue-cryptojs'
Vue.use(VueCryptojs)
Vue.use(Vue2Editor);
import 'bootstrap-vue/dist/bootstrap-vue.css';
Vue.config.productionTip = false;
Vue.use(BootstrapVue);

Vue.use(IconsPlugin);
new Vue({
    vuetify,
    render: h => h(Auth),
}).$mount('#vue');
new Vue({
    vuetify,
    render: h => h(Index),
}).$mount('#index');