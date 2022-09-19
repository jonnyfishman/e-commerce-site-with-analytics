<template>

          <p><span>Min: £{{ range[0] }}</span><span>Max: £{{ range[1] }}</span></p><!-- Add function to check units-->

           <vue-slider v-model="range" :min="this.min" :max="this.max" :tooltip-placement="['bottom', 'bottom']" :adsorb="true" :lazy="true" :interval="interval" @drag-end="getTriggered" :min-range="interval" :contained="true" :dragOnClick="true" :tooltip="'hover'" :tooltip-formatter="val => '£' + val">

           </vue-slider>

</template>

<script>
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/default.css'

export default {
  name: 'pRange',
  props: {
    options: {
      type: Object,
      required: true
    },
    filter: {
      type: Number
    }
  },
  data() {
    return {
        interval: 10,
        min: this.options[0].sub_category,
        max: this.options[this.options.length-1].sub_category + 10,
        range: [],
        ids: []
    }
  },
  emits: ['triggered'],
  components: {
    VueSlider
  },
  methods: {

   getTriggered() {
     this.$emit('triggered', this.ids.flatMap(id=>id), true)
      this.ids = []
     this.options.forEach(id => {
       if ( id.sub_category >= this.range[0] && id.sub_category < this.range[1] ) this.ids.push(id.values)
     })
     this.$emit('triggered', this.ids.flatMap(id=>id), false, '?range=price,'+this.range[0]+','+this.range[1])
   },
   reset() {
     this.range = [this.min,this.max]
   }
  },
  watch: {
    filter: function() {  // on filter change check list of ids and compare to change slider?
      if (this.filter == 0) this.reset()

    }
  },
  computed: {
/*
    max: function() {
      return this.options[this.options.length-1].sub_category + this.interval
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
