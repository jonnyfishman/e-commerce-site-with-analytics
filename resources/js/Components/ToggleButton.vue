<template>
    <button :class="{isActive: desc}" @click="triggered">
      <slot></slot>
    </button>
</template>

<script>
export default {
  props: {
    params: {
      type: Array,
      required: true
    },
  },
  data() {
    return {
      desc: false
    }
  },

  methods: {
    triggered() {
      this.desc = !this.desc

      // if ( this.desc ) {
        this.$emit('triggered', this.params.join(''))
      // }
      // else {
        // this.$emit('triggered', this.params[0])
      // }

    }
  }
}
</script>

<style scoped>
button {
  border:none;
  background:none;
  font-size: inherit;
  padding: 0;
  cursor:pointer;
  position:relative;
}
button:hover {
  font-weight:bold;
}

</style>
