<template>
          <p><span>Min: £{{ range[0] }}</span><span>Max: £{{ range[1] }}</span></p><!-- Add function to check units-->

           <vue-slider
              v-model="range"
              :min="this.min"
              :max="this.max"
              :tooltip-placement="['bottom', 'bottom']"
              :adsorb="true"
              :lazy="true"
              :interval="interval"
              @drag-end="getTriggered"
              :min-range="interval"
              :contained="true"
              :dragOnClick="true"
              :tooltip="'hover'"
              :tooltip-formatter="val => '£' + val">

           </vue-slider>

</template>

<script>
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/default.css'

export default {
  name: 'pRange',
  props: {
    values: {
      type: Object,
      required: true
    },
    name: {
      type: String,
      required: true
    }
  },
  data() {
    return {
        interval: 10,
        min: parseInt(this.values[0]),
        max: parseInt(this.values[this.values.length-1]) + 10,
        range: [],
        ids: [],
        filter: this.$store.getters.numberOfFilters
    }
  },
  emits: ['triggered'],
  components: {
    VueSlider
  },
  methods: {

   getTriggered() {

     this.$store.commit('addFilter', {'name':'range', 'value':this.name+','+this.range[0]+','+this.range[1]})
     this.$store.dispatch('updateCategories',{'name':this.name, 'query':'range=price,'+this.range[0]+','+this.range[1]})
     this.$store.dispatch('updateProductIds', this.$store.state.filters)
     //this.$emit('triggered', this.ids.flatMap(id=>id), false, 'range=price,'+this.range[0]+','+this.range[1])
   },
   reset() {
     this.range = [this.min,this.max]
   }
  },
  watch: {
    '$store.getters.numberOfFilters': function() {
      if ( this.$store.getters.numberOfFilters === 0 )this.reset()

    }
  },
  computed: {
/*
    max: function() {
      return this.values[this.values.length-1].sub_category + this.interval
    },
*/
    getRange: function() {
        return [this.min, this.max]
    }
  },

  mounted() {
    this.reset()
  }
}
</script>

<style scoped>
  p {
    display:flex;
    justify-content:space-between;
  }
</style>
