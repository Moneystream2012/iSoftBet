import axios from 'axios';

export default {
    create (message) {
        return axios.post(
            '/api/transaction/create',
            {
                message: message,
            }
        );
    },
    getAll () {
        return axios.get('/api/transaction');
    },
}
