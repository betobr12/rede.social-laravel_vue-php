
import Vue from 'vue'
import App from './App'
import router from './router'
import axios from 'axios'
import Vuex from 'vuex'
import slug from 'slug'

Vue.use(Vuex)
Vue.config.productionTip = false
Vue.prototype.$http = axios
Vue.prototype.$urlAPI = 'http://127.0.0.1:8000/api/'
Vue.prototype.$slug = slug

var store = { 
  state: {
    user: sessionStorage.getItem('user') ? this.user = JSON.parse(sessionStorage.getItem('user')) : null,
    contentsTimeLine: null,
  },
  getters: { 

    getUsuario: state => {
      return state.user;
    },
    getToken: state => {
      return state.user.token;
    },
    getContentsTimeLine: state => {
      return state.contentsTimeLine;
    }
  },
  mutations: { 
    setUsuario(state,n) {
      state.user = n;
    },
    setContentsTimeLine(state,n) {
      state.contentsTimeLine = n;
    },
    setCountLike(state,total_like) {
      state.contentsTimeLine = total_like;
    },
    setPaginationContentsTimeLine(state,list) {
      for (let item of list) {
        state.contentsTimeLine.push(item)
      }
    },
  }
};

Vue.directive('scroll', {
  inserted: function (el, binding) {
    let f = function (evt) {
      if (binding.value(evt, el)) {
        window.removeEventListener('scroll', f)
      }
    }
    window.addEventListener('scroll', f)
  }
})

new Vue({
  el: '#app',
  store: new Vuex.Store(store),
  router,
  components: { App },
  template: '<App/>'
})
