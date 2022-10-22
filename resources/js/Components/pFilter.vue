<template>
  <fieldset>
    <input class="toggle" type="checkbox" :id="id"/>
    <label :for="id">{{ label }}</label><!-- add fa icon-->
        <ul v-if="!isNumber" class="sub_category">

          <li :class="{active: active[value] }" v-for="(value, index) in Object.keys(values)" :key="value" @click="triggered(label, value)">
            <template v-if="isHex"><span class="colour_dot" :style="{ 'background-color': `${value}`}"></span>({{ Object.values(values)[index].length }})</template>
            <template v-else>{{ value }} ({{ Object.values(values)[index].length }})</template>
            <font-awesome-icon v-if="!active[value]" class="circle" icon="fa-regular fa-circle" />
            <font-awesome-icon v-if="active[value]" class="check" icon="fa-regular fa-circle-check" />

          </li>
        </ul>
        <div v-else class="sub_category">
          <p-range :name="label" :values="Object.keys(values)"/>
        </div>
  </fieldset>
</template>

<script>
import pRange from '@/Components/pRange.vue'

export default {
  name: 'pFilter',
  props: {
    label: {
      type: String,
      required: true
    },
    values: {
      type: Object,
      required: true
    }

  },
  data() {
    return {
        id: null,
        active: {},
        first: Object.keys(this.values)[0],
        ids:[]
    }
  },
  components: {
    pRange
  },
  methods: {
    triggered(name, value) {
      if ( !this.active[value] ) {

        this.active[value] = true

        //this.$emit('triggered', this.ids) //:
        this.$store.commit('addFilter', {'name':name, 'value':value})
        this.$store.dispatch('updateProductIds', this.$store.state.filters)
        // this.$emit('triggered', ids, false, '?range=price,'+this.range[0]+','+this.range[1])
     }
     else {
       this.active[value] = false
       //this.$emit('triggered', this.ids, true) // delete ids
       this.$store.commit('removeFilter', {'name':name, 'value':value})
       this.$store.dispatch('updateProductIds', this.$store.state.filters)
     }

   },
  },
  computed: {
   isHex: function() {

     return ( (''+this.first).includes('#') && this.first.length==7 ) ? true : false
   },
   isNumber: function() {
     return !isNaN(this.first)
   }
  },
  watch: {
    '$store.getters.numberOfFilters': function() {
      if ( this.$store.getters.numberOfFilters === 0 ) this.active = []

    }
  },
  mounted() {
// https://coolors.co/e2efde-afd0bf-808f87-9b7e46-f4b266
    this.id = Math.random().toString(36).slice(2)
  }
}
</script>

<style scoped>
  fieldset {
    display:flex;
    flex-direction:column;
    justify-content:space-evenly;
    border:none;
    padding:0;
    margin:0.4em 0;
  }
  input[type=checkbox].toggle{
    display:none;
  }
  .sub_category {
    list-style:none;
    padding:0;
    display:none;
    margin:0;
  }
  input[type=checkbox]:checked ~ .sub_category {
    display:block;
  }

  .toggle+label {
    border-bottom:1px solid #999;
  }
  label {
    width:100%;
    height:100%;
    display:block;
  }
  .toggle+label, li, >>> p {
    padding:0.4em;
    position:relative;
    flex:1 1 auto;
    cursor:pointer;
    text-transform: capitalize;
    display: inline-flex;
    align-items: center;
    width:100%;
  }
  li, >>> p {
    font-size:80%;
  }
  li:hover {
    background:#E2EFDE;
  }
  .toggle+label:after {
    content: '';
    width:1em;
    height:1em;
    text-align:center;
    position:absolute;
    right:0.4em;
    background: url("data:image/svg+xml,%3Csvg class='svg-inline--fa fa-chevron-down' aria-hidden='true' focusable='false' data-prefix='fas' data-icon='chevron-down' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512' data-v-615b7c3c=''%3E%3Cpath class='' fill='currentColor' d='M224 416c-8.188 0-16.38-3.125-22.62-9.375l-192-192c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L224 338.8l169.4-169.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-192 192C240.4 412.9 232.2 416 224 416z'%3E%3C/path%3E%3C/svg%3E") center center no-repeat;
  }
  input[type=checkbox]:checked ~ label:after {
    content: '';
    background-image: url("data:image/svg+xml,%3Csvg class='svg-inline--fa fa-chevron-up' aria-hidden='true' focusable='false' data-prefix='fas' data-icon='chevron-up' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512' data-v-615b7c3c=''%3E%3Cpath class='' fill='currentColor' d='M416 352c-8.188 0-16.38-3.125-22.62-9.375L224 173.3l-169.4 169.4c-12.5 12.5-32.75 12.5-45.25 0s-12.5-32.75 0-45.25l192-192c12.5-12.5 32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25C432.4 348.9 424.2 352 416 352z'%3E%3C/path%3E%3C/svg%3E");
  }

  .check, .circle {
    position:absolute;
    right:0.4em;
    font-size:120%;
    color:#21A0A0;
  }
  .circle {

    color:#F4B266;
  }

  .colour_dot {
    width:1.4em;
    height:1.4em;
    border:1px solid #222;
    border-radius:50%;
    margin-right:0.5em;
  }
</style>
