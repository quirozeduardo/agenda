import Vue from 'vue';
import App from './App.vue';
import store from './store/store';
import vuetify from 'vuetify';
import axios from 'axios';
import VueAxios from 'vue-axios';
import router from './router';

Vue.config.productionTip = false;

Vue.use(VueAxios, axios.create({
  baseURL: 'dataScript/'
}));
Vue.use(vuetify, {
  iconfont: 'fa',
});
new Vue({
  store,
  router,
  render: (h) => h(App)
}).$mount('#app');
