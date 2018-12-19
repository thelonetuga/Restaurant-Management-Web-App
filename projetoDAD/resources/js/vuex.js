import Vue from 'vue'
import Vuex from 'vuex'
import VuexPersist from 'vuex-persist';

Vue.use(Vuex);

const vuexLocalStorage = new VuexPersist({
    key: 'vuex',
    storage: window.localStorage
});


const store = new Vuex.Store({
    state: {
        token: "",
        user: null,
        items: [],
        ismanager: false,
        iscook: false,
        iswaiter: false,
        iscashier: false,
    },
    mutations: {
        clearUserAndToken: (state) => {
            state.user = null;
            state.token = "";
            window.localStorage.removeItem('user');
            window.localStorage.removeItem('token');
            axios.defaults.headers.common.Authorization = undefined;
            state.ismanager = false;
            state.iswaiter = false;
            state.iscook = false;
            state.iscashier = false;

        },
        check: (state) => {
            return !!state.token;
        },
        clearUser: (state) => {
            state.user = null;
            window.localStorage.removeItem('user');
            state.ismanager = false;
            state.iscook = false;
            state.iswaiter = false;
            state.iscashier = false;
        },
        clearToken: (state) => {
            state.token = "";
            window.localStorage.removeItem('token');
            axios.defaults.headers.common.Authorization = undefined;
        },
        setUser: (state, user) => {
            state.user = user;
            window.localStorage.setItem('user', JSON.stringify(user));
            if (state.user.type === 'manager') {
                state.ismanager = true;
            } else if (state.user.type === 'cook') {
                state.iscook = true;
            } else if (state.user.type === 'waiter') {
                state.iswaiter = true;
            } else if (state.user.type === 'cashier') {
                state.iscashier = true;
            }
        },
        setToken: (state, token) => {
            state.token = token;
            window.localStorage.setItem('token', token);
            axios.defaults.headers.common.Authorization = "Bearer " + token;
        },
        loadTokenAndUserFromSession: (state) => {
            state.token = "";
            state.user = null;
            let token = window.localStorage.getItem('token');
            let user = window.localStorage.getItem('user');
            if (token) {
                state.token = token;
                axios.defaults.headers.common.Authorization = "Bearer " + token;
            }
            if (user) {
                state.user = JSON.parse(user);
            }
        },
        getItems: (state) => {
            axios.get('api/items')
                .then(response => {
                    state.items = response.data.data;
                });
        },
        getUser: (state) => {
            axios.get("/api/users/me").then(response => {
                state.user = response.data.data;
            }).catch(response => {
                if (response.status === 401) {
                    this.logout();
                }
            });
        },
        setManager: (state) => {
            state.ismanager = false;
        },
        setCook: (state) => {
            state.iscook = false;
        },
        setWaiter: (state) => {
            state.iswaiter = false;
        },
        setCashier: (state) => {
            state.iscashier = false;
        }
    },
    getters: {
        getUserAuth: state => state.user,
    },
    plugins: [vuexLocalStorage.plugin]
});

export default store;
