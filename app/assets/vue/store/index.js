import Vue from 'vue';
import Vuex from 'vuex';
import SecurityModule from './security';
import CustomerModule from './customer';
import TransactionModule from './transaction';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        security: SecurityModule,
        customer: CustomerModule,
        transaction: TransactionModule,
    },
});
