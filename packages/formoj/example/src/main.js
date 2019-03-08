import Vue from 'vue'
import App from './App.vue'
import Formoj from 'formoj/src';

Vue.config.productionTip = false;

Vue.use(Formoj, {
    apiBaseUrl: '/api',
    locale:'fr',
});

new Vue({
    render: h => h(App),
}).$mount('#app');
