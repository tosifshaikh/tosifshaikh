<template>
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                        <div class="card-header"><h3 class="text-center font-weight-light my-4">Password Recovery</h3></div>
                        <div class="card-body">
                            <div class="small mb-3 text-muted">Enter your email address and we will send you a link to reset your password.</div>
                            <form v-on:submit.prevent="onSubmit">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="inputEmail" type="email" placeholder="name@example.com" v-model="user.email" />
                                    <label for="inputEmail">Email address</label>
                                    <div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                    <router-link to="/login" class="btn btn-primary" >Login</router-link>
                                    <button type="submit" class="btn btn-primary" ref="btnSubmit">Reset Password</button>
<!--                                <router-link to="/reset-password" class="btn btn-primary">Forgot Password?</router-link>-->
<!--                                    <a class="btn btn-primary" href="login.html">Reset Password</a>-->
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
import * as auth from '../../Services/auth_service';
export default {
    name: "ResetPassword",
    data() {
        return {
            user : {
                name : ''
            },
            errors : {},
        }

    },
    created() {
        document.querySelector('body').style.backgroundColor = '#343a40';
    },
    methods : {
        onSubmit :async function() {

                try {
                    this.errors = {};
                    this.disableSubmission(this.$refs.btnSubmit);
                    const response = await auth.resetPasswordRequest(this.user);
                    this.flashMessage.success({
                        message: response.data.message ,
                        time: 5000,
                        blockClass: 'custom-block-class'
                    });
                    this.$router.push({name : ''});
                }
                catch (e) {

                     switch (e.response.status) {

                         case 422:
                             this.errors = e.response.data.errors;
                             break;
                         default:
                             this.flashMessage.error({
                                 message: 'Some error Occured',
                                 time: 5000,
                                 blockClass: 'custom-block-class'
                             });
                             break;

                     }
                    this.enableSubmission(this.$refs.btnSubmit);

                }
        },
        disableSubmission(btn) {
            btn.setAttribute('disabled','disabled');
            this.btnOldHtml = btn.innerHtml;
            btn.innerHtml = '<span class="fa fa-spinner fa-spin">Please Wait</span>';
        },
        enableSubmission(btn) {
            btn.removeAttribute('disabled');
            btn.innerHtml = this.btnOldHtml;
        }

    }
}
</script>

<style scoped>

</style>
