<template>
  <!-- <nav-bar /> -->
  <main>
    <aside>
      <h2>Filters</h2>
      <button v-if="this.$store.getters.numberOfFilters > 0" class="btn" @click.prevent="this.$store.commit('clearFilter');this.$store.dispatch('updateCategories')">
        Clear {{this.$store.getters.numberOfFilters}} Filters
        <font-awesome-icon class="close" icon="fa-solid fa-xmark" />
      </button>
      <div v-for="category in this.$store.state.categories" :key="category.name">

        <p-filter :label="category.name" :values="category.values" />
      </div>

    </aside>
    <article>
      <header>
        <h2>Mens Trail Running Shoes</h2>

        <div v-if="products[0]" class="sort-group" >

          <p-sort v-for="sortable in sortables" :key="sortable.column" :sortable="sortable" @triggered="sortProducts">
            {{ sortable.name }}
          </p-sort>
        </div>
      </header>
      <template v-if="products[0]" >
        <section v-for="product in products" :key="product.id+product.name">
          <p-section :product="product" :key="product.id" />
        </section>
      </template>
      <template v-else>
        <section v-for="n in 10" :key="'blank_'+n">
          <div class="product-wrapper">
            <img src="https://via.placeholder.com/200"/>
            <h2></h2>
            <h4></h4>
            <p></p>
          </div>
        </section>
      </template>
    </article>
  </main>
  <footer>
    <p>copyright notice &copy;</p>
  </footer>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import NavBar from '@/Components/NavBar.vue'
import pFilter from '@/Components/pFilter.vue'
import pSection from '@/Components/pSection.vue'
import pSort from '@/Components/pSort.vue'
import { isEqual, intersection } from 'lodash';

export default {
  data () {
    return {
      products: [],
      sortables: [ // Get this from axios
          {
            name: 'Name',
            column: "name"
          },
          {
            name: 'Brand',
            column: "brand"
          },
          {
            name: 'Price',
            column: "price"
          },
          {
            name: 'Colour',
            column: "colour"
          },

      ],
      categories: []
    }
  },
  components: {
    Head, Link,
    NavBar, pFilter, pSection, pSort
  },
  methods: {
    loadProducts() {
      this.products = []
      let query = ''


          query = '?ids=' + this.$store.state.productIds.join(',')


      axios
      .get('/api/products'+query) // should post in ids and get response default should be recommended products
      .then(response => {

            this.products = response.data.data

      })
    },
    sortProducts(name, desc) {

      this.products.sort((a,b) => {

  			let fa, fb
        fa = ( typeof a[name] === 'string' ) ? a[name].toLowerCase() : a[name]
        fb = ( typeof b[name] === 'string' ) ? b[name].toLowerCase() : b[name]

  			if (fa < fb) {
  				return desc ? -1 : 1
  			}
  			if (fa > fb) {
  				return desc ? 1 : -1
  			}
  			return 0
  		})

    },
    filterProducts(ids, del = false, query = false) {

      if ( query ) {    // query needs to be additive for multiple range components
        /*
        this.filter.forEach((filteredIDs, index, theArray) => {
          theArray[index] = intersection(filteredIDs.flatMap(id => id), ids.flatMap(id => id))
        });
        (filteredIDs => {
          intersection(filteredIDs, ids)
        })
        console.log(this.filter)
        */
        //this.filter = ids
        this.loadCategories(query)
        this.loadProducts()

        //this.filter = ['c']

        return
      }

      if ( del == true) {

        // this.filter = difference(this.filter, ids)


        this.filter = this.filter.filter(id => {

          return !isEqual(id, ids)
        })

        this.loadProducts()

        return
      } else if ( ids.length === 0 ) {
        this.filter = []

        this.$store.dispatch('updateCategories')
        this.loadProducts()
        return
      }

      this.filter.push(ids)   // this should be an axios call to get new ids
      this.$store.commit('addFilter', {'filter':'first', 'ids':[1,2,3]})
      this.loadProducts()
      /*
      this.filter =  this.filter.length === 0 ?
                            this.filter = ids :
                            intersection(ids.flatMap(id => id), this.filter.flatMap(id => id))
                            */
      /*
      this.categories.filter(p => {
        return this.filter.flatMap(id => id).some(id => id === p['id'])
      })
      */
    },

  },
  watch: {
    '$store.getters.productIds': function() {
      this.loadProducts()

    }

  },
  mounted() {

//    this.$store.commit('addFilter', {'filter':'first', 'ids':[1,2,3]})


    this.$store.dispatch('updateCategories')

    this.loadProducts()
  }
}
</script>

<style scoped>
  .sort-group {
    margin-bottom:0.6em;
  }
  .sort-group span:last-child {
    border-right:none;
  }
  .btn {
    border:none;
    border-bottom:1px solid #999;
    padding:0.4em;
    width:100%;
    text-align:left;
    background:none;
    font-size:100%;
    font-family:inherit;

    position:relative;
    font-size:100%;
    display: inline-flex;
    align-items: center;
  }
  .close {

    position:absolute;
    right:0.6em;
  }
  .btn:hover {
    background:#E2EFDE;
  }

  .product-wrapper {
    background: #fff;
    height: 100%;
    display: inline-flex;
    flex-direction: column;
    justify-content: flex-end;
    width:100%;
  }
  .product-wrapper img {
    width:100%;
    object-fit: cover;
  }
  h2, h4, p {
    font-weight:normal;
    margin:0;

  }
</style>
