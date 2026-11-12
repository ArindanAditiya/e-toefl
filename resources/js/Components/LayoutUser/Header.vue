<template>
    <!--start header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white rounded fixed-top rounded-0 shadow-sm">
        <div class="container-fluid">
            <Link class="navbar-brand" href="/user/dashboard">
                <!-- <img src="/assets/images/logo-img.png" width="140" alt="" /> -->
            </Link>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent1">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"> <Link class="nav-link" aria-current="page" href="/user/dashboard"><i class='bx bx-home-alt me-1'></i>Home</Link></li>
                    <li class="nav-item"> <Link class="nav-link" href="/logout" method="POST"><i class='bx bx-log-out-circle'></i>Logout</Link></li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    // import link
    import { Link } from '@inertiajs/inertia-vue3';

    export default {
        // register component
        components: {
            Link
        }
    }
</script>

<style>

</style>
