<template>
<main>
    <router-view></router-view>
        <FlashMessage position="right top"></FlashMessage>

</main>

</template>

<script>
import * as auth from './Services/auth_service'
export default {
    name: "App",
    beforeCreate : async function() {
        try {
            if (auth.isLoggedIn()) {
                const reponse = await auth.getProfile();
                this.$store.dispatch('authenticate',reponse.data.name);
            }
        }catch (e) {
                auth.logout();
        }
    }
}
</script>

<style scoped>

</style>
