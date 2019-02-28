import Vue from 'vue'
import App from './App.vue'
import VueFormoj from 'vue-formoj/src';

Vue.config.productionTip = false;

Vue.use(VueFormoj, { apiBaseUrl: '/api' });

new Vue({
    render: h => h(App),
}).$mount('#app');
