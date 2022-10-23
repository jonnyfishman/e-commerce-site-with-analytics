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

        <div class="sort-group" >

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
        <section v-for="n in $store.getters.numberOfProducts" :key="'blank_'+n">
          <div class="product-wrapper">
            <svg width="100%" height="100%" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
              <rect width="400" height="400" rx="10" ry="10"/>
            </svg>
            <h2>&nbsp;</h2>
            <h4>&nbsp;</h4>
            <p>&nbsp;</p>
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

    }
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

  h2, h4, p {
    font-weight:normal;
    margin:0;

  }
  .product-wrapper svg {
    width:100%;
    object-fit: cover;
    fill:rgb(204, 204, 204, 0.5);
  }
  .product-wrapper h2, .product-wrapper h4, .product-wrapper p {
    width:76%;
    background: rgb(204, 204, 204, 0.5);
    border-radius: 5px;
    font-size: 80%;
    margin: .25rem 0;
  }
  .product-wrapper h4 {
    width:24%;
  }
  .product-wrapper p {
    width:51%;
  }
</style>
