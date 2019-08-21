import axios from 'axios';

export default {
    create (data) {
        return axios.post('/api/transaction', data);
    },
    update (data) {
        return axios.put('/api/transaction/' + data.id, data);
    },
    delete (data) {
        return axios.delete('/api/transaction/' + data.id);
    },
    get (id) {
        return axios.get('/api/transaction/' + id);
    },
    getAll () {
        return axios.get('/api/transaction');
    },
}
