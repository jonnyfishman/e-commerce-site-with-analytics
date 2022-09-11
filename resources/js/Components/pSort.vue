<template>
    <span>
      <slot></slot>
        <font-awesome-icon class="chevron" @click.prevent="triggered()" icon="fa-solid fa-chevron-up" />
        <font-awesome-icon class="chevron" @click.prevent="triggered('d')" icon="fa-solid fa-chevron-down" />
    </span>
</template>

<script>
export default {
  name: 'pSort',
  props: {
    sortable: {
      type: Object,
      required: true
    }
  },
  methods: {
    triggered(desc = null) {

        this.$emit('triggered', this.sortable.column, desc)

    }
  }
}
</script>

<style scoped>
  .chevron:hover {
    color:#666;
  }
  .sort-group span {
    padding-right:0.2em;
    border-right:1px solid #ccc;
    margin-right:0.4em;
  }

</style>
