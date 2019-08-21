import axios from 'axios';

export default {
    create (data) {
        return axios.post('/api/customer', data);
    },
    getAll () {
        return axios.get('/api/customer');
    },
}
