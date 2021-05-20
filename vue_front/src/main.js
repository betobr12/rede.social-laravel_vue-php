// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import axios from 'axios' // usado para trazer os dados da api, foi colocado aqui afim de criar uma constante e utiliza -lo em todas as paginas
import Vuex from 'vuex'

Vue.use(Vuex)
Vue.config.productionTip = false
Vue.prototype.$http = axios
Vue.prototype.$urlAPI = 'http://127.0.0.1:8000/api/'

var store = { //vuex
  state: {
    user: sessionStorage.getItem('user') ? this.user = JSON.parse(sessionStorage.getItem('user')) : null
  },
  getters: { //receber listas e valores

    getUsuario: state => {
      return state.user;
    },
    getToken: state => {
      return state.user.token;
    }
  },
  mutations: { //efetuar moodificações
    setUsuario(state,n) {
      state.user = n;
    }

  }

}

/* eslint-disable no-new */
new Vue({
  el: '#app',
  store: new Vuex.Store(store),
  router,
  components: { App },
  template: '<App/>'
})
