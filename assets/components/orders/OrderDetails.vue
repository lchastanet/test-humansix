<template>
  <div v-if="order" class="px-4 my-5 text-center">
    <h1 class="display-5 fw-bold mb-5">Order Details</h1>
    <h2 class="display-9 fw-bold">Commande N°{{ order.id }}</h2>
    <p>
      Passée {{ order.orderDate | formatDate }}, par
      {{ order.customer | completeName }}
    </p>
    <p>Status: {{ order.status }}</p>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Sku</th>
          <th scope="col">Name</th>
          <th scope="col">Price</th>
          <th scope="col">Quantity</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="line in order.carts" :key="line.id">
          <th scope="row">{{ line.product[0].sku }}</th>
          <td>{{ line.product[0].name }}</td>
          <td>{{ line.product[0].price | currencydecimal }}</td>
          <td>{{ line.quantity }}</td>
        </tr>
      </tbody>
    </table>
    <p class="mt-5">Total : {{ order.price | currencydecimal }}</p>
  </div>
</template>

<script>
import moment from "moment"

export default {
  name: "orderDetails",
  data() {
    return {
      id: this.$route.params.id,
      order: null,
      error: null,
    }
  },
  mounted() {
    this.$axios
      .get(`order/${this.id}`)
      .then((res) => (this.order = res.data))
      .catch((err) => (this.error = err))
  },
  filters: {
    currencydecimal(value) {
      return `${value.toFixed(2)} €`
    },
    completeName(value) {
      return `${value.firstname} ${value.lastname}`
    },
    formatDate(value) {
      return moment(value).format("[le] MM/DD/YYYY [à] HH:mm")
    },
  },
}
</script>

<style scoped>
.table {
  max-width: 500px;
  margin: auto;
}
</style>
