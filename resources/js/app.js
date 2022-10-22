import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createStore } from 'vuex'
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m.js';
import FontAwesome from '@/Plugins/FontAwesome'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const store = createStore({
  state () {
    return {
      filters: [],
      categories: [],
      productIds: []
    }
  },
  getters: {
    numberOfFilters (state) {
      return state.filters.length
    },
    numberOfProducts (state) {
      return state.productIds.length
    },
    productIds (state) {
      return state.productIds
    }
  },
  mutations: {
    addFilter (state, payload) {
      console.log(payload.name,payload.value)
      
      state.filters = [...state.filters].filter(values => {
        return ( payload.name === 'range' && values.name === 'range' && payload.value.split(',')[0] === values.value.split(',')[0]) ? false : true
      })
      state.filters.push({'name':payload.name, 'value':payload.value})

    },
    removeFilter (state, payload) {

      state.filters = [...state.filters].filter(values => {
        return (values.name === payload.name && values.value === payload.value) ? false : true
      })

    },
    clearFilter (state) {
      state.filters = []
      state.productIds = []
    },
    updateCategories (state, payload) {
      state.categories = payload
    },
    updateProductIds (state, payload) {
      state.productIds = payload
    }
  },
  actions: {
    async updateCategories({commit}, payload={}) {

      const categories = await axios.get('/api/categories?'+payload.query)
                                          .then(response => response.data.data)
      commit('updateCategories', categories)

      // commit('updateFilter')  change this to a new route to get ids? Move category details into filter to be used
    },
    async updateProductIds({commit}, payload={}) {

      let query = []

      payload.forEach(filter=>{
        if ( !query[filter.name] ) {
          query[filter.name] = filter.name + '=' + filter.value.replace('#','')
        } else {
          query[filter.name] += ',' + filter.value.replace('#','')
        }
        //return filter.name + '=' + filter.value.replace('#','')
      })
//console.log('query',Object.values(query).join('&'))
      const productIds = await axios.get('/api/categories/id?'+Object.values(query).join('&'))
                                          .then(response => response.data.data)
                                          //console.log(productIds)
      commit('updateProductIds', productIds)

      // commit('updateFilter')  change this to a new route to get ids? Move category details into filter to be used
    },
  }



})

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(FontAwesome)
            .use(store)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });

// Add error catch in axios callbacks
// problem that active filters remain when category redraws (change object from 0:{name,value} to name:{value} ALSO change prop from [] to {})
// can updatecategories use the same values as addgilter?
