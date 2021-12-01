<template>
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                        <div class="card-header"><h3 class="text-center font-weight-light my-4">{{ translate('Create Account') }}</h3></div>
                        <div class="card-body">
                            <form v-on:submit.prevent="register">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 mb-md-0">
                                            <input class="form-control" id="name" type="text" v-model="user.name" :placeholder="[[  translate('Enter Full name') ]]" />
                                            <label for="name">{{  translate('Enter Full name') }}</label>
                                            <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input class="form-control" id="email" type="email" v-model="user.email" placeholder="name@example.com" />
                                            <label for="email">{{ translate('Email Address') }}</label>
                                            <div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 mb-md-0">
                                            <input class="form-control" id="password" type="password" v-model="user.password" placeholder="Create a password" />
                                            <label for="password">{{  translate('Password')}}</label>
                                            <div class="invalid-feedback" v-if="errors.password">{{errors.password[0]}}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 mb-md-0">
                                            <input class="form-control" id="password_confirmation" type="password" v-model="user.password_confirmation"  placeholder="Confirm password" />
                                            <label for="password_confirmation">{{ translate('Confirm Password') }}</label>
                                            <div class="invalid-feedback" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 mb-0">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-block">{{  translate('Create Account') }}</button>
<!--                                        <a class="btn btn-primary btn-block" href="login.html">Create Account</a>-->
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center py-3">
                            <router-link to="/login" class="small" >{{  translate('Have an account ? Go to login') }}</router-link>
<!--                            <div class="small"><a href="login.html">Have an account? Go to login</a></div>-->
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
    name: "Register"
    ,
    created() {
        document.querySelector('body').style.backgroundColor = '#343a40';
    },
    data() {
        return {
            user : {
                name : '',
                email : '',
                password : '',
                password_confirmation : ''
            },
            errors : {},
        }
    },
    methods : {
        register : async function() {
            try {
                await auth.register(this.user);
                this.errors = {};
                this.$router.push('/login');
            } catch (e) {
                switch (e.response.status) {
                    case 422:
                        this.errors = e.response.data.errors;
                        break;
                        case 500:
                            this.flashMessage.error({
                                message: e.response.data.message,
                                time: this.$store.state.MyAppConstants.get('TIME'),
                            });
                        break;
                        case 401:
                            this.flashMessage.error({
                                message: e.response.data.message,
                                time: this.$store.state.MyAppConstants.get('TIME'),
                            });
                        break;
                    default:
                        this.flashMessage.error({
                            message: this.translate('ErrorMSG'),
                            time: this.$store.state.MyAppConstants.get('TIME'),
                        });
                        break;
                }
                
            }
        }
    },
}
</script>
