<template>
    <div class="hold-transition register-page">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <p class="h1"><b>Good</b>Shop</p>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Register a new membership</p>

                <form @submit.prevent="register">
                    <div class="form-group input-group mb-3">
                        <input type="text" class="form-control" v-bind:class="{'is-invalid':errors.login}" placeholder="Username" v-model="login">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user-secret"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback" v-if="errors.login">{{errors.login[0]}}</div>
                    </div>

                    <div class="form-group input-group mb-3">
                        <input type="text" class="form-control" v-bind:class="{'is-invalid':errors.full_name}" placeholder="Full name" v-model="full_name">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback" v-if="errors.full_name">{{errors.full_name[0]}}</div>
                    </div>
                    <div class="form-group input-group mb-3">
                        <input type="email" class="form-control" v-bind:class="{'is-invalid':errors.email}" placeholder="Email" v-model="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
                    </div>
                    <div class="form-group input-group mb-3">
                        <input type="password" class="form-control" v-bind:class="{'is-invalid':errors.password}" placeholder="Password" v-model="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback" v-if="errors.password">{{errors.password[0]}}</div>
                    </div>
                    <div class="form-group input-group mb-3">
                        <input type="password" class="form-control" v-bind:class="{'is-invalid':errors.main}" placeholder="Retype password" v-model="password_confirmation">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback" >{{errors.main}}</div>
                    </div>
                    <div class="row-cols-1">
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                    </div>
                </form>

                <div class="social-auth-links text-center">
                    <a href="/api/auth/google" class="btn btn-block btn-danger">
                        <i class="fab fa-google-plus mr-2"></i>
                        Sign up using Google+
                    </a>
                </div>

                <router-link to="/login" class="text-center">I already have a membership</router-link>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import axios from 'axios'
export default {
    name: "RegisterPage",
    data() {
        return {
            errors: {main: ''},
            login: '',
            full_name: '',
            email: '',
            password: '',
            password_confirmation: ''
        }
    },
    methods: {
        async register(event) {
            event.preventDefault()
            this.errors = {main: ''}

            axios.post('http://auth-server.test:8000/api/auth/register', {
                'login':this.login,
                'full_name':this.full_name,
                'password':this.password,
                'password_confirmation':this.password_confirmation,
                'email':this.email
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
