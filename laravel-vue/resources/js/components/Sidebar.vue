<template>
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <li class="nav-item">
                        <router-link to="/home" class="nav-link sb-nav-link-icon" exact>
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            <span>Dashboard</span>
                        </router-link>
                    </li>
                  <li class="nav-item" v-if="$store.state.profile.role === 'admin'">
                      <router-link to="/home/Categories" class="nav-link sb-nav-link-icon" exact>
                          <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                          <span>Categories</span>
                      </router-link>
                  </li>
                    <li class="nav-item" v-if="$store.state.profile.role === 'user' || $store.state.profile.role === 'admin'">
                      <router-link to="/home/Products" class="nav-link sb-nav-link-icon" exact>
                          <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                          <span>Products</span>
                      </router-link>
                  </li>
                    <li class="nav-item" v-if="$store.state.profile.role === 'admin'">
                      <router-link to="/home/To-Do-List" class="nav-link sb-nav-link-icon" exact>
                          <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                          <span>To Do List</span>
                      </router-link>
                  </li>

                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:</div>
                Start Bootstrap
            </div>
        </nav>
    </div>
</template>

<script>
export default {
    name: "Sidebar"
}
</script>

<style scoped>

</style>
