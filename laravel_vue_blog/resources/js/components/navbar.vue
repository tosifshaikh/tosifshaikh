<template>


	<!-- [ Pre-loader ] End -->
	<!-- [ navigation menu ] start -->
	<nav class="pcoded-navbar  ">
		<div class="navbar-wrapper  ">
			<div class="navbar-content scroll-div " >

				<div class="">
					<div class="main-menu-header">
						<img class="img-radius" src="assets/images/user/avatar-2.jpg" alt="User-Profile-Image">
						<div class="user-details">
							<span>John Doe</span>
							<div id="more-details">UX Designer<i class="fa fa-chevron-down m-l-5"></i></div>
						</div>
					</div>
					<div class="collapse" id="nav-user-link">
						<ul class="list-unstyled">
							<li class="list-group-item"><a href="user-profile.html"><i class="feather icon-user m-r-5"></i>View Profile</a></li>
							<li class="list-group-item"><a href="#!"><i class="feather icon-settings m-r-5"></i>Settings</a></li>
							 <li class="list-group-item" ><a href="#" @click.prevent="onLogout"><i class="feather icon-log-out m-r-5"></i>Logout</a></li>
<!--							<li class="list-group-item" ><router-link to="/logout"><i
                                class="feather icon-log-out m-r-5"></i>Logout</router-link></li>-->
						</ul>
					</div>
				</div>

				<ul class="nav pcoded-inner-navbar ">
					<!-- <li class="nav-item pcoded-menu-caption">
						<label>Navigation</label>
					</li> -->
					<!-- <li class="nav-item" v-for="(menuItem,i) in permission" :key="i" v-if ="countPermission && menuItem.read">
						<router-link :to="menuItem.name" class="nav-link">
								<span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">{{menuItem.resourceName}}</span>
						</router-link>


					</li> -->
					<!-- <li class="nav-item" v-for="(menuItem,i) in permission" :key="i" v-if ="menuItem.read">
						<router-link :to="menuItem.name" class="nav-link">
								<span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">{{menuItem.resourceName}}</span>
						</router-link>


					</li> -->

				<!-- 	<li class="nav-item">


					     <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-layout"></i></span><span class="pcoded-mtext">Page layouts</span></a>
					    <ul class="pcoded-submenu">
					        <li><a href="layout-vertical.html" target="_blank">Vertical</a></li>
					        <li><a href="layout-horizontal.html" target="_blank">Horizontal</a></li>
					    </ul>
					</li> -->
					 <li class="nav-item pcoded-menu-caption">
						<label>Admin</label>
					</li>

					<li class="nav-item pcoded-hasmenu">
						<a  class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Master</span></a>
						<ul class="pcoded-submenu">
							<li><router-link to="/tags" class="nav-link">
								<span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Tags</span>
						</router-link></li>
							<li><router-link to="/category" class="nav-link">
								<span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Category</span>
						</router-link></li>
                        <li><router-link to="/admin-users" class="nav-link">
								<span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Admin Users</span>
						</router-link></li>

						</ul>
					</li>

					<li class="nav-item pcoded-menu-caption">
					    <label>Permissions</label>
					</li >
                     <li  class="nav-item"><router-link to="/role-management" class="nav-link">
								<span class="pcoded-micon"><i class="feather icon-lock"></i></span><span class="pcoded-mtext">Role</span>
						</router-link></li>
                         <li  class="nav-item"><router-link to="/assign-roles" class="nav-link">
								<span class="pcoded-micon"><i class="feather icon-lock"></i></span><span class="pcoded-mtext">Assign Roles</span>
						</router-link></li>
					<li class="nav-item">
					    <a href="form_elements.html" class="nav-link "><span class="pcoded-micon"><i class="feather icon-file-text"></i></span><span class="pcoded-mtext">Forms</span></a>
					</li>
					<li class="nav-item">
					    <a href="tbl_bootstrap.html" class="nav-link "><span class="pcoded-micon"><i class="feather icon-align-justify"></i></span><span class="pcoded-mtext">Bootstrap table</span></a>
					</li>
					<li class="nav-item pcoded-menu-caption">
						<label>Chart & Maps</label>
					</li>
					<li class="nav-item">
					    <a href="chart-apex.html" class="nav-link "><span class="pcoded-micon"><i class="feather icon-pie-chart"></i></span><span class="pcoded-mtext">Chart</span></a>
					</li>
					<li class="nav-item">
					    <a href="map-google.html" class="nav-link "><span class="pcoded-micon"><i class="feather icon-map"></i></span><span class="pcoded-mtext">Maps</span></a>
					</li>
					<li class="nav-item pcoded-menu-caption">
						<label>Pages</label>
					</li>
					<li class="nav-item pcoded-hasmenu">
					    <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-lock"></i></span><span class="pcoded-mtext">Authentication</span></a>
					    <ul class="pcoded-submenu">
					        <li><a href="auth-signup.html" target="_blank">Sign up</a></li>
					        <li><a href="auth-signin.html" target="_blank">Sign in</a></li>
					    </ul>
					</li>
					<li class="nav-item"><a href="sample-page.html" class="nav-link "><span class="pcoded-micon"><i class="feather icon-sidebar"></i></span><span class="pcoded-mtext">Sample page</span></a></li>

				</ul>

				<!-- <div class="card text-center">
					<div class="card-block">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<i class="feather icon-sunset f-40"></i>
						<h6 class="mt-3">Upgrade To Pro</h6>
						<p>Please contact us on our email for need any support</p>
						<a href="https://1.envato.market/PgJNQ" target="_blank" class="btn btn-primary btn-sm text-white m-0">Upgrade</a>
					</div>
				</div> -->

			</div>
		</div>
	</nav>

</template>

<script>
import {mapGetters,mapActions} from 'vuex';
import { IS_USER_AUTHENTICATE_GETTER, LOGOUT_ACTION } from '../store/storeconstants';
import store from '../store/store';
export default {
    name : "Navbar",
     // props: ['permission'],
      created() {

      },computed: {
          ...mapGetters('auth', {
              isAuthenticated: IS_USER_AUTHENTICATE_GETTER,
          }),
		  /* countPermission() {
			  return this.permission.length;
		  },
		  readPermission(){
			   return this.permission.filter(x => x.read === true)
		  } */
      },
      methods : {
           ...mapActions('auth', {
              logout: LOGOUT_ACTION,
          }),
          async onLogout() {
               console.log('onLogout');
              await this.logout({method : 'post', URL : 'app/logout'});

              //this.$router.push('/login');
          }
      },
     /*  mounted() {
          console.log(this.isAuthenticated,'isAuthenticated')
      } */
}
</script>

<style>

</style>
