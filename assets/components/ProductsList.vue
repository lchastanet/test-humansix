<template>
   <div class="px-4 my-5">
    <h1 class="display-5 fw-bold text-center mb-5">Products List</h1>
    <div class="container-fluid d-flex flex-row flex-wrap justify-content-around">
       <div v-for="product in products" :key="product.sku" class="card m-2" style="width: 18rem;">
         <div class="card-body">
            <h5 class="card-title text-center">{{ product.name }}</h5>
            <p class="card-text fw-bold">Sku : <span class="fw-normal">{{ product.sku }}</span></p>
            <p class="card-text fw-bold">Price : <span class="fw-normal">{{ product.price | currencydecimal }} €</span></p>
         </div>
       </div>
    </div>
   </div>
</template>

<script>
   import axios from "axios"

   export default {
       name: "productsList",
       data () {
          return {
             products: null,
             error: null
          }
       },
       mounted () {
          axios.get('product')
            .then(res => this.products = res.data)
            .catch(err => this.error = err)
       },
       filters: {
         currencydecimal (value) {
            return value.toFixed(2)
         }
      },
   }
</script>

<style scoped>
   .card:hover {
      cursor: pointer;
      transform: scale(1.1);
      transition: ease-in-out 300ms;
   }
</style>