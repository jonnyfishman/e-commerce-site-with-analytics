<template>
  <fieldset>
    <input class="toggle" type="checkbox" :id="id"/>
    <label :for="id">{{ label }}</label><!-- add fa icon-->

      <template v-if="getType(options[0].sub_category) === 'int'">


          <div>Min: £{{ value[0] }} Max: £{{ value[1]}}</div>
          
           <vue-slider v-model="value" :min="Math.floor(options[0].sub_category / 100) * 100" :max="Math.ceil(options[options.length-1].sub_category / 100) * 100" drag-end="alert('end')" :tooltip-placement="['bottom', 'bottom']" :adsorb="true" :interval="10" @drag-end="getTriggered">

           </vue-slider>

      </template>
      <template v-else>
        <ul>
          <li :class="{active: active[index] }" v-for="(option, index) in options" :key="option.sub_category" @click="triggered(option.values, index)">
            <template v-if="getType(option.sub_category) === 'hex'">
              <span class="colour_dot" :style="{ 'background-color': `${option.sub_category}`}"></span>({{ option.values.length }})
            </template>
            <template v-else>
            {{ option.sub_category }} ({{ option.values.length }})
            </template>
            <font-awesome-icon v-if="!active[index]" class="circle" icon="fa-regular fa-circle" />
            <font-awesome-icon v-if="active[index]" class="check" icon="fa-regular fa-circle-check" />

          </li>
        </ul>
      </template>

  </fieldset>
</template>

<script>
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/default.css'

export default {
  name: 'pFilter',
  props: {
    label: {
      type: String,
      required: true
    },
    options: {
      type: Object,
      required: true
    },
    filter: {
      type: Number
    },

  },
  data() {
    return {
        id: null,
        active: [],
        value: [Math.floor(this.options[0].sub_category / 100) * 100,Math.ceil(this.options[this.options.length-1].sub_category / 100) * 100]
    }
  },
  components: {
    VueSlider
  },
  methods: {
    triggered(ids, index) {


      if ( !this.active[index] ) {

        this.active[index] = true

        this.$emit('triggered', ids) //:
     }
     else {
       this.active[index] = false
       this.$emit('triggered', ids, true)
     }

   },
   getType(name) {
     if ( Number.isInteger(name) ) {
       return 'int'
     }
     else if ( name.includes('#') > -1 && name.length==7 ) {
       return 'hex'
     }
     return 'str'
   },
   getTriggered() {
     let ids = []
     this.options.map(id => {
       if ( id.sub_category >= this.value[0] && id.sub_category < this.value[1] ) ids.push(id.values)
     })
     this.$emit('triggered', ids)
   }
  },
  watch: {
    filter: function() {
      !isInteger(options.sub_category[0])
      if ( this.filter == 0 ) this.active = []

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
  ul {
    list-style:none;
    padding:0;
    display:none;
    margin:0;
  }
  input[type=checkbox]:checked ~ ul {
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
  .toggle+label, li {
    padding:0.4em;
    position:relative;
    flex:1 1 auto;
    cursor:pointer;
    text-transform: capitalize;
    display: inline-flex;
    align-items: center;
    width:100%;
  }
  li {
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
