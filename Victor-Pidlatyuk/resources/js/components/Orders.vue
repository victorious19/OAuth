<template>
<section class="m-3" v-if="orders">
    <div class="d-flex mb-3">
        <input class="form-control me-2" type="text" placeholder="Search order by email, phone number or product name" v-model="search_value">
        <button class="btn btn-outline-danger mr-2" v-if="filter" @click.prevent="cancel">Cancel</button>
        <button class="btn btn-outline-primary" @click.prevent="()=>getOrders(1)">Search</button>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                <tr>
                    <th style="width: 2%">
                        Id
                    </th>
                    <th>
                        Product
                    </th>
                    <th>
                        Client
                    </th>
                    <th>
                        Price
                    </th>
                    <th style="width: 8%" class="text-center">
                        Status
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="order in orders.data" v-bind:key="order.id">
                    <td>
                        {{order.id}}
                    </td>
                    <td>
                        <a>
                            {{order.product}}
                        </a>
                        <br/>
                        <small>
                            Created {{(normal_date(order))}}
                        </small>
                    </td>
                    <td>
                        {{ order.user_full_name }}
                        <br/>
                        <small>
                            {{ order.user_email }}
                        </small>
                        <br>
                        <small>
                            {{ order.phone }}
                        </small>
                    </td>
                    <td>
                        {{order.price}}₴
                    </td>
                    <td class="project-state">
                            <select class="badge" v-bind:id="'select'+order.id" @change.prevent="(event) => edit(event, order.id)" v-bind:class=" {
                                'badge-primary': order.status === 'complete',
                                'badge-danger': order.status === 'canceled',
                                'badge-success': order.status === 'active'
                            }">
                            <option v-bind:selected="order.status === 'complete'" value="complete">Complete</option>
                            <option v-bind:selected="order.status === 'active'" value="active">Active</option>
                            <option v-bind:selected="order.status === 'canceled'" value="canceled">Canceled</option>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="pb-5">
        <pagination :data="orders" @pagination-change-page="getOrders" class="d-flex" align="center"></pagination>
    </div>
</section>
<Spinner v-else></Spinner>
</template>

<script>
import Spinner from "./Spinner";
import Product from "./Product";
export default {
    name: "Orders",
    components: {
        Spinner
    },
    data() {
        return {
            orders: null,
            search_value: '',
            filter: '',
        }
    },
    mounted() {
        this.getOrders(1)
    },
    methods: {
        cancel() {
            this.search_value = ''
            this.getOrders(1)
        },
        edit(event, id) {
            axios.patch('/api/orders/'+id, {'status':event.target.value})
            .then(res => {
                this.getOrders(this.orders.current_page)
            })
            .catch(err => {
                console.log(err)
            })
        },
        normal_date(order) {
            let date = new Date(order.created_at)
            let year = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(date);
            let month = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(date);
            let day = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(date);
            let hour = new Intl.DateTimeFormat('ru', { hour: '2-digit' }).format(date);
            let minute = new Intl.DateTimeFormat('en', { minute: '2-digit' }).format(date);

            return `${day}.${month}.${year} ${hour}:${minute}`
        },
        getOrders(page = 1) {
            this.filter = this.search_value
            let query = ''
            if (this.filter) query ='&search_field='+this.filter
            axios.defaults.headers['Authorization'] = `Bearer ${this.$cookie.get('token')}`;
            axios.get('/api/orders?page='+page+query, {},)
                .then(res => {
                    this.orders = res.data
                })
                .catch(err => {
                    console.log(err)
                })
        }

    }
}
</script>

<style scoped>
.no_change {
    background-color: transparent;
    border:none;
    outline: none
}
.order_select {
    appearance: none;
    text-align-last: center;
}
</style>
