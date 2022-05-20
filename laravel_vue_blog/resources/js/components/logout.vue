<script>
import {mapActions} from 'vuex';
import { LOGOUT_ACTION } from '../store/storeconstants'
export default {
    name : logout,
    methods : {
        ...mapActions('auth', {
             logout: LOGOUT_ACTION,
        }),
        logout() {
           // console.log('logout');return
            this.logout({method : 'post', URL : 'app/logout'});
            this.$router.push('/login');
        }
},
    mounted() {

    },
    created() {
        console.log('sacascascascascsac');
    }
}
</script>

<style>

</style>
