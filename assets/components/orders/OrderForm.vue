<template>
  <div class="px-4 my-5 text-center">
    <h1 class="display-5 fw-bold mb-5">Order Form</h1>
    <div
      class="d-flex flex-row flex-wrap align-items-center justify-content-center mb-3"
    >
      <p class="mx-2 d-flex flex-row m-auto">
        <span class="badge bg-secondary mx-1">Products:</span>
        {{ quantity }}
      </p>
      <p class="mx-2 d-flex flex-row m-auto">
        <span class="badge bg-secondary mx-1">Total:</span>
        {{ price | currencydecimal }}
      </p>
      <button @click.prevent="emptyCart" class="btn btn-danger btn-sm mx-3">
        Empty Cart
      </button>
    </div>
    <form v-on:submit.prevent="onSubmit">
      <select
        v-model="customer"
        class="form-select mx-auto mb-4 w-25"
        aria-label="Customer select"
      >
        <option disabled value="">Please select a customer</option>
        <option
          v-for="customer in customers"
          :key="customer.id"
          :value="customer.id"
        >
          {{ customer | completeName }}
        </option>
      </select>
      <div
        class="container-fluid d-flex flex-row flex-wrap justify-content-around"
      >
        <div
          v-for="product in products"
          :key="product.sku"
          class="card m-2"
          style="width: 18rem"
        >
          <ProductCard
            :product="product"
            :page="page"
            @add-to-cart="addToCart"
          />
        </div>
      </div>
      <button
        v-if="quantity > 0 && customer > 0"
        @click.prevent="sendOrder"
        class="btn btn-success mt-5 w-20"
      >
        Order
      </button>
    </form>
  </div>
</template>

<script>
import axios from "axios"
import ProductCard from "../products/ProductCard.vue"

export default {
  name: "orderForm",
  components: {
    ProductCard,
  },
  data() {
    return {
      page: "order-form",
      customers: null,
      customer: "",
      products: null,
      error: null,
      cart: [],
      price: 0,
      quantity: 0,
    }
  },
  mounted() {
    axios
      .get("order/new")
      .then((res) => {
        this.customers = res.data.customer
        this.products = res.data.products
      })
      .catch((err) => (this.error = err))
  },
  methods: {
    addToCart(payload) {
      const productSku = payload.sku
      const alreadyIn = this.cart.find((product) => product.sku === productSku)

      if (alreadyIn) {
        alreadyIn.quantity += 1
      } else {
        this.cart.push({ sku: productSku, quantity: 1, price: payload.price })
      }

      const sumQuantity = (total, current) => total + current.quantity
      const sumPrice = (total, current) =>
        total + current.price * current.quantity

      this.quantity = this.cart.reduce(sumQuantity, 0)
      this.price = this.cart.reduce(sumPrice, 0)
    },
    emptyCart() {
      this.price = 0
      this.quantity = 0
      this.cart = []
    },
    async sendOrder() {
      const order = {}
      order.customer = this.customer
      order.cart = []

      this.cart.forEach((product) =>
        order.cart.push({ sku: product.sku, quantity: product.quantity })
      )

      await axios
        .post("order/new", order)
        .then((res) => this.$router.push("/orders-list"))
        .catch((error) => console.log(error))
    },
  },
  filters: {
    currencydecimal(value) {
      return `${value.toFixed(2)} €`
    },
    completeName(value) {
      return `${value.firstname} ${value.lastname}`
    },
  },
}
</script>

<style scoped></style>
