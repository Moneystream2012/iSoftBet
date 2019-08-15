import CustomerAPI from '../api/customer';

export default {
    namespaced: true,
    state: {
        isLoading: false,
        error: null,
        customers: [],
    },
    getters: {
        isLoading (state) {
            return state.isLoading;
        },
        hasError (state) {
            return state.error !== null;
        },
        error (state) {
            return state.error;
        },
        hasCustomers (state) {
            return state.customers.length > 0;
        },
        customers (state) {
            return state.customers;
        },
    },
    mutations: {
        ['CREATING_CUSTOMER'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['CREATING_CUSTOMER_SUCCESS'](state, post) {
            state.isLoading = false;
            state.error = null;
            state.customers.unshift(post);
        },
        ['CREATING_CUSTOMER_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.customers = [];
        },
        ['FETCHING_CUSTOMERS'](state) {
            state.isLoading = true;
            state.error = null;
            state.customers = [];
        },
        ['FETCHING_CUSTOMERS_SUCCESS'](state, customers) {
            state.isLoading = false;
            state.error = null;
            state.customers = customers;
        },
        ['FETCHING_CUSTOMERS_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.customers = [];
        },
    },
    actions: {
        createCustomer ({commit}, customerData) {
            commit('CREATING_CUSTOMER');
            return CustomerAPI.create(customerData)
                .then(res => commit('CREATING_CUSTOMER_SUCCESS', {id: res.data, name: customerData.name, cnp: customerData.cnp}))
                .catch(err => commit('CREATING_CUSTOMER_ERROR', err));
        },
        fetchCustomers ({commit}) {
            commit('FETCHING_CUSTOMERS');
            return CustomerAPI.getAll()
                .then(res => commit('FETCHING_CUSTOMERS_SUCCESS', res.data))
                .catch(err => commit('FETCHING_CUSTOMERS_ERROR', err));
        },
    },
}
