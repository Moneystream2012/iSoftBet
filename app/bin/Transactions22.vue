<template>
    <div>
        <div class="row col">
            <h1>Transactions</h1>
        </div>

        <div class="row col" v-if="canCreateTransaction">
            <form>
                <div class="form-row">
                    <div class="col-8">
                        <input v-model="message" type="text" class="form-control">
                    </div>
                    <div class="col-4">
                        <button @click="createTransaction()" :disabled="message.length === 0 || isLoading" type="button" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>

        <div v-if="isLoading" class="row col">
            <p>Loading...</p>
        </div>

        <div v-else-if="hasError" class="row col">
            <error-message :error="error"></error-message>
        </div>

        <div v-else-if="!hasTransactions" class="row col">
            No transactions!
        </div>

        <div v-else v-for="transaction in transactions" class="row col">
            <!--data-table
                    url="http://vue-datatable.test/ajax"
                    :per-page="perPage"
                    :columns="columns">
            </data-table-->
            <!--
            <transaction :transactionId="transaction.transactionId"></transaction-->
        </div>

        <!--v-paginator resource_url="api/transaction" @update="updateResource"></v-paginator-->
    </div>
</template>

<script>
    import Transaction from '../assets/vue/components/Transaction';
    import ErrorMessage from '../assets/vue/components/ErrorMessage';

    export default {
        name: 'transactions',
        components: {
            Transaction,
            ErrorMessage
        },
        data () {
            return {
                perPage: ['5', '10', '50'],
                columns: [
                    {
                        label: 'ID',
                        name: 'id',
                        filterable: true,
                    },
                    {
                        label: 'Name',
                        name: 'name',
                        filterable: true,
                    },
                    {
                        label: 'Email',
                        name: 'email',
                        filterable: true,
                    },
                    {
                        label: '',
                        name: 'View',
                        filterable: false,
                    },
                ],
                message: '',
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
        methods: {
            createTransaction () {
                this.$store.dispatch('transaction/createTransaction', this.$data.message)
                    .then(() => this.$data.message = '')
            }
        },
    }
</script>
