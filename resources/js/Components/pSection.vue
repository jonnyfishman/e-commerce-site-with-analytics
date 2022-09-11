<template>
    <div class="product-wrapper">
      <img v-if="product.image" :src="'/assets/product_files/' + product.image + '_400x400.png'"/>
{{product.is_active}}
      <h2>{{ product.name }}</h2>
      <h4>{{ product.brand }}</h4>
      <p>£{{ product.price }}</p>
    </div>
</template>

<script>
export default {
  name: 'pSection',
  props: {
    product: {
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped>
  .product-wrapper {
    background: #fff;
    height: 100%;
    display: inline-flex;
    flex-direction: column;
    justify-content: flex-end;
  }
  .product-wrapper img {
    width:100%;
  }
  h2, h4, p {
    font-weight:normal;
    margin:0;

  }
</style>
