<template>
  <!-- <nav-bar /> -->
  <main>
    <aside>
      <h2>Filters</h2>
      <button v-if="this.filter.length > 0" class="btn" @click.prevent="filterProducts([])">
        Clear {{this.filter.length}} Filters
        <font-awesome-icon class="close" icon="fa-solid fa-xmark" />
      </button>
      <div v-for="category in categories" :key="category.name">

        <p-filter :label="category.name" :options="category.values" :filter="this.filter.length" @triggered="filterProducts" />
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
      <section v-for="product in products" :key="product.id+product.name">
        <p-section :product="product" :key="product.id" />
      </section>
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
import { isEqual } from 'lodash';

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
      categories: [],
      filter: []
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

      if ( this.filter.length > 0 ) {
          query = '?ids=' + this.filter.join(',')
      }

      axios
      .get('/api/products'+query) // should post in ids and get response default should be recommended products
      .then(response => {

            this.products = response.data.data

      })
    },
    loadCategories(query = '') {  console.log(query)
      axios
      .get('/api/categories'+query)
      .then(response => {

            this.categories = response.data.data

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
        this.filter = ids
        this.loadCategories(query)
        this.loadProducts()
        this.filter = ['']
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
        console.log('clear')
        this.loadCategories()
this.loadProducts()
        return
      }

      this.filter.push(ids)   // this should be an axios call to get new ids
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
  computed: {


  },
  mounted() {


    this.loadProducts();

    this.loadCategories();

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
</style>
