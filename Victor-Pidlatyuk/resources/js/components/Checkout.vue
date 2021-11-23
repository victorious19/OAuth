<template>
<div v-if="product">
    <div class="px-3 py-2 border-bottom">
        <div class="d-flex flex-wrap justify-content-center">
            <p class="ml-3 me-lg-auto display-4 p-3">
                GoodShop
            </p>
            <div class="text-end mt-auto mb-auto p-3">
                <router-link to="/profile" class="btn btn-light text-dark me-2" v-if="$cookie.get('token') && $cookie.get('id')">Back to profile</router-link>
                <router-link to="/login" class="btn btn-light text-dark me-2">Login</router-link>
                <router-link to="/register" class="btn btn-secondary">Sign-up</router-link>
            </div>
        </div>
    </div>
    <div class="col-md-5 order-md-2 float-right mt-5 mr-5">
        <div class="row border">
            <table class="m-3">
                <tr>
                    <td style="width: 2%">
                        <img v-bind:src="'/storage/img/products/'+product.pictures.filter(el => el.status === 'main')[0].img_name" alt="Product picture" class="ml-3" style="height: 100px">
                    </td>
                    <td class="text-center">
                        <h6>Product name</h6><br>
                        {{product.title}}
                    </td>
                    <td class="text-center">
                        <h6>Price</h6><br>
                        {{product.price}}
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-md-6 order-md-1 mr-auto">
        <form class="needs-validation m-5">
        <div class="row g-3">
            <div class="col-12 form-group">
                <label for="fullName" class="form-label">Full name</label>
                <input type="text" class="form-control" id="fullName" v-bind:class="{'is-invalid':errors.user_full_name}" v-model="full_name" pattern="([a-zA-Zа-яА-Я]+\s){1,2}([a-zA-Zа-яА-Я]+)">
                <div class="invalid-feedback" v-if="errors.user_full_name">{{errors.user_full_name[0]}}</div>
            </div>

            <div class="col-12 form-group">
                <label for="number" class="form-label">Phone number</label>
                <input type="tel" class="form-control" id="number" placeholder="Format: +380123456789" v-model="phone" v-bind:class="{'is-invalid':errors.phone}">
                <div class="invalid-feedback" v-if="errors.phone">{{errors.phone[0]}}</div>
            </div>
            <div class="col-12 form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" placeholder="you@example.com" v-model="email" v-bind:class="{'is-invalid':errors.user_email}">
                <div class="invalid-feedback" v-if="errors.email">{{errors.user_email[0]}}</div>
            </div>
        </div>

        <hr class="my-4">

        <h4 class="mb-3">Payment</h4>
        <div class="row gy-3">
            <div class="form-group">
                <div id="card-element" class="mt-3 border p-3 border-gray-300 rounded-3"></div>
                <small id="card-error" role="alert" class="text-danger"></small>
                <button class="w-100 btn btn-primary btn-lg mt-3" type="button" @click.prevent="buy" id="make_order_button">Make a purchase</button>
            </div>
        </div>
        <div class = "alert alert-danger" v-if="errors.main">
            <a class="close" data-dismiss="alert"> × </a>
            <strong> Error! </strong> Server error. <br>{{errors.main}}
        </div>
    </form>
    </div>
</div>
<Spinner v-else></Spinner>
</template>

<script>
import Spinner from "./Spinner";
export default {
    components: {
        Spinner
    },
    name: "Checkout",
    props: ["product_id"],
    data() {
        return {
            product: null,
            stripe: null,
            card: null,
            full_name: '',
            phone: '',
            email: '',
            errors: {main: ''}
        }
    },
      mounted() {
        axios.get('/api/products/'+this.product_id, {},)
            .then(res => {
                this.product = res.data
            })
            .then(() => {
                this.stripe = Stripe('pk_test_51JOgHZG4Sc8ngUz3x5Kp5DGVJtrE0HsXtXc0lGMvGXIwK6YBpwe33RQFXOr7gSSP1rQ5LvXInfXhrtOw4yJiYa1Z00eBVkRR48')
                let elements = this.stripe.elements()
                this.card = elements.create('card')
                this.card.mount(document.getElementById('card-element'))
                this.card.on("change", function (event) {
                    // document.getElementById('make_order_button').disabled = event.empty
                    document.querySelector("#card-error").textContent = event.error ? event.error.message : "";
                });
            })
            .catch(err => {
                console.log(err)
            })
    },
    methods: {
        buy() {
            this.errors = {main: ''}
            this.stripe.createToken(this.card).then(res => {
                alert(res.token.id)
                axios.defaults.headers['Authorization'] = `Bearer ${this.$cookie.get('token')}`;
                axios.post('/api/orders', {
                    'stripeToken': res.token.id,
                    'product_id': this.product_id,
                    'user_full_name': this.full_name,
                    'user_email': this.email,
                    'phone': this.phone
                })
                    .then(res => {
                        if(res.data.status) this.$router.push('/thanks')
                        else this.errors.main = res.data.message
                    })
                    .catch(err => {
                        if (err.response.data.errors) this.errors = err.response.data.errors
                        else if(err.response.data.message) this.errors.main =  err.response.data.message
                        else this.errors.main =  err.response.statusText
                    })
            })
            .catch(err => {
                console.log(err)
            })
        }
    }
}
</script>

<style scoped>
</style>
