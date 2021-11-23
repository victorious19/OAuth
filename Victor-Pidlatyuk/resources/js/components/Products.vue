<template>
<div v-if="products" class="m-3">
    <link rel="stylesheet" href="/css/AdminProducts/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/AdminProducts/LineIcons.3.0.css" />
    <link rel="stylesheet" href="/css/AdminProducts/tiny-slider.css" />
    <link rel="stylesheet" href="/css/AdminProducts/glightbox.min.css" />
    <link rel="stylesheet" href="/css/AdminProducts/main.css" />
    <div class="alert" v-bind:class="this.message.class" role="alert">
    {{this.message.content}}
    </div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My products</h1>
                </div>
            </div>
        </div>
        <router-link to="/create-product" class="btn btn-primary row m-4 mb-0">Add new product</router-link>
    </section>

    <div class="row m-3">
        <Product v-for="product in products.data" v-bind:product="product" v-bind:key="product.id"></Product>
    </div>
    <div class="pb-5">
        <pagination :data="products" @pagination-change-page="get_products" class="d-flex" align="center"></pagination>
    </div>
</div>
<Spinner v-else></Spinner>
</template>

<script>
import Product from "./Product";
import Spinner from "./Spinner";
export default {
    name: "Products",
    components: {
        Product, Spinner
    },
    data() {
        return {
            products: null,
            message: {class: '', content: ''}
        }
    },
    mounted() {
        this.get_products(1)
        if(this.$route.query.class && this.$route.query.content) {
            this.message.class = this.$route.query.class
            this.message.content = this.$route.query.content
        }
    },
    methods: {
        async get_products(page = 1) {
            axios.defaults.headers['Authorization'] = `Bearer ${this.$cookie.get('token')}`;
            axios.get('/api/users/'+this.$cookie.get('id')+'/products?page='+page, {},)
                .then(res => {
                    this.products = res.data
                })
                .catch(err => {
                    console.log(err)
                })
        },
    },
}
</script>

<style scoped>
</style>

