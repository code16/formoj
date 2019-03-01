import Formoj from './components/Formoj.vue';
import Form from './components/Form.vue';
import {createConfig} from "./util/config";

export default {
    install(Vue, config={}) {
        Vue.component('formoj', Formoj);
        Vue.component('fj-form', Form);
        Vue.prototype.$formoj = createConfig(config);
    }
}

export {
    Formoj,
    Form,
}