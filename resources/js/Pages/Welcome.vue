<template>
  <!-- <nav-bar /> -->
  <main>
    <aside>
      <h2>Filters</h2>
      <p-filter :id="getRamdomInt()" label="Fit" :options="['Narrow', 'Wide']" />
      <p-filter :id="getRamdomInt()" label="Price" :options="['&lt; £100', '&lt; £200', '&lt; £300']" />
      <p-filter :id="getRamdomInt()" label="Drop" :options="['6mm', '8mm', '12mm']" />
    </aside>
    <article>
      <header>
        <h2>Mens Trail Running Shoes</h2>
        <div class="sort-group">
          <span>Brand
              <font-awesome-icon @click.prevent="sortShoes('brand&desc')" icon="fa-solid fa-chevron-up" />
              <font-awesome-icon @click.prevent="sortShoes('brand')" icon="fa-solid fa-chevron-down" />
          </span>
          <span>Name
            <font-awesome-icon @click.prevent="sortShoes('name&desc')" icon="fa-solid fa-chevron-up" />
            <font-awesome-icon @click.prevent="sortShoes('name')" icon="fa-solid fa-chevron-down" />
          </span>
          <span>Price
            <font-awesome-icon @click.prevent="sortShoes('price')" icon="fa-solid fa-chevron-up" />
            <font-awesome-icon @click.prevent="sortShoes('price&desc')" icon="fa-solid fa-chevron-down" />
          </span>
          <span>Colour
            <font-awesome-icon @click.prevent="sortShoes('colour_index')" icon="fa-solid fa-chevron-up" />
            <font-awesome-icon @click.prevent="sortShoes('colour_index&desc')" icon="fa-solid fa-chevron-down" />
          </span>
        </div>
      </header>
      <section v-for="shoe in shoes" :key="shoe.id">
        <p-section :shoe="shoe" />
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

export default {
  data () {
    return {
      shoes: [],

    }
  },
  components: {
    Head, Link,
    NavBar, pFilter, pSection
  },
  methods: {
    getRamdomInt () {
      return Math.floor(Math.random() * (999 - 111 + 1) + 111)
    },
    sortShoes(searchTerm) {
      axios
      .get('/api/products?sortBy=' + searchTerm)
      .then(response => {
        if ( response.data.length == 1 ) {    // get rid of this by edting the resource controller
          this.shoes = [ response.data ]
        }
        else {
            this.shoes = response.data
        }

      })
    }
  },
  mounted() {
    axios
    .get('/api/products')
    .then(response => {
      if ( response.data.length == 1 ) {
        this.shoes = [ response.data ]
      }
      else {
          this.shoes = response.data
      }

    })
  }
}
</script>

<style scoped>
  .sort-group {
    margin-bottom:0.6em;
  }
  .sort-group span:hover {
    color:#666;
  }
  .sort-group span {
    padding-right:0.2em;
    border-right:1px solid #ccc;
    margin-right:0.4em;
  }
  .sort-group span:last-child {
    border-right:none;
  }
</style>
