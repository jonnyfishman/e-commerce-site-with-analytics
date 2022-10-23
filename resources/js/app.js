import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createStore } from 'vuex'
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m.js';
import FontAwesome from '@/Plugins/FontAwesome'
import { intersection } from 'lodash';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const store = createStore({
  state () {
    return {
      filters: {},
      categories: [],
      productIds: []
    }
  },
  getters: {
    numberOfFilters (state) {
      return Object.values(state.filters).flatMap(item=>{
        return (item[0] === 'range') ? item[0] : item
      }).length
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
      if ( Array.isArray(payload.value) ) {
        state.filters[payload.name] = payload.value
      }
      else if ( !state.filters[payload.name] ) {
       state.filters[payload.name] = [payload.value]
      }
      else {
        /*
        state.filters[payload.value] = Object.values(state.filters['range']).filter(filter=> {

          return (!filter.includes(payload.value[0]))
        });
        */
        state.filters[payload.name].push(payload.value)
      }

      // state.filters.push({'name':payload.name, 'value':payload.value})

    },
    removeFilter (state, payload) {

      const index = state.filters[payload.name].indexOf(payload.value)
      if (index > -1) {
        state.filters[payload.name].splice(index, 1)
      }
      if ( state.filters[payload.name].length === 0) delete state.filters[payload.name]


    },
    clearFilter (state) {
      state.filters = {}
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
    async updateCategories({commit, state}) {

      let query = []

      Object.entries(state.filters).forEach(([name, values]) => { // GET RID OF Object.entries
          const category = state.categories[state.categories.findIndex(f => f.name === name)]

          if ( values[0] === 'range') {
            query.push(name + '=' + encodeURIComponent( values.join(',') ) )
          }
      })
      console.log('updateCategories',query)
      const categories = await axios.get('/api/categories?'+query.join('&'))
                                          .then(response => response.data.data)
      commit('updateCategories', categories)

    },
    async updateProductIds({commit, state}) {

      let query = []


      Object.entries(state.filters).forEach(([name, values]) => { // GET RID OF Object.entries
          const category = state.categories[state.categories.findIndex(f => f.name === name)]

          if ( values[0] === 'range') {
            query.unshift(name + '=' + encodeURIComponent( values.join(',') ) )
          } else {

              query.push(name + '=' + encodeURIComponent( intersection(Object.keys(category.values), values).join(',') ) )
          }


      })
console.log('updateProductIds',Object.values(query).join('&'))
      const productIds = await axios.get('/api/categories/id?'+query.join('&'))
                                          .then(response => response.data.data)

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

// loading section as component
// change filter back to array not Object
// validate in categorycontroller
