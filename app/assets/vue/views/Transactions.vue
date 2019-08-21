<template>
    <div>
        <div class="row col">
            <h1>Transactions</h1>
        </div>

        <div class="row col" v-if="canCreateTransaction">
            <el-form autoComplete="off" :model="transactionForm" :rules="transactionRules" ref="transactionForm" label-position="left" label-width="0px"
                     class="card-box transaction-form">
                <div class="form-row">
                    <el-form-item prop="name">
                        <span class="svg-container svg-container_name">
                          <i class="fas fa-user"></i>
                        </span>
                        <el-input name="name" type="text" v-model="transactionForm.customerId" placeholder="Transaction customerId"
                        />
                    </el-form-item>

                    <el-form-item prop="cnp">
                        <span class="svg-container svg-container_cnp">
                          <i class="fas fa-user"></i>
                        </span>
                        <el-input name="cnp" type="text" v-model="transactionForm.amount" placeholder="0.00"
                        />
                    </el-form-item>

                    <el-form-item>
                        <el-button type="primary" style="width:100%;" :loading="loading" :disabled="transactionForm.customerId.length === 0 || isLoading" @click="createTransaction()">
                            Create
                        </el-button>
                    </el-form-item>
                </div>
            </el-form>
        </div>

        <div v-if="isLoading" class="row col">
            <p>Loading...</p>
        </div>

        <div v-else-if="hasError" class="row col">
            <!--error-message :error="error"></error-message-->
            <p>hasError...</p>
        </div>

        <div v-else-if="!hasTransactions" class="row col">
            No transactions!
        </div>

        <div v-else class="row col">
            <div id="v-table" style="min-width: 1000px;margin: auto;">
                <vuetable ref="vuetable"
                          api-url="http://app.localhost/api/transaction"
                          :fields="fields"
                          pagination-path=""
                          @vuetable:pagination-data="onPaginationData"
                >
                    <template slot="actions" scope="props">
                        <div class="table-button-container">
                            <button class="ui button" @click="editRow(props.rowData)"><i class="fa fa-edit"></i> Edit</button>&nbsp;&nbsp;
                            <button class="ui basic red button" @click="deleteRow(props.rowData)"><i class="fa fa-remove"></i> Delete</button>&nbsp;&nbsp;
                        </div>
                    </template>
                </vuetable>
                <!--:query-params="{ sort_order: 'sort_order', offset: '(page_no -1) * page_size', limit: 'page_size' }"-->
                <vuetable-pagination ref="pagination"
                     @vuetable-pagination:change-page="onChangePage"
                ></vuetable-pagination>
            </div>

            <!--  v-for="t in transactions"
            <transaction :id="t.id" :name="t.customerId" :amount="t.amount" :created="t.created"></transaction-->
        </div>
    </div>
</template>

<script>
    import Transaction from '../components/Transaction';

    import Vuetable from './vuetable-custom'
    import VuetablePagination from './vuetable-custom/components/VuetablePagination'

//    import ErrorMessage from '../components/ErrorMessage';

    export default {
        name: 'transactions',
        components: {
            Transaction,
            Vuetable,
            VuetablePagination,
//            ErrorMessage,
        },
        data () {
            const validateTransactionCustomerId = (rule, value, callback) => {
                if (value.length < 1) {
                    callback(new Error('Transaction CustomerId is required'))
                } else {
                    callback()
                }
            };
            const validateTransactionAmount = (rule, value, callback) => {
                if (value.length < 1) {
                    callback(new Error('Transaction amount is required'))
                } else {
                    callback()
                }
            };

            return {
                transactionForm: {
                    customerId: '',
                    amount: '0.00'
                },
                transactionRules: {
                    customerId: [{ required: true, trigger: 'blur', validator: validateTransactionCustomerId }],
                    amount: [{ required: true, trigger: 'blur', validator: validateTransactionAmount }]
                },
                loading: false,

                fields: ['id', 'customerId', 'amount', 'created', '__slot:actions'],
            };
        },
        created () {
            this.$store.dispatch('transaction/fetchTransactions');
        },
        computed: {
            isLoading () {
                return this.$store.getters['transaction/isLoading'];
            },
            hasError () {
                return this.$store.getters['transaction/hasError'];
            },
            error () {
                return this.$store.getters['transaction/error'];
            },
            hasTransactions () {
                return this.$store.getters['transaction/hasTransactions'];
            },
            transactions () {
                return this.$store.getters['transaction/transactions'];
            },
            canCreateTransaction () {
                return this.$store.getters['security/hasRole']('ROLE_ADMIN');
            }
        },
        mounted() {

        },
        methods: {
            createTransaction () {
                this.$store.dispatch('transaction/createTransaction', this.$data.transactionForm)
                    .then(() => this.$data.transactionForm = { customerId: '', amount: '0.00' })
            },
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
            },
            onChangePage (page) {
                this.$refs.vuetable.changePage(page)
            },
            editRow(rowData){
                alert("You clicked edit on"+ JSON.stringify(rowData))
            },
            deleteRow(rowData){
                alert("You clicked delete on"+ JSON.stringify(rowData))
            }
        },
    }
</script>

<style>
    #v-table {
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
        margin-top: 60px !important;
        margin-left: 10px !important;
    }
</style>
