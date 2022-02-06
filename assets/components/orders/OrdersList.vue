<template>
  <div class="px-4 my-5">
    <h1 class="display-5 fw-bold text-center mb-5">Orders List</h1>
    <div
      class="form-check form-switch d-flex flex-row justify-content-center p-0 mb-4"
    >
      <input
        v-model="orderedBy"
        @change="changeListOrder"
        class="form-check-input mx-1"
        type="checkbox"
        id="flexSwitchCheckDefault"
      />
      <label class="form-check-label mx-1" for="flexSwitchCheckDefault">{{
        orderedByLabel
      }}</label>
    </div>
    <OrderCard v-if="orders" :orders="orders" :key="orderList" />
  </div>
</template>

<script>
import OrderCard from "./OrderCard.vue"

export default {
  name: "ordersList",
  components: {
    OrderCard,
  },
  data() {
    return {
      orders: null,
      error: null,
      orderedBy: false,
      orderedByLabel: null,
      orderList: 0,
    }
  },
  mounted() {
    this.getOrders()
  },
  methods: {
    async getOrders() {
      const orderedBy = this.orderedBy ? "Older" : "Newer"

      this.orderedByLabel = orderedBy

      await this.$axios
        .get("order", { params: { orderedBy } })
        .then((res) => (this.orders = res.data))
        .catch((err) => (this.error = err))
    },
    async changeListOrder() {
      await this.getOrders()
      this.orderList++
      console.log(this.orderList)
    },
  },
}
</script>
