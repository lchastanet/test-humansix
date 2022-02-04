<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNavAltMarkup"
        aria-controls="navbarNavAltMarkup"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div
        class="collapse navbar-collapse justify-content-between"
        id="navbarNavAltMarkup"
      >
        <ul class="navbar-nav">
          <li class="nav-item">
            <router-link to="/" class="nav-link">Home</router-link>
          </li>
          <li class="nav-item">
            <router-link to="/orders-list" class="nav-link"
              >Order List</router-link
            >
          </li>
          <li class="nav-item">
            <router-link to="/products-list" class="nav-link"
              >Products List</router-link
            >
          </li>
          <li v-if="isLoggedIn" class="nav-item">
            <router-link to="/order-form" class="nav-link"
              >Create Order</router-link
            >
          </li>
        </ul>
        <div v-if="isLoggedIn" class="d-flex">
          <button @click="logout" class="btn btn-outline-danger" type="submit">
            Logout
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: "navBar",
  computed: {
    isLoggedIn: function () {
      return this.$store.getters.isAuthenticated
    },
  },
  methods: {
    async logout() {
      await this.$store.dispatch("LogOut")
      this.$router.push("/")
    },
  },
}
</script>

<style scoped></style>
