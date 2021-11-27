<template>
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                        <div class="card-header"><h3 class="text-center font-weight-light my-4">Reset Your Password</h3>
                        </div>
                        <div class="card-body">
                            <form v-on:submit.prevent="onSubmit">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="email" type="email" placeholder="name@example.com"
                                           v-model="user.email" required="required"/>
                                    <label for="email">Email address</label>
                                    <div class="invalid-feedback" v-if="errors.email">{{ errors.email[0] }}</div>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="verificationCode" type="number"
                                           placeholder="Enter verification code" v-model="user.verificationCode"
                                           required="required" autofocus="autofocus"/>
                                    <label for="verificationCode">Enter verification code</label>
                                    <div class="invalid-feedback" v-if="errors.verificationCode">
                                        {{ errors.verificationCode[0] }}
                                    </div>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="password" type="password"
                                           placeholder="Enter New Password" v-model="user.password"
                                           required="required"/>
                                    <label for="password">Enter New Password</label>
                                    <div class="invalid-feedback" v-if="errors.password">{{ errors.password[0] }}</div>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="password_confirmation" type="password"
                                           placeholder="Confirm Password" v-model="user.password_confirmation"
                                           required="required" />
                                    <label for="password_confirmation">Confirm Password</label>
                                    <div class="invalid-feedback" v-if="errors.password_confirmation">
                                        {{ errors.password_confirmation[0] }}
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                    <router-link to="/login" class="btn btn-primary">Login</router-link>
                                    <button type="submit" class="btn btn-primary" ref="btnSubmit">Reset Password
                                    </button>
                                    <!--                                <router-link to="/reset-password" class="btn btn-primary">Forgot Password?</router-link>-->
                                    <!--                                    <a class="btn btn-primary" href="login.html">Reset Password</a>-->
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center py-3">
                            <router-link to="/reset-password-request" class="small">Resend Verification Code</router-link>
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
            user: {
                email: '',
                password: '',
                password_confirmation: '',
                verificationCode: '',
            },
            btnOldHtml: '',
            errors: {},
        }

    },
    created() {
        document.querySelector('body').style.backgroundColor = '#343a40';
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            vm.user.email = to.params.email
        });
    },
    methods: {
        onSubmit: async function () {

            try {
                this.errors = {};
                this.disableSubmission(this.$refs.btnSubmit);
                const response = await auth.resetPassword(this.user);
                this.flashMessage.success({
                    message: response.data.message,
                    time: 5000,
                    blockClass: 'custom-block-class'
                });
                this.$router.push('/login');
            } catch (e) {

                switch (e.response.status) {

                    case 422:
                        this.errors = e.response.data.errors;
                        break;
                    case 401:
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
            btn.setAttribute('disabled', 'disabled');
            this.btnOldHtml = btn.innerHTML;
            btn.innerHTML = '<span class="fa fa-spinner fa-spin">Please Wait</span>';
        },
        enableSubmission(btn) {
            btn.removeAttribute('disabled');
            btn.innerHTML = this.btnOldHtml;
        }

    }
}
</script>

<style scoped>

</style>
