<template>
  <form @submit.prevent="submit" id="form" class="m-auto mt-5">
    <div
      v-if="showError"
      class="alert alert-danger alert-dismissible fade show"
      role="alert"
    >
      Invalid credentials !
      <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Close"
      ></button>
    </div>
    <div class="mb-3">
      <label for="inputUsername" class="form-label">Login</label>
      <input
        v-model="form.username"
        type="text"
        class="form-control"
        id="inputUsername"
        aria-describedby="username"
      />
    </div>
    <div class="mb-3">
      <label for="inputPassword" class="form-label">Password</label>
      <input
        v-model="form.password"
        type="password"
        class="form-control"
        id="inputPassword"
      />
    </div>
    <button type="submit" class="btn btn-primary w-100">Connexion</button>
  </form>
</template>

<script>
import { mapActions } from "vuex"

export default {
  name: "home",
  data() {
    return {
      form: {
        username: "",
        password: "",
      },
      showError: false,
      error: null,
    }
  },
  methods: {
    ...mapActions(["LogIn"]),
    async submit() {
      const user = {
        username: this.form.username,
        password: this.form.password,
      }
      try {
        await this.LogIn(user)
        this.$router.push("/orders-list")
        this.showError = false
      } catch (error) {
        this.showError = true
      }
    },
  },
}
</script>

<style scoped>
#form {
  width: 300px;
}
</style>
