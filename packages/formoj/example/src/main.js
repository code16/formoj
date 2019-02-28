import Vue from 'vue'
import App from './App.vue'
import VueFormoj from 'formoj/js';

Vue.config.productionTip = false;

Vue.use(VueFormoj);

new Vue({
  render: h => h(App),
}).$mount('#app');
