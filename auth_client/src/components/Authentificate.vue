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
    let auth_code = this.$route.query.auth_code;

    if (auth_code) {
      this.$cookie.set('auth_code', auth_code);
    }

    if (!this.$cookie.get('auth_code')) {
      this.$router.push('/login')
    }
  }
}
</script>

<style scoped>

</style>