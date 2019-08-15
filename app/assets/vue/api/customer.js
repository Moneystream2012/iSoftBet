import axios from 'axios';

export default {
    create (customerData) {
        return axios.post(
            '/api/customer/create',
            customerData
        );
    },
    getAll () {
        return axios.get('/api/customers');
    },
}
