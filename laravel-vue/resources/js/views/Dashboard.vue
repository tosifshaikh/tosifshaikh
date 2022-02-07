<template>
    <main>
        <div class="container-fluid px-4">
            <ol class="breadcrumb mt-4">
                <li class="breadcrumb-item active">
                    <router-link to="/home">
                        Dashboard
                    </router-link>
                </li>
                <li class="breadcrumb-item">
                    Overview
                </li>
            </ol>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <button class="btn btn-primary" v-on:click="userScope">User Scope</button>
                </div>
                <div class="col-md-6 mb-4">
                    <button class="btn btn-danger" v-on:click="adminScope">Admin Scope</button>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">Primary Card</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="#">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">Warning Card</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="#">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">Success Card</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="#">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger text-white mb-4">
                        <div class="card-body">Danger Card</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="#">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</template>

<script>
import * as user from '../Services/user_service';
export default {
    name: "Welcome",
    methods : {
        userScope :async function() {
            try{
                const response = await user.userScope();
                console.log(response);
            }
            catch (e)
            {
                    console.log(e)
            }
        }, adminScope :async function() {
            try{
                const response = await user.adminScope();
                console.log(response);
            }
            catch (e)
            {
                    console.log(e)
            }
        }
    }
}
</script>

<style scoped>

</style>
