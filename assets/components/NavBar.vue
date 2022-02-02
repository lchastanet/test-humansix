<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <li class="nav-item"><router-link to="/" class="nav-link">Home</router-link></li>
                <li class="nav-item"><router-link to="/orders-list" class="nav-link">Order List</router-link></li>
            </div>
            </div>
        </div>
    </nav>
</template>

<script>
   export default {
       name: "navBar"
   }
</script>

<style scoped>

</style>