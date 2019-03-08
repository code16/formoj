import Vue from 'vue'
import App from './App.vue'
import Formoj from 'formoj/src';

Vue.config.productionTip = false;

Vue.use(Formoj, {
    apiBaseUrl: '/api',
    locale: 'en',
    i18n: {
        en: {
            'section.button.next': 'Next section',
        }
    }
});

new Vue({
    render: h => h(App),
}).$mount('#app');
