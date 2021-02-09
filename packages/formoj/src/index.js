import './polyfill';
import Formoj from './components/Formoj.vue';
import Answer from './components/Answer';
import Form from './components/Form.vue';
import { createConfig } from "./util/config";
import { versionNumber } from "./util/version";

export default {
    install(Vue, config={}) {
        Vue.component('formoj', Formoj);
        Vue.component('formoj-answer', Answer);
        Vue.component('fj-form', Form);
        // use Vue.observable 2.6 feature
        if(versionNumber(Vue.version) >= 2.6) {
            Vue.prototype.$formoj = Vue.observable(createConfig(config));
        } else {
            Vue.util.defineReactive(Vue.prototype, '$formoj', createConfig(config));
        }
    }
}

export {
    Formoj,
    Form,
}
