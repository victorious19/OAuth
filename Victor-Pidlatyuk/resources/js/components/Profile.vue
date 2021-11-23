<template>
<div  class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item d-none d-sm-inline-block">
                    <button @click.prevent="$router.push('/')" class="btn m-2">Client page</button>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <button class="btn m-2" @click="logout">Logout</button>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <p class="brand-link">
                <img src="/dist/img/AdminLTELogo.png" alt="GoodShop Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">GoodShop</span>
            </p>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                        <li class="nav-item">
                            <router-link to="/profile" class="nav-link">
                                <i class="nav-icon fas  fa-home"></i>
                                <p>
                                    Home
                                    <i class="right fas fa-angle-left" v-if="$route.path === '/profile'"></i>
                                </p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/orders" class="nav-link">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Shop
                                    <i class="right fas fa-angle-left" v-if="$route.path === '/orders'"></i>
                                </p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/profile/products" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    My Products
                                    <i class="right fas fa-angle-left" v-if="$route.path === '/profile/products' || $route.path === '/create-product' || $route.name === 'change-product'"></i>
                                </p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/profile/categories" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Categories
                                    <i class="right fas fa-angle-left" v-if="$route.path === '/profile/categories'"></i>
                                </p>
                            </router-link>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menumenu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <router-view></router-view>
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
</div>
</template>

<script>
export default {
    name: "Profile",
    mounted() {
        // alert(this.$cookie.get('token'))
        if(!(this.$cookie.get('token') && this.$cookie.get('id'))) {
            this.$router.push('/login')
        }
    },
    methods: {
        logout() {
            this.$cookie.delete('token')
            this.$cookie.delete('id')
            this.$router.push('/login')
        }
    }
}
</script>

<style scoped>

</style>
