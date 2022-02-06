<template>
  <div class="px-4 my-5">
    <h1 class="display-5 fw-bold text-center mb-5">Orders List</h1>
    <div
      class="container-fluid d-flex flex-row flex-wrap justify-content-around"
    >
      <div
        v-for="order in orders"
        :key="order.id"
        class="card m-2"
        style="width: 18rem"
      >
        <router-link
          :to="{ name: 'orderDetails', params: { id: order.id } }"
          class="hidden-link"
        >
          <div class="card-body">
            <h5 class="card-title text-center">Order N°{{ order.id }}</h5>
            <p class="card-text fw-bold">
              Date :
              <span class="fw-normal">{{ order.orderDate | formatDate }}</span>
            </p>
            <p class="card-text fw-bold">
              Status :
              <span class="fw-normal">{{ order.status }}</span>
            </p>
            <p class="card-text fw-bold">
              Customer :
              <span class="fw-normal">{{ order.customer | completeName }}</span>
            </p>
            <p class="card-text fw-bold">
              Price :
              <span class="fw-normal"
                >{{ order.price | currencydecimal }} €</span
              >
            </p>
          </div>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment"

export default {
  name: "ordersList",
  data() {
    return {
      orders: null,
      error: null,
    }
  },
  mounted() {
    this.$axios
      .get("order")
      .then((res) => (this.orders = res.data))
      .catch((err) => (this.error = err))
  },
  filters: {
    currencydecimal(value) {
      return value.toFixed(2)
    },
    completeName(value) {
      return `${value.firstname} ${value.lastname}`
    },
    formatDate(value) {
      return moment(value).format("[Le] MM/DD/YYYY [à] HH:mm")
    },
  },
}
</script>

<style scoped>
.hidden-link {
  color: inherit;
  text-decoration: none;
}

.card:hover {
  cursor: pointer;
  transform: scale(1.1);
  transition: ease-in-out 300ms;
}
</style>
