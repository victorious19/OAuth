<template>
    <div class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <p class="h1"><b>Good</b>Shop</p>
            </div>
            <div class="card-body">
                <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
                <form @submit.prevent="forgot">
                    <div class="form-group input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" v-model="email" v-bind:class="{'is-invalid':errors.length !== 0}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <div v-for="(error, index) in errors" class="invalid-feedback" :key="index">{{error}}</div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Send new password to email</button>
                        </div>
                    </div>
                </form>
                <p class="mt-3 mb-1">
                    <router-link to="/login">Login</router-link>
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
            email: '',
            errors: []
        }
    },
    mounted() {
        if(this.$route.query.message) this.errors.push(this.$route.query.message);
    },
    methods: {
        async forgot(event) {
            event.preventDefault()
            this.success = false
            this.errors = []
            axios.post('/api/auth/reset-password', {'email':this.email})
                .then(() => {
                    this.$router.push({ path: '/reset-password', query: {email: this.email }})
                })
                .catch(err => {
                    this.errors.push(err.response.data.message)
                    console.log(this.errors)
                })
        }
    }
}
</script>

