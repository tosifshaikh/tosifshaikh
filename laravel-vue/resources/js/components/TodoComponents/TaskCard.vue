<template>
    <div class="bg-white shadow rounded px-3 pt-3 pb-5 border border-white">


        <div class="card-title">
            <p class="text-gray-700 font-semibold font-sans tracking-wide text-sm">{{task.title}}</p>

            <div class="dropdown-divider"></div>
            <badge v-if="task.priority" :color="badgeColor"></badge>
            <div class="flex align-top float-right">
            <!--            <button class="float-xl-right" ><span class="fas fa-trash-alt" ></span></button>-->

                <img
                        class="w-6 h-6 rounded-full ml-3"
                        src="https://pickaface.net/gallery/avatar/unr_sample_161118_2054_ynlrg.png"
                        alt="Avatar"
                >
        </div>
        </div>

        <div class="flex justify-content-lg-end">

        </div>

        <div class="flex mt-4 justify-between items-start-l">
            <span class="text-sm text-gray-600">{{formatDate(task.updated_at)}}</span>
        <Label v-if="task.type" :data="labelText"></Label>

        </div>
        <div class="dropdown-divider"></div>
        <div class="dropdown float-right">
            <button class="" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Click">
                <span class="fas fa-ellipsis-v text-gray-600"></span>
            </button>
            <div class="dropdown-menu " aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item text-muted text-sm" href="#"  @click="$emit('click-me','editTask')">  <span class="fas fa-edit mr-2"></span>Edit</a>
                <a class="dropdown-item text-muted text-sm" href="#">  <span class="fas fa-trash-alt mr-2"></span>Delete</a>

            </div>
        </div>

    </div>
</template>
<script>
import Badge from "./Badge";
import Label from "./Label";
export default {
    components: {
        Badge, Label
    },
    props: {
        task: {
            type: Object,
            default: () => ({})
        },
       // showEditTaskModal :Function,
      //  hideEditTaskModal : Function

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
        },
        labelText() {
            const type = {
                1 : {color : 'red', name : 'Bug'},
                2 : {color : 'green', name : 'Features'},

            }
            return type[this.task.type];
        }
    }
};
</script>


<style scoped>

</style>
