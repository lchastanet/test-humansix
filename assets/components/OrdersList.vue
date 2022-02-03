<template>
   <div class="px-4 py-5 my-5 text-center">
    <h1 class="display-5 fw-bold">Order List</h1>
     <div v-if="User">
        <p>Hi {{User}}</p>
      </div>
      <div v-else>
        ! LoggedIn
      </div>
  </div>
</template>

<script>
   import { mapGetters } from "vuex"

   export default {
       name: "ordersList",
       computed: {
         ...mapGetters({User: "StateUser"}),
       },
   }
</script>

<style scoped>

</style>