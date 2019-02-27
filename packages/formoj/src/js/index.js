import Form from './components/Form.vue';
import VueI18n from 'vue-i18n';

export default {
    install(Vue) {
        Vue.use(VueI18n);
        Vue.component('fj-form', Form);
    }
}

export {
    Form
}