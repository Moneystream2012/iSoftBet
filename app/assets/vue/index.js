import Vue from 'vue';
import ElementUI from 'element-ui'
import locale from 'element-ui/lib/locale/lang/en'
import 'element-ui/lib/theme-chalk/index.css'
import 'babel-polyfill'

import './styles/index.scss' // global css
import App from './App';
import router from './router';
import store from './store';

Vue.use(ElementUI, { locale });

new Vue({
    template: '<App/>',
    components: { App },
    router,
    store,
}).$mount('#app');
