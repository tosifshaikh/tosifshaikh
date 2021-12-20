<template>
    <div class="bg-white shadow rounded px-3 pt-3 pb-5 border border-white">
        <div class="flex align-top float-right">
<!--            <button class="float-xl-right" ><span class="fas fa-trash-alt" ></span></button>-->
            <span class="text-sm text-gray-600">{{formatDate(task.updated_at)}}</span>
            </div>
        <div class="card-title">
            <badge v-if="task.priority" :color="badgeColor"></badge>
            <p class="text-gray-700 font-semibold font-sans tracking-wide text-sm">{{task.title}}</p>
        </div>
        <div class="flex justify-content-lg-end">





        </div>

        <div class="flex mt-4 justify-between items-start-l">
            <img
                    class="w-6 h-6 rounded-full ml-3"
                    src="https://pickaface.net/gallery/avatar/unr_sample_161118_2054_ynlrg.png"
                    alt="Avatar"
            >

        </div>

    </div>
</template>
<script>
import Badge from "./Badge";
export default {
    components: {
        Badge
    },
    props: {
        task: {
            type: Object,
            default: () => ({})
        }
    },
    computed: {
        badgeColor() {
            const mappings =
            {
                1 : {color : 'badge badge-danger', name : 'High'},
                2 : {color : 'badge badge-warning', name: 'Medium'},
                3 : {color : 'badge badge-primary', name : 'Low'}
                }
           /* const mappings = {
                Design: "purple",
                "Feature Request": "teal",
                Backend: "blue",
                QA: "green",
                default: "teal"
            };*/
            return mappings[this.task.priority] || mappings.default;
        }
    }
};
</script>


<style scoped>

</style>