<template>
<form class="content m-3" @submit.prevent="change" v-if="categories">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="form-group">
                        <label for="inputTitle">Title</label>
                        <input type="text" id="inputTitle" class="form-control" v-model="title" v-bind:class="{'is-invalid':errors.title}">
                        <div class="invalid-feedback" v-if="errors.title">{{errors.title[0]}}</div>
                    </div>
                    <div class="form-group">
                        <label for="inputDescription">Description</label>
                        <textarea id="inputDescription" class="form-control" rows="4" v-model="description" v-bind:class="{'is-invalid':errors.description}"></textarea>
                        <div class="invalid-feedback" v-if="errors.description">{{errors.description[0]}}</div>
                    </div>
                    <div class="form-group">
                        <label for="inputCategory">Category</label>
                        <select id="inputCategory" class="form-control custom-select" v-model="category" v-bind:class="{'is-invalid':errors.category_id}">
                            <option v-for="category in categories">{{ category.name }}</option>
                        </select>
                        <div class="invalid-feedback" v-if="errors.category_id">{{errors.category_id[0]}}</div>
                    </div>
                    <div class="form-group">
                        <label for="inputPrice">Price</label>
                        <input type="number" id="inputPrice" class="form-control" v-model="price" v-bind:class="{'is-invalid':errors.price}">
                        <div class="invalid-feedback" v-if="errors.price">{{errors.price[0]}}</div>
                    </div>
                    <div class="form-group">
                        <label for="inputPicture">Picture</label>
                        <input type="file" id="inputPicture" multiple class="form-control" accept=".png, .jpg" @change.prevent="upload" v-bind:class="{'is-invalid':errors.pictures}">
                        <div class="invalid-feedback" v-if="errors.pictures">{{errors.pictures[0]}}</div>
                    </div>
                    <div class = "alert alert-danger" v-if="errors.main">
                        <a class="close" data-dismiss="alert"> × </a>
                        <strong> Error! </strong> Server error. {{ errors.main }}
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <router-link to="/profile/products" class="btn btn-secondary">Cancel</router-link>
            <input type="submit" value="Change product" class="btn btn-success float-right">
        </div>
    </div>
</form>
<Spinner v-else></Spinner>
</template>

<script>
import Spinner from "./Spinner";
export default {
    props:['product_id'],
    components: {
      Spinner
    },
    data() {
        return {
            title: '',
            description: '',
            category: '',
            price: null,
            formData: new FormData(),
            errors: {main: ''},
            categories: null,
        }
    },
    mounted() {
        axios.get('/api/categories', {})
            .then(res => {
                this.categories = res.data
            })
            .catch(err => {
                alert(err)
            })
    },
    methods: {
        upload(event) {
            let files = event.target.files
            for (let i = 0; files[i]; i++) {
                this.formData.append(`pictures[${i}]`, files[i])
            }
        },
        change() {
            this.errors = {main: ''}
            if(this.title) this.formData.append('title', this.title)
            if(this.description)this.formData.append('description', this.description)
            if(this.price) this.formData.append('price', this.price)
            if(this.category) this.formData.append('category', this.category)
            this.formData.append("_method", "PATCH");
            axios.defaults.headers['Authorization'] = `Bearer ${this.$cookie.get('token')}`;
            axios.post('/api/products/'+this.product_id, this.formData)
                .then(res => {
                    this.$router.push({path:'/profile/products', query: {class:'alert-primary', content:'Successful change'}})
                })
                .catch(err => {
                    this.success = false
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
