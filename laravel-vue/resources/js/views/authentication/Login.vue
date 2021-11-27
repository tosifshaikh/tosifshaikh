<template>
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                        <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                        <div class="card-body">
                            <form v-on:submit.prevent="login">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="email" type="text" placeholder="name@example.com" v-model="user.email" />
                                    <label for="email">Email address</label>
                                    <div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="password" type="password" placeholder="Password" v-model="user.password"/>
                                    <label for="password">Password</label>
                                    <div class="invalid-feedback" v-if="errors.password">{{errors.password[0]}}</div>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" id="remember-me" type="checkbox" v-model="user.remember_me" />
                                    <label class="form-check-label" for="remember-me">Remember Password</label>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                    <router-link to="/reset-password-request" class="small" >Forgot Password?</router-link>
<!--                                    <a class="small" href="password.html">Forgot Password?</a>-->
                                        <button type="submit" class="btn btn-primary">Login</button>
                                      <!--  <router-link to="/login" class="btn btn-primary" >Login</router-link>-->
    <!--                                    <a class="btn btn-primary" href="index.html">Login</a>-->
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center py-3">
                            <router-link to="/register" class="small" >Need an account? Sign up!</router-link>
<!--                            <div class="small"><a href="register.html">Need an account? Sign up!</a></div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import * as auth from '../../Services/auth_service'
export default {
    name: "Login",
    created() {
        document.querySelector('body').style.backgroundColor = '#343a40';
    },
    data() {
        return {
            user : {
                email : '',
                password : '',
                remember_me : false
            },
            errors : {}
        }
    },
    methods : {
        login : async function() {
            try {

                const response = await auth.login(this.user);
                this.errors = {};
                this.$router.push('/home');
            }
            catch (e) {

               /* switch (e.response.status) {

                    case 422:
                        this.errors = e.response.data.errors;
                        break;
                    case 500:
                        this.flashMessage.error({
                            message: e.response.data.message,
                            time: 5000,
                        });
                        break;
                    case 401:
                        this.flashMessage.error({
                            message: e.response.data.message,
                            time: 5000,
                        });
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Some error Occured',
                            time: 5000,
                        });
                        break;

                }*/

            }
        }
    }
}
</script>

<style scoped>


</style>

