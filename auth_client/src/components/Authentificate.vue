<template>
  <div class="text-center">
    <h2>GoodShop is requesting permissions to access your account</h2>
    <a class="btn btn-primary mr-3" href="http://auth-server.test:8000/api/answer?accept=true">Accept</a>
    <a class="btn btn-danger" href="http://auth-server.test:8000/api/answer?accept=true">Decline</a>
  </div>
</template>

<script>
export default {
  mounted() {
    if (!this.$cookie.get('auth_code')) {
      this.$router.push('/login')
    }
  }
}
</script>

<style scoped>

</style>