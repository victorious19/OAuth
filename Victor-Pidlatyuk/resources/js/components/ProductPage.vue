<template>
<div v-if="product">
<div class="px-3 py-2 border-bottom" v-if="$route.path.split('/')[1] === 'products'">
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
<section class="content m-3">
        <!-- Default box -->
    <div class="card card-solid">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <button class="col-12 btn" data-bs-toggle="modal" data-bs-target="#myModal" @click="to_modal">
                            <img v-bind:src="'/storage/img/products/'+product.pictures[0].img_name" class="product-image" alt="Product Image" id="main_picture">
                        </button>

                        <!-- Modal -->
                        <div class="modal" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">{{product.title}}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click.prevent="modal_close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card-body">
                                            <div class="row text-center">
                                                <div id="carouselExampleControls" class="carousel slide carousel-fade" data-bs-ride="carousel">
                                                    <div class="carousel-inner">
                                                        <div class="carousel-item" v-for="picture in product.pictures">
                                                            <img v-bind:src="'/storage/img/products/'+picture.img_name" class="d-block w-100" alt="Product_picture">
                                                        </div>
                                                    </div>
                                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"  data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Next</span>
                                                    </button>
                                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"  data-bs-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Previous</span>
                                                    </button>
                                                </div>
                                                <h2 class="mb-0">
                                                    ₴{{product.price}}
                                                </h2>

                                                <div class="mt-4">
                                                    <button data-bs-dismiss="modal" aria-label="Close" @click.prevent="$router.push('/checkout/'+product_id)" class="btn btn-primary btn-lg btn-flat">
                                                        <i class="fas fa-cart-plus fa-lg mr-2"></i>
                                                        Add to Cart
                                                    </button>
                                                </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 product-image-thumbs">
                            <div class="product-image-thumb" v-for="picture in product.pictures" v-bind:class="{active:picture.status === 'main'}"><img v-bind:src="'/storage/img/products/'+picture.img_name" alt="Product Image" @click.prevent="img_click"></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <h3 class="my-3">{{product.title}}</h3>
                        <p>Description: {{product.description}}</p>

                        <hr>

                        <h2 class="mb-0">
                            ₴{{product.price}}
                        </h2>

                        <div class="mt-4">
                            <router-link :to="'/checkout/'+product_id" class="btn btn-primary btn-lg btn-flat">
                                <i class="fas fa-cart-plus fa-lg mr-2"></i>
                                Add to Cart
                            </router-link>
                        </div>

                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
</section>
</div>
<Spinner v-else></Spinner>
</template>

<script>
import Spinner from "./Spinner";
export default {
    components: {
        Spinner
    },
    props: ["product_id"],
    data() {
        return {
            product: null,
        }
    },
    mounted() {
        axios.defaults.headers['Authorization'] = `Bearer ${this.$cookie.get('token')}`;
        axios.get('/api/products/'+this.product_id, {},)
            .then(res => {
                this.product = res.data
            })
            .catch(err => {
                console.log(err)
            })
    },
    methods: {
        img_click (event) {
            let main_picture = document.getElementById('main_picture')
            main_picture.src = event.target.src
            document.querySelector('.product-image-thumbs .active').classList.remove('active')
            event.target.parentElement.classList.add('active')
        },
        to_modal(event) {
            let pictures = Array.from(document.getElementsByClassName('carousel-item'))
            pictures.forEach(picture => {if(picture.children[0].src === event.target.src) {
                picture.classList.add('active')
            }
            })
        },
        modal_close() {
            document.querySelector('.carousel-inner .active').classList.remove('active')
        }
    }

}
</script>

<style scoped>
.active {
    background-color: lightgrey;
}
</style>
