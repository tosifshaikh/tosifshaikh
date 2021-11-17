<template>
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                        <div class="card-header"><h3 class="text-center font-weight-light my-4">Password Recovery</h3></div>
                        <div class="card-body">
                            <div class="small mb-3 text-muted">Enter your email address and we will send you a link to reset your password.</div>
                            <form>
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="inputEmail" type="email" placeholder="name@example.com" />
                                    <label for="inputEmail">Email address</label>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                    <router-link to="/login" class="btn btn-primary" >Login</router-link>
<!--                                    <router-link to="/reset-password" class="btn btn-primary">Forgot Password?</router-link>-->
                                    <a class="btn btn-primary" href="login.html">Reset Password</a>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center py-3">
                            <router-link to="/register" class="small" >Need an account? Sign up!</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    name: "ResetPassword",
    created() {
        document.querySelector('body').style.backgroundColor = '#343a40';
    }
}
</script>

<style scoped>

</style>