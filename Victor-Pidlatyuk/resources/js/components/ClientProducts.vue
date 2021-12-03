<template>
    <div v-if="products && categories">
        <link rel="stylesheet" href="/css/AdminProducts/bootstrap.min.css" />
        <link rel="stylesheet" href="/css/AdminProducts/LineIcons.3.0.css" />
        <link rel="stylesheet" href="/css/AdminProducts/tiny-slider.css" />
        <link rel="stylesheet" href="/css/AdminProducts/glightbox.min.css" />
        <link rel="stylesheet" href="/css/AdminProducts/main.css" />
        <div class="px-3 py-2 border-bottom">
            <div class="d-flex flex-wrap justify-content-center">
                <p class="ml-3 me-lg-auto display-4 p-3">
                    GoodShop
                </p>
                <div class="mt-auto mb-auto mr-3 p-3">
                    <div class="d-flex">
                        <input class="form-control me-2" type="search" placeholder="Search product" v-model="search_value" style="width: 250px">
                        <button class="btn btn-outline-danger mr-2" v-if="this.filter.search_field" @click.prevent="cancel">Cancel</button>
                        <button class="btn btn-outline-primary" @click.prevent="search">Search</button>
                    </div>
                </div>
                <div class="text-end mt-auto mb-auto p-3">
                    <router-link to="/profile" class="btn btn-light text-dark me-2" v-if="$cookie.get('token') && $cookie.get('id')">Back to profile</router-link>
                    <a href="/oauth/redirect" class="btn btn-light text-dark me-2">Login</a>
                </div>
            </div>
        </div>
        <div class="alert" v-bind:class="this.message.class" role="alert" v-if="message">
            {{this.message.content}}
        </div>
        <div class="d-flex">
            <div class="ml-auto mr-3">
                <select class="form-select" v-model="sort_param" @change.prevent="get_products">
                    <option value="date asc">Newest</option>
                    <option value="date desc">Latest</option>
                    <option value="name asc">Name (A-Z)</option>
                    <option value="name desc">Name (Z-A)</option>
                    <option value="price asc">Price (low-high)</option>
                    <option value="price desc">Price (high-low)</option>
                </select>
            </div>
        </div>
        <div class="d-flex" v-if="is_filter">
            <div class="mr-auto ml-3">
                <button class="btn btn-outline-dark" @click.prevent="clear_filters" style="border-radius: 20px">
                    <span aria-hidden="true">&times;</span>
                    Clear filters
                </button>
            </div>
        </div>
        <div>
            <div class="float-left  mt-3">
                <nav class="mt-3" style="width: 300px">
                    <header class="card-header">
                        <h3 class="ml-3">Filters</h3>
                    </header>
                    <header class="card-header">
                        <button data-toggle="collapse" data-target="#collapse_aside1" data-abc="true" aria-expanded="false" class="collapsed w-100 btn">
                            <i class="icon-control fa fa-chevron-down float-right"></i>
                            <label class="float-left">Categories</label>
                        </button>
                    </header>
                    <div class="collapse" id="collapse_aside1">
                        <div class="d-flex flex-column category m-3">
                            <label class="form-check ml-3 user-select-none" v-for="category in categories">
                                <input type="checkbox" class="form-check-input" @change.prevent="(event) => category_filter(event.target.checked, category.id)">
                                <span class="form-check-label"> {{category.name}} </span>
                            </label>
                        </div>
                    </div>
                    <header class="card-header">
                        <button data-toggle="collapse" data-target="#collapse_aside2" data-abc="true" aria-expanded="false" class="collapsed w-100 btn">
                            <i class="icon-control fa fa-chevron-down float-right"></i>
                            <label class="float-left">Price </label>
                        </button>
                    </header>
                    <div class="collapse" id="collapse_aside2">
                        <div class="card-body">
                            <div class="form-row mt-3">
                                <div class="form-group col-md-6"> <label>Min</label>
                                    <input v-model="minPrice" class="form-control" type="number" step="0.01" @change="sliderChange">
                                </div>
                                <div class="form-group text-right col-md-6">
                                    <label>Max</label>
                                    <input v-model="maxPrice" class="form-control" type="number" step="0.01" @change="sliderChange">
                                </div>
                            </div>
                            <button class="btn btn-danger mr-2" data-abc="true" @click.prevent="disable_price_filter">Cancel</button>
                            <button class="btn btn-primary" data-abc="true" @click.prevent="price_filter">Apply Now</button>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="row mt-3 pb-5">
                <Product v-for="product in products.data" v-bind:product="product" v-bind:key="product.id"></Product>
                <pagination :data="products" @pagination-change-page="get_products" class="d-flex" align="center"></pagination>
            </div>
        </div>
    </div>
