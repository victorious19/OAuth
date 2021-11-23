<template>
<div class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <p class="h1"><b>Good</b>Shop</p>
            </div>
            <div class="card-body">
                <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>
                <form @submit.prevent="reset">
                    <div class="form-group input-group mb-3">
                        <input type="email" class="form-control" v-bind:class="{'is-invalid':errors.email}" v-bind:value="$route.query.email" readonly>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
                    </div>
                    <div class="form-group input-group mb-3">
                        <input type="text" class="form-control" v-bind:class="{'is-invalid':errors.verification_code}" v-model="verification_code" placeholder="Verification code(6 symbols)">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-shield-alt"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback" v-if="errors.verification_code">{{errors.verification_code[0]}}</div>
                    </div>
                    <div class="form-group input-group mb-3">
                        <input type="password" class="form-control" v-bind:class="{'is-invalid':errors.password}" v-model="password"  placeholder="New Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback" v-if="errors.password">{{errors.password[0]}}</div>
                    </div>
                    <div class="form-group input-group mb-3">
                        <input type="password" class="form-control" v-model="password_confirmation" v-bind:class="{'is-invalid':errors.password||errors.main}" placeholder="Confirm Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback" >{{errors.main}}</div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Change password</button>
                        </div>
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <router-link to="/login">Login</router-link>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</div>
</template>

<script>
export default {
    data() {
      return {
          errors: {main: ''},
          email: this.$route.query.email,
          verification_code: '',
          password: '',
          password_confirmation: ''
      }
    },
    methods: {
        async reset() {
            this.errors = {main: ''}
            axios.post('/api/auth/change-password', {
                'email': this.email,
                'password': this.password,
                'password_confirmation': this.password_confirmation,
                'verification_code': this.verification_code
            })
                .then(res => {
                    this.$router.push('/login')
                })
                .catch(err => {
                    alert()
                    console.log(err.response)
                    if (err.response.data.errors) this.errors = err.response.data.errors
                    else if(err.response.data.message) this.errors.main =  err.response.data.message
                    else this.errors.main =  err.response.statusText
                })
        }
    }
}
</script>

<style scoped>

</style>
