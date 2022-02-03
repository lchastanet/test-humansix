<template>
   <div class="px-4 my-5">
    <h1 class="display-5 fw-bold text-center mb-5">Orders List</h1>
    <div class="container-fluid d-flex flex-row flex-wrap justify-content-around">
       <div v-for="order in orders" :key="order.id" class="card m-2" style="width: 18rem;">
         <div class="card-body">
            <h5 class="card-title text-center">Order N°{{ order.id }}</h5>
            <p class="card-text fw-bold">Date : <span class="fw-normal">{{ order.orderDate | formatDate }}</span></p>
            <p class="card-text fw-bold">Status : <span class="fw-normal">{{ order.status }}</span></p>
            <p class="card-text fw-bold">Customer : <span class="fw-normal">{{ order.customer | completeName }}</span></p>
            <p class="card-text fw-bold">Price : <span class="fw-normal">{{ order.price | currencydecimal }} €</span></p>
            <a href="#" class="btn btn-primary">Details</a>
         </div>
       </div>
    </div>
   </div>
</template>

<script>
   import axios from "axios"
   import moment from "moment"

   export default {
       name: "ordersList",
       data () {
          return {
             orders: null,
             error: null
          }
       },
       mounted () {
          axios.get('order')
            .then(res => this.orders = res.data)
            .catch(err => this.error = err)
       },
       filters: {
         currencydecimal (value) {
            return value.toFixed(2)
         },
         completeName (value) {
            return `${value.firstname} ${value.lastname}`
         },
         formatDate (value) {
            return moment(value).format('[Le] MM/DD/YYYY [à] hh:mm')
         }
      },
   }
</script>

<style scoped>

</style>


// for using username
//    import { mapGetters } from "vuex"

//    export default {
//        name: "ordersList",
//        computed: {
//          ...mapGetters({User: "StateUser"}),
//        },
//    }