<Spinner v-else></Spinner>
</template>

<script>
import Product from "./Product";
import Spinner from "./Spinner";
import Page404 from "./404"
export default {
    components: {
        Product, Spinner, Page404
    },
    data() {
        return {
            products: null,
            categories: null,
            message: {class: '', content: ''},
            search_value: '',
            minPrice: 0,
            maxPrice: 0,
            sort_param: 'date asc',
            filter: {
                category: [],
                search_field: '',
                minPrice: '',
                maxPrice: ''
            },
            is_filter: false
        }
    },
    mounted() {
        axios.all([
            axios.get('/api/products', {}),
            axios.get('/api/categories', {})
        ])
            .then(axios.spread((products, categories) => {
                this.products = products.data
                this.categories = categories.data
            }))
            .catch(err=>{
                console.log(err)
            })

        if(this.$route.query.class && this.$route.query.content) {
            this.message.class = this.$route.query.class
            this.message.content = this.$route.query.content
        }
    },
    methods: {
        price_filter() {
            this.filter.minPrice = this.minPrice
            this.filter.maxPrice = this.maxPrice
            this.get_products()
        },
        make_query() {
            let query = ''
            let sort = this.sort_param.split(' ')

            if (this.filter.minPrice !== '' && this.filter.maxPrice !== '')
                query += '&min_price='+this.filter.minPrice+'&max_price='+this.filter.maxPrice
            if (this.filter.category.length > 0) query += '&category_id='+this.filter.category.join(',');
            if (this.filter.search_field) query += '&search_field='+ this.filter.search_field

            if (query !== '') this.is_filter = true
            else this.is_filter = false

            query += '&'+sort[0]+'='+sort[1]

            return query;
        },
        async get_products(page = 1) {
            axios.get('/api/products?page='+page+this.make_query(), {},)
                .then(res => {
                    this.products = res.data
                })
                .catch(err => {
                    console.log(err)
                })
        },
        cancel() {
            this.filter.search_field = ''
            this.search_value = ''
            this.get_products()
        },
        disable_price_filter() {
            this.filter.minPrice = ''
            this.filter.maxPrice = ''
            this.get_products()
        },
        search() {
            this.filter.search_field = this.search_value
            this.get_products()
        },
        sliderChange() {
            if (+this.minPrice > +this.maxPrice) {
                let temp = this.maxPrice
                this.maxPrice = this.minPrice
                this.minPrice = temp
            }
        },
        category_filter(checked,category_id) {
            if (checked) {
                this.filter.category.push(category_id)
            }
            else {
                let index = this.filter.category.indexOf(category_id);
                if (index > -1) {
                    this.filter.category.splice(index, 1);
                }
            }
            this.get_products()
        },
        clear_filters() {
            this.filter = {
                category: [],
                search_field: '',
                minPrice: '',
                maxPrice: ''
            }
            Array.from(document.querySelectorAll('input[type=checkbox]')).map(el => {
                el.checked = false
            })
            this.minPrice = this.maxPrice = 0
            this.search_value = ''
            this.get_products()
        }
    },
}
</script>

<style scoped>
.range-slider {
    position: absolute;
    width: 250px;
}
.range-slider::-webkit-slider-thumb {
    z-index: 2;
    position: relative;
}
.category {
    height: auto;
    max-height: 30em;
    overflow-x: hidden;
}
</style>

