<template>
    <div class="min-vh-100 d-flex flex-row align-items-center bg-secondary">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <span class="display-1 d-block">404</span>
                    <div class="mb-4 lead">The page you are looking for was not found.</div>
                    <button @click.prevent="$router.go(-1)" class="btn btn-link">Back</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
}
</script>
