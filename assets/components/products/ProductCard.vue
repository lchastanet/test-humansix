<template>
  <div class="card-body">
    <h5 class="card-title text-center">{{ product.name }}</h5>
    <p class="card-text fw-bold">
      Sku : <span class="fw-normal">{{ product.sku }}</span>
    </p>
    <p class="card-text fw-bold">
      Price :
      <span class="fw-normal">{{ product.price | currencydecimal }} €</span>
    </p>
    <button
      v-if="page === 'order-form'"
      @click.prevent="$emit('add-to-cart', { sku: product.sku })"
      class="btn btn-primary"
    >
      Add to cart
    </button>
  </div>
</template>

<script>
export default {
  name: "productCard",
  props: {
    product: Object,
    required: true,
    page: null,
  },
  filters: {
    currencydecimal(value) {
      return value.toFixed(2)
    },
  },
}
</script>
