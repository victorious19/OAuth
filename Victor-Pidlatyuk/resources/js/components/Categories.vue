<template>
<div v-if="categories" class="m-3">
    <div class="d-flex flex-row justify-content-between">
        <input class="form-control mr-3" type="text" placeholder="Category name" v-model="category_name">
        <button class="btn btn-outline-primary" style="white-space: nowrap;" @click.prevent="create_category">Create new category</button>
    </div>
    <small class="text-danger" v-if="error">{{error}}</small>
    <div class="card mt-3">
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                <tr>
                    <th style="width: 2%">
                        Id
                    </th>
                    <th class="text-center">
                        Category
                    </th>
                    <th class="text-center">
                        Delete
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="category in categories.data" v-bind:key="category.id">
                    <td>
                        {{category.id}}
                    </td>
                    <td class="text-center">
                        {{category.name}}
                    </td>
                    <td class="text-center">
                        <button class="btn btn-outline-danger mr-1" style="white-space: nowrap;" @click.prevent="() => delete_category(category.id)">Delete category</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="pb-5">
        <pagination :data="categories" @pagination-change-page="get_categories" class="d-flex" align="center"></pagination>
    </div>
</div>
<Spinner v-else></Spinner>
</template>

<script>
import Spinner from "./Spinner";

export default {
    name: "Categories",
    data() {
        return {
            categories: null,
            category_name: '',
            error: ''
        }
    },
    components: {
        Spinner
    },
    mounted() {
        this.get_categories(1)
    },
    methods: {
        get_categories(page = 1) {
            axios.defaults.headers['Authorization'] = `Bearer ${this.$cookie.get('token')}`;
            axios.get('/api/admin_categories?page='+page, {})
                .then(res => {
                    this.categories = res.data
                })
                .catch(err => {
                    console.log(err)
                })
        },
        create_category() {
            this.error = ''
            axios.post('/api/categories', {'name': this.category_name})
                .then(res => {
                    this.categories = null
                    this.category_name = null
                    this.get_categories()
                })
                .catch(err => {
                    if (err.response.data.errors) this.error = err.response.data.errors.name[0]
                    else this.error =  err.response.statusText
                })
        },
        delete_category(category_id) {
            this.error = ''
            axios.delete('/api/categories/'+category_id)
                .then(res => {
                    this.categories = null
                    this.category_name = null
                    this.get_categories()
                })
                .catch(err => {
                    if (!this.category_name) this.error = 'The name field is required.'
                    else this.error =  err.response.statusText
                })
        }
    },
}
</script>

<style scoped>

</style>
