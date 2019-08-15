import TransactionAPI from '../api/transaction';

export default {
    namespaced: true,
    state: {
        isLoading: false,
        error: null,
        transactions: [],
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
        hasTransactions (state) {
            return state.transactions.length > 0;
        },
        transactions (state) {
            return state.transactions;
        },
    },
    mutations: {
        ['CREATING_TRANSACTION'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['CREATING_TRANSACTION_SUCCESS'](state, post) {
            state.isLoading = false;
            state.error = null;
            state.transactions.unshift(post);
        },
        ['CREATING_TRANSACTION_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.transactions = [];
        },
        ['FETCHING_TRANSACTIONS'](state) {
            state.isLoading = true;
            state.error = null;
            state.transactions = [];
        },
        ['FETCHING_TRANSACTIONS_SUCCESS'](state, transactions) {
            state.isLoading = false;
            state.error = null;
            state.transactions = transactions;
        },
        ['FETCHING_TRANSACTIONS_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.transactions = [];
        },
    },
    actions: {
        createTransaction ({commit}, message) {
            commit('CREATING_TRANSACTION');
            return TransactionAPI.create(message)
                .then(res => commit('CREATING_TRANSACTION_SUCCESS', res.data))
                .catch(err => commit('CREATING_TRANSACTION_ERROR', err));
        },
        fetchTransactions ({commit}) {
            commit('FETCHING_TRANSACTIONS');
            return TransactionAPI.getAll()
                .then(res => commit('FETCHING_TRANSACTIONS_SUCCESS', res.data))
                .catch(err => commit('FETCHING_TRANSACTIONS_ERROR', err));
        },
    },
}
