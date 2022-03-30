<template>
<div class="auth-wrapper">
	<div class="auth-content text-center">
		<img src="assets/images/logo.png" alt="" class="img-fluid mb-4">
		<div class="card borderless">
			<div class="row align-items-center ">
				<div class="col-md-12">
					<div class="card-body">
						<h4 class="mb-3 f-w-400">Signin {{sitename}}</h4>
						<hr>
						<div class="form-group mb-3">
							<input type="email" class="form-control" id="Email" placeholder="Email address" v-model="loginData.email">
						</div>
						<div class="form-group mb-4">
							<input type="password" class="form-control" id="Password" placeholder="***********" v-model="loginData.pass">
						</div>
						<div class="custom-control custom-checkbox text-left mb-4 mt-2">
							<input type="checkbox" class="custom-control-input" id="customCheck1">
							<label class="custom-control-label" for="customCheck1">Save credentials.</label>
						</div>

						<button class="btn btn-block btn-primary mb-4" @click="login" :disabled="isLogging" >{{ isLogging ? 'Signing...' : 'Signin'}}</button>
						<hr>
						<p class="mb-2 text-muted">Forgot password? <a href="auth-reset-password.html" class="f-w-400">Reset</a></p>
						<p class="mb-0 text-muted">Don’t have an account? <a href="auth-signup.html" class="f-w-400">Signup</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</template>

<script>
import { mapState,mapActions } from 'vuex';
import SignupValidations from '../services/SignupValidations';
import {LOGIN_ACTION} from '../store/storeconstants';
export default {
    data() {
        return {
            loginData : {
                email : '',
                pass: ''
            },
            errors : [],
            isLogging : false,
        }
    },
    methods: {
        ...mapActions('auth',{
            loginAction: LOGIN_ACTION
        }),
       login() {
          //https://www.youtube.com/c/LeelaWebDev/videos
          let validations = new SignupValidations(this.loginData.email,this.loginData.pass);
          this.errors = validations.checkValidations();

          if(this.errors.length) {

             for(let dd in this.errors){
                    return this.error(this.errors[dd].msg);
              };
          }
                /* if (this.loginData.email.trim() == '') {
                    return this.error('Email is required');
                }
                if (this.loginData.pass.trim() == '') {
                    return this.error('Password is required');
                }
                if (this.loginData.pass.length < 6) {
                    return this.error('Incorrect Login Details');
                } */
                this.isLogging = true;
               this.loginAction({method : 'post', URL : 'app/login', data : this.loginData});
              // this.$store.loginData({method : 'post', URL : 'app/login', data : this.loginData});
               // const res = await this.callApi('post','app/login',this.loginData);
 if (1) {
               // if (res.status == 200) {
                   /*  this.$store.commit('setUser',{'email' : res.data.user.email,'fullName' : res.data.user.fullName });
                   // this.$store.commit('updateUser',this.user);
                    this.$store.commit('SetUserPermission',res.data.user.role);
                    console.log(this.isLoggedIn) */
                //   this.$router.push('/dashboard');
                //  window.location = '/dashboard';

                   // this.success(res.data.msg);
                }
                else {
                    if (res.status == 401) {
                             this.error(res.data.msg);
                    } else if(res.status == 422) {

                            for(let e in res.data.errors) {
                                     this.error(res.data.errors[e][0]);
                            }
                    }
                    else {
                          this.error();
                    }
                }
                this.isLogging = false;
       }
    },
    computed : {
        //auth is the module name
        ...mapState('auth', {
            //you can write anything instead of site name
            sitename : (state) => state.name
        }),
    }

}
</script>

<style>

</style>
