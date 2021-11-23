<template>
<div class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <p class="h1"><b>Good</b>Shop</p>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form @submit.prevent="login">
                    <div class="form-group input-group mb-3">
                        <input type="text" class="form-control" v-bind:class="{'is-invalid':errors.login}" placeholder="Login or email" v-model="username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback" v-if="errors.login">{{errors.login[0]}}</div>
                    </div>
                    <div class="form-group input-group mb-3">
                        <input type="password" class="form-control" v-bind:class="{'is-invalid':errors.password||errors.main}" placeholder="Password" v-model="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback" v-if="errors.password">{{errors.password[0]}}</div>
                        <div class="invalid-feedback" >{{errors.main}}</div>
                    </div>
                    <div class="row-cols-1">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                </form>

                <div class="social-auth-links text-center mt-2 mb-3">
                    <a href="/api/auth/google" class="btn btn-block btn-danger">
                        <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                    </a>
                </div>

                <p class="mb-1">
                    <router-link to="/forgot-password">I forgot my password</router-link>
                </p>
                <p class="mb-0">
                    <router-link to="/register" class="text-center">Register a new membership</router-link>
                </p>
            </div>
        </div>
    </div>
</div>

</template>

<script>
import axios from 'axios'
export default {
    data() {
        return {
            errors: {main:''},
            username: '',
            password: ''
        }
    },
    mounted() {
        if(this.$route.query.message) this.errors.main = this.$route.query.message
    },
    methods: {
        async login(event) {
            event.preventDefault()
            this.errors = {main: ''}

            axios.post('/api/auth/login', {
                'login': this.username,
                'password': this.password
            })
                .then(res => {
                    this.$router.push({path: '/profile', query: {token: res.data.token, id: res.data.user.id}})
                })
                .catch(err => {
                    if (err.response.data.errors) this.errors = err.response.data.errors
                    else if(err.response.data.message) this.errors.main =  err.response.data.message
                    else this.errors.main =  err.response.statusText
                })
        }
    }
}
</script>

