<template>
<div v-if="user">
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Profile</h1>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">

                <!-- Profile Image -->
                <div class="card">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="img-fluid img-thumbnail" id="profile_avatar"
                                 v-bind:src="'/img/users/'+user.avatar"
                                 alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center text-break p-2">{{user.login}}</h3>


                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Email</b> <p class="float-right text-break">{{user.email}}</p>
                            </li>
                            <li class="list-group-item">
                                <b>Full name</b> <p class="float-right text-break">{{user.full_name}}</p>
                            </li>
                        </ul>
                        <button type="button" class="btn btn-outline-secondary mb-3 col-md-12" data-bs-toggle="modal" data-bs-target="#myModal">
                            Change password
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Change password</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" @submit.prevent="change_password">
                                        <div class="form-group row">
                                            <label for="inputPassword" class="col-sm-4 col-form-label">Password</label>
                                            <div class="col-sm-7">
                                                <input type="password" class="form-control" id="inputPassword" placeholder="Password" v-model="password" v-bind:class="{'is-invalid':errors.password}">
                                                <div class="invalid-feedback" v-if="errors.password">{{errors.password[0]}}</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword1" class="col-sm-4 col-form-label">Repeat password</label>
                                            <div class="col-sm-7">
                                                <input type="password" class="form-control" id="inputPassword1" placeholder="Repeat password" v-model="password_confirmation" >
                                            </div>
                                        </div>
                                        <div class="alert alert-success" role="alert" v-if="success === '2'">
                                            Successful change
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" @click.prevent="change_password">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->

            <div class="col-md-7" >
                <div class="card">
                    <div class="card-body">
                        <form class="form-horizontal" @submit.prevent="edit">
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 col-form-label">Nickname</label>
                                <div class="col-sm-10">
                                    <input class="form-control" id="inputName" placeholder="Nickname" v-model="login" v-bind:class="{'is-invalid':errors.login}">
                                    <div class="invalid-feedback" v-if="errors.login">{{errors.login[0]}}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="inputEmail" placeholder="Email" v-model="email" v-bind:class="{'is-invalid':errors.email}">
                                    <div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Full name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputName2" placeholder="Full name" v-model="full_name" pattern="([a-zA-Zа-яА-Я]+\s){1,2}([a-zA-Zа-яА-Я]+)" v-bind:class="{'is-invalid':errors.full_name}">
                                    <div class="invalid-feedback" v-if="errors.full_name">{{errors.full_name[0]}}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputAvatar" class="col-sm-2 col-form-label">Avatar</label>
                                <div class="col-sm-10">
                                    <input type="file" id="inputAvatar" class="form-control" accept=".png, .jpg" @change.prevent="upload" v-bind:class="{'is-invalid':errors.avatar}" >
                                    <div class="invalid-feedback" v-if="errors.avatar">{{errors.avatar[0]}}</div>
                                </div>
                            </div>
                            <div class = "alert alert-danger" v-if="errors.main">
                                <a class="close" data-dismiss="alert"> × </a>
                                <strong> Error! </strong> Server error.
                            </div>
                            <div class="alert alert-success" role="alert" v-if="success === '1'">
                                Successful change
                            </div>
                            <div class="form-group row float-right mr-1">
                                <button type="submit" class="btn btn-danger" @submit.prevent="edit">Submit</button>
                            </div>
                        </form>
                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
</div>
<Spinner v-else></Spinner>
</template>

<script>
import Spinner from "./Spinner"
export default {
    name: "Home",
    components: {
        Spinner
    },
    data() {
        return {
            errors: {main:''},
            user: null,
            login: '',
            email: '',
            full_name: '',
            password: '',
            password_confirmation: '',
            formData: new FormData(),
            success: false
        }
    },
    mounted() {
        if(this.$route.query.token && this.$route.query.token) {
            this.$cookie.set('token', this.$route.query.token)
            this.$cookie.set('id', this.$route.query.id)
            this.$router.replace('/profile')
        }
        if(this.$cookie.get('token') && this.$cookie.get('id')) {
            axios.defaults.headers['Authorization'] = `Bearer ${this.$cookie.get('token')}`;
            axios.get('/api/users/'+this.$cookie.get('id'), {},)
                .then(res => {
                    this.user = res.data
                })
                .catch(err => {
                    this.$cookie.delete('token')
                    this.$cookie.delete('id')
                    this.$router.push('/login')
                })
        }
        else this.$router.push('/login')
    },
    methods: {
        edit: function () {
            this.errors = {main: ''}
            if (this.login && this.login !== this.user.login) this.formData.append('login', this.login)
            if (this.email && this.email !== this.user.email) this.formData.append('email', this.email)
            if (this.full_name && this.login !== this.user.login) this.formData.append('full_name', this.full_name)
            axios.defaults.headers['Authorization'] = `Bearer ${this.$cookie.get('token')}`;
            axios.post('/api/users/' + this.user.id, this.formData)
                .then(res => {
                    this.errors = {main: ""}
                    this.user = res.data
                    this.formData = new FormData()
                    this.email = ''
                    this.login = ''
                    this.full_name = ''
                    document.getElementById('inputAvatar').value = ''
                    this.success = '1'
                })
                .catch(err => {
                    this.formData = new FormData()
                    this.success = false
                    if (err.response.data.errors) this.errors = err.response.data.errors
                    else if (err.response.data.message) this.errors.main = err.response.data.message
                    else this.errors.main = err.response.statusText
                })
        },
        upload(event) {
            this.formData.append('avatar', event.target.files[0])
        },
        change_password() {
            axios.defaults.headers['Authorization'] = `Bearer ${this.$cookie.get('token')}`;
            axios.post('/api/users/' + this.user.id, {
                'password': this.password,
                'password_confirmation': this.password_confirmation
            })
                .then(res => {
                    this.errors = {main: ""}
                    this.success = '2'
                })
                .catch(err => {
                    this.success = false
                    if (err.response.data.errors) this.errors = err.response.data.errors
                    else if (err.response.data.message) this.errors.main = err.response.data.message
                    else this.errors.main = err.response.statusText
                })
        }
    }
}
</script>

<style scoped>

</style>
