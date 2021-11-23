<template>
<div class="col-lg-3 col-md-6 col-12">
    <div class="single-product">
        <button class="btn float-right" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" v-if="$route.path === '/profile/products'">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
            </svg>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li><router-link class="dropdown-item" v-bind:to="{ name: 'change-product', params: { product_id: product.id }}">Change</router-link></li>
            <li><button class="dropdown-item" @click.prevent="delete_product">Delete</button></li>
        </ul>
        <div class="product-image">
            <router-link v-bind:to="$route.path === '/'?'/products/'+product.id:'/product_page/'+product.id"><img v-bind:src="'/storage/img/products/'+product.picture" alt="Product picture"></router-link>
            <div class="button" v-if="$route.path === '/'">
                <router-link :to="$route.path === '/'?'/products/'+product.id:'/product_page/'+product.id" class="btn"><i class='fas fa-shopping-bag'></i>More details</router-link>
            </div>
        </div>
        <div class="product-info">
            <span class="text text-break">{{product.category}}</span>
            <h4 class="title">
                <router-link v-bind:to="'/product_page/'+product.id">{{product.title}}</router-link>
            </h4>
            <p class="text text-break description">{{product.description}}</p>
            <p class="text text-break" v-if="$route.path === '/profile/products'">Successful purchases: {{product.purchases}}</p>
            <div class="price">
                <span>₴{{product.price}}</span>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "Product",
    props:['product'],
    mounted() {

    },
    methods: {
        delete_product() {
            axios.delete('/api/products/'+this.product.id, {})
                .then(res => {
                    this.$parent.get_products()
                    if (res) {
                        this.$parent.message.class = 'alert-primary'
                        this.$parent.message.content = 'Successful deletion'
                    }
                    else {
                        this.$parent.message.class = 'alert-danger'
                        this.$parent.message.content = 'Something went wrong'
                    }
                })
                .catch(err => {
                    alert(err)
                })
        }
    }
}
</script>

<style scoped>
    .description {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
