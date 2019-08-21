<template>
    <div>
        <div class="row col">
            <h1>Customers</h1>
        </div>

        <div class="row col" v-if="canCreateCustomer">
            <el-form autoComplete="off" :model="customerForm" :rules="customerRules" ref="customerForm" label-position="left" label-width="0px"
                     class="card-box customer-form">
                <div class="form-row">
                    <el-form-item prop="name">
                        <span class="svg-container svg-container_name">
                          <i class="fas fa-user"></i>
                        </span>
                        <el-input name="name" type="text" v-model="customerForm.name" placeholder="Customer name"
                        />
                    </el-form-item>

                    <el-form-item prop="cnp">
                        <span class="svg-container svg-container_cnp">
                          <i class="fas fa-user"></i>
                        </span>
                        <el-input name="cnp" type="text" v-model="customerForm.cnp" placeholder="0.00"
                        />
                    </el-form-item>

                    <el-form-item>
                        <el-button type="primary" style="width:100%;" :loading="loading" :disabled="customerForm.name.length === 0 || isLoading" @click="createCustomer()">
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
            <error-message :error="error"></error-message>
        </div>

        <div v-else-if="!hasCustomers" class="row col">
            No customers!
        </div>

        <div v-else v-for="c in customers" class="row col">
            <customer :id="c.id" :name="c.name" :cnp="c.cnp"></customer>
        </div>
    </div>
</template>

<script>
    import Customer from '../components/Customer';
    import ErrorMessage from '../components/ErrorMessage';

    export default {
        name: 'customers',
        components: {
            Customer,
            ErrorMessage,
        },
        data () {
            const validateCustomerName = (rule, value, callback) => {
                if (value.length < 1) {
                    callback(new Error('Customer name is required'))
                } else {
                    callback()
                }
            };

            return {
                customerForm: {
                    name: '',
                    cnp: '0.00'
                },
                customerRules: {
                    name: [{ required: true, trigger: 'blur', validator: validateCustomerName }]
                },
                loading: false
            }
        },
        created () {
            this.$store.dispatch('customer/fetchCustomers');
        },
        computed: {
            isLoading () {
                return this.$store.getters['customer/isLoading'];
            },
            hasError () {
                return this.$store.getters['customer/hasError'];
            },
            error () {
                return this.$store.getters['customer/error'];
            },
            hasCustomers () {
                return this.$store.getters['customer/hasCustomers'];
            },
            customers () {
                return this.$store.getters['customer/customers'];
            },
            canCreateCustomer () {
                return this.$store.getters['security/hasRole']('ROLE_ADMIN');
            }
        },
        methods: {
            createCustomer () {
                this.$store.dispatch('customer/createCustomer', this.$data.customerForm)
                    .then(() => this.$data.customerForm = { name: '', cnp: '0.00' })
            },
        },
    }
</script>
