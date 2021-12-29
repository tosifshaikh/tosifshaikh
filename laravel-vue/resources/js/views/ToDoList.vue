//https://codesandbox.io/s/animated-draggable-kanban-board-with-tailwind-and-vue-1ry0p?ref=madewithvuejs.com&file=/src/App.vue
<template>
 <div class="container mt-5">
     <div class="row">
         <div class="col form-inline">

         </div>
     </div>
 </div>
</template>
<template>
    <div class="container-fluid px-4">
        <ol class="breadcrumb mt-4">
            <li class="breadcrumb-item active">
                <router-link to="/">
                    Dashboard
                </router-link>
            </li>

            <li class="breadcrumb-item">
                To Do List
            </li>
        </ol>
        <div class="card mb-3">
            <div class="card-header d-flex">
              <span><i class="fas fa-clipboard-list"></i>
                    To Do List
              </span>
            </div>
            <div class="card-body">
                <div class="">
                    <div class="min-h-screen flex overflow-x-scroll py-12">
                        <div
                                v-for="(element,ind) in categories"
                                :key="element.id"
                                class="bg-gray-100 rounded-lg px-3 py-3 column-width rounded mr-4"
                        >
                            <div>
                                <p class="text-gray-700 font-semibold font-sans tracking-wide text-center"><span class="text-sm">{{element.category_name}}</span> <button class="btn btn-primary btn-sm ml-2" v-if="element.id == 1" @click="showAddTaskModal" title="Add Task"><span class="fa fa-plus" ></span></button></p>
                            </div>
                            <!-- Draggable component comes from vuedraggable. It provides drag & drop functionality -->

                            <draggable  :animation="200" ghost-class="ghost-card" group="tasks" class="cardClass" :move="changeOrder"  :key="ind" :list="element.tasks"  :id="element.id">
                                <!-- Each element from here will be draggable and animated. Note :key is very important here to be unique both for draggable and animations to be smooth & consistent. -->

                                <task-card
                                        v-for="(task) in element.tasks"
                                        :key="task.id"
                                        :task="task"
                                        class="mt-3 cursor-move"
                                        :id="task.id"
                                        @click-me="editTask(task)"
                                        @delete-me="deleteTask(task.id)"
                                ></task-card>

                                <!-- </transition-group> -->
                            </draggable>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!--            <div class="card-body">
                <div class="row" v-model="categories" >
                    <div class="col-md-3" v-for="element in categories" :key="element.category_id">
                        <div class="p-2 alert alert-secondary">
                            <div class="text-center "><h5>{{element.category_name}}

                                <button class="btn btn-primary btn-sm ml-2" v-if="element.category_id == 1" @click="showAddTaskModal"><span class="fa fa-plus" ></span></button></h5>

                            </div>
                        <draggable class="list-Group kanban-column"  group="tasks"  @end="changeOrder" v-model="element.tasks" :options="dragOptions" >
                               <transition-group :id="element.category_id">

                            <div class="list-group-item mb-3" v-for="task in element.tasks" :id="task.task_id" :key="task.task_id+','+task.category_id+','+task.order" >


                                <div class="float-right">
                                    <button class="btn btn-xs" v-on:click="editTask(task)"><span class="fa fa-edit" ></span></button>
                                </div>

                                <div class="card border-grey mb-3" style="max-width: 18rem;">

                                    <div class="card-header bg-transparent border-grey column">
                                        <div class="float-left ">
                                            <label>{{ task.title}}</label>
                                        </div>
                                        <div class="float-right mt-0 align-top">
                                            <img alt="" width="100" class="img-fluid avatar fas fa-user-circle">
                                        </div>
                                    </div>

                                    <div class="card-body">
                                     <span :class="priority[task.priority].color">{{priority[task.priority].name}}</span>

&lt;!&ndash;                                        <h5 class="card-title">Success card title</h5>&ndash;&gt;
                                        <p class="card-text text-truncate">{{task.Description}}</p>
                                    </div>
                                    <div class="card-footer bg-transparent border-grey row-0">
                                        <div class="column "><small class="text-muted ">{{time}}   Last updated 3 mins ago</small></div>
                                    </div>
                                </div>


                            </div>
                              </transition-group>
                            </draggable>

                        </div>
                    </div>
                </div>
-->
                <b-modal ref="TaskModal" hide-footer title="Add Task">
                    <div class="d-block">
                        <form v-on:submit.prevent="AddTask">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                <label for="title" class="form-label">Task Title</label>
                                <input type="text" class="form-control" id="title" placeholder="Enter Task Title" v-model="taskData.title">
                                    </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                <label for="description" class="form-label">{{ translate('Enter Description') }}</label>
                                <textarea
                                    class="form-control"
                                    name="description"
                                    id="description"
                                    v-model="taskData.description"
                                    :placeholder="[[translate('Enter Description')]]"
                                > </textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6"> <label for="priority" class="form-label">{{ translate('Choose Priority') }}</label>
                                    <select class="form-control"  id="priority" name="priority" v-model="taskData.priority">
                                        <option value="">Choose Priority</option>
                                        <option v-for="(priority,index) in priority" :value="index" :key="index">{{ priority.name }}</option>
                                    </select>
                                </div>
                                <div class="col-md-4"> <label for="Type" class="form-label">{{ translate('Choose Type') }}</label>
                                    <select class="form-control"  id="Type" name="Type" v-model="taskData.type">
                                        <option v-for="(taskType,index) in taskType" v-bind:value="taskType.value" :key="index" >{{ taskType.name }}</option>
                                    </select>
                                </div>

                            </div>
                            <div class="row mb-3">
                                <div class="col-md-8"> <label for="assignee" class="form-label">{{ translate('Assignee') }}</label>
                                    <select class="form-control"  id="assignee" name="assignee" v-model="taskData.user_id">
                                        <option value="">Choose Assignee</option>
                                        <option value=0>Unassign</option>
                                        <option value=1 >Tosif</option>
                                    </select>
                                </div>
                            </div>

                            <hr>
                            <div class="text-right">
                                <button type="button" class="btn btn-light" v-on:click="hideAddTaskModal"> Cancel</button>
                                <button type="submit" class="btn btn-primary" ><span class="fa fa-check"></span> Save Task</button>
                            </div>

                        </form>
                    </div>

                </b-modal>

                <b-modal ref="EditTaskModal" hide-footer title="Edit Task">
                    <div class="d-block">
                        <form v-on:submit.prevent="saveTaskData">
                            <div class="mb-3">
                                <label for="title" class="form-label">Task Title</label>
                                <input type="text" class="form-control" id="title" placeholder="Enter Task Title" v-model="editTaskData.title">
                                <div class="invalid-feedback" v-if="errors.title">{{errors.title[0]}}</div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ translate('Enter Description') }}</label>
                                <textarea
                                        class="form-control"
                                        name="description"
                                        id="description"
                                        v-model="editTaskData.Description"
                                        :placeholder="[[translate('Enter Description')]]"
                                > </textarea>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6"> <label for="priority" class="form-label">{{ translate('Choose Priority') }}</label>
                                    <select class="form-control"  id="priority" name="priority" v-model="editTaskData.priority">
                                        <option value="">Choose Priority</option>
                                        <option v-for="(priority,index) in priority" :value="index" :key="index">{{ priority.name }}</option>
                                    </select>
                                </div>
                                <div class="col-md-4"> <label for="Type" class="form-label">{{ translate('Choose Type') }}</label>
                                    <select class="form-control"  id="Type" name="Type" v-model="editTaskData.type">
                                        <option v-for="(taskType,index) in taskType" v-bind:value="taskType.value" :key="index" >{{ taskType.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-8"> <label for="assignee" class="form-label">{{ translate('Assignee') }}</label>
                                    <select class="form-control"  id="assignee" name="assignee" v-model="editTaskData.user_id">
                                        <option value="">Choose Assignee</option>
                                        <option value=0>UnAssign</option>
                                        <option value=1 >Tosif</option>
                                    </select>
                                </div>
                            </div>
                              <hr>
                            <div class="text-right">
                                <button type="button" class="btn btn-light" v-on:click="hideEditTaskModal"> Cancel</button>
                                <button type="submit" class="btn btn-primary" ><span class="fa fa-check"></span> Update Task</button>
                            </div>

                        </form>
                    </div>

                </b-modal>
            </div>



</template>
<script>
import draggable from 'vuedraggable';
import * as todoService from '../Services/todo_service';
import TaskCard from "../components/TodoComponents/TaskCard.vue";
export default {
    name: "ToDoList",
    components :{
        draggable,
        TaskCard,
    },
    beforeDestroy() {
        // prevent memory leak
        clearInterval(this.interval)
    },
    created() {
        // update the time every second
        this.interval = setInterval(() => {
            // Concise way to format time according to system locale.
            // In my case this returns "3:48:00 am"
            this.time = Intl.DateTimeFormat(navigator.language, {
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric'
            }).format()
        }, 1000)

    },
    computed:{
      count() {
          return (new Date().toLocaleString())

      },
        dragOptions () {
            return  {
                animation: 1,
                group: 'description',
                ghostClass: 'ghost'
            };
        },
    },
    data() {
        return {
            time : '',
            taskData :  this.resetData,
            editTaskData : this.EditData,
            errors : {},
            categories : [],
            priority : {
                1 : {color : 'badge badge-danger', name : 'High'},
                2 : {color : 'badge badge-warning', name: 'Medium'},
                3 : {color : 'badge badge-primary', name : 'Low'}
            },
            taskType : [
                    { value : 1 , name : 'Bug'},
                    { value : 2 , name : 'Features'}
            ]

        }
    },
    mounted() {
        this.getCategories();
    },

        methods : {
        resetData : function() {
                      return {
                          title: '',
                          description: '',
                          priority: '',
                          category_id: 1,
                          user_id: 1,
                          type: 1
                      }
        },
        EditData : function() {
            return  {
                title : '',
                description : '',
                priority : '',
                category_id : '',
                user_id : '',
                type : ''
            }

        },
        hideAddTaskModal() {
            this.$refs.TaskModal.hide();
        },
        hideEditTaskModal() {
            this.$refs.EditTaskModal.hide();
        },
        showAddTaskModal() {
            this.$refs.TaskModal.show();
        },
        showEditTaskModal() {
            this.$refs.EditTaskModal.show();
        },
        async AddTask() {
            let formData = new FormData();
            this.taskData.category_id = 1;
            formData.append('title', this.taskData.title);
            formData.append('categoryID', this.taskData.category_id);
            formData.append('description', this.taskData.description);
            formData.append('priority', this.taskData.priority);
            formData.append('type', this.taskData.type);
            formData.append('_method','POST');
            try
            {
                const response = await todoService.addList(formData);
                this.categories.map(category => {
                   if(category.id == response.data.data.category_id) {
                       return category.tasks.push(response.data.data);
                   }
                });

                this.flashMessage.success({
                    message: response.data.message,
                    time: this.$getConst('TIME'),
                    blockClass: 'custom-block-class'
                });
                this.taskData = this.resetData;
            }catch (e) {
                this.flashMessage.error({
                    message: this.translate(e),
                    time: this.$getConst('TIME'),
                    blockClass: 'custom-block-class'
                });
            }
           this.hideAddTaskModal();
        },
        async getCategories() {
            try{
                const response = await todoService.getToDolist();
                this.categories = response.data;
            }
            catch (e) {
                this.flashMessage.error({
                    message: this.translate(e),
                    time: this.$getConst('TIME'),
                    blockClass: 'custom-block-class'
                });
            }
        },
       async changeOrder(data) {
           let fromCategory = data.from.id;
           let toCategory = data.to.id;
           let draggedElement = data.draggedContext.element;
           let task_id = draggedElement.id;

            if (task_id !== null) {
                    try {
                        const response = await todoService.updateCategory({id : task_id, toCategory : toCategory});
                        this.categories.map(categories => {

                            if(categories.id == toCategory) {
                                console.log( categories.tasks,categories.tasks['0'],'task')
                                for(let c in categories.tasks) {
                                    console.log( categories.tasks[0],'task_id',task_id)
                                }
                               // categories.tasks.forEach((element, index)=>{

                                    /*if (element.id == task_id) {

                                        categories.tasks[index].category_id = toCategory;
                                    }*/
                              //  })
                            }
                        });
                        console.log(this.categories,'changeOrder');
                    }catch (e) {
                        console.log(e)
                    }
            }
        },
        editTask(task) {
         this.editTaskData = {...task};
            console.log(task,'editTask');
         this.showEditTaskModal();
        },
        async saveTaskData() {
            let formData = new FormData();
            formData.append('title', this.editTaskData.title);
            formData.append('categoryID', this.editTaskData.category_id);
            formData.append('description', this.editTaskData.Description);
            formData.append('priority', this.editTaskData.priority);
            formData.append('type', this.editTaskData.type);
            formData.append('id', this.editTaskData.id);
            formData.append('_method','PUT');
            try
            {
                const response = await todoService.UpdateList(this.editTaskData.id,formData);

                this.categories.map(categories => {
                   if (categories.id == response.data.data.category_id) {
                       categories.tasks.forEach((element, index)=>{
                           if (element.id == response.data.data.id) {
                               categories.tasks[index] = response.data.data;
                           }
                       });
                    }
                });
                this.hideEditTaskModal();
                this.flashMessage.success({
                    message: response.data.message,
                    time: this.$getConst('TIME'),
                    blockClass: 'custom-block-class'
                });
                this.editTaskData = this.EditData;

            }catch (e) {
                if (e.response.status) {
                    this.errors = e.response.data.errors
                } else {
                    this.flashMessage.error({
                        message: this.translate(e),
                        time: this.$getConst('TIME'),
                        blockClass: 'custom-block-class'
                    });
                }

            }
        },
        deleteTask(id) {
            const flash = this.flashMessage;
           this.$swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes Delete it!',
                cancelButtonText: 'No, Keep it!',
                showLoaderOnConfirm: true
            }).then((result) => {
                if(result.value) {
                    try
                    {
                        const response = todoService.deleteTask(id);
                        flash.success({
                            message: response.data.message,
                            time: this.$getConst('TIME'),
                            blockClass: 'custom-block-class'
                        });
                        this.categories.map(categories => {
                            if (categories.id == response.data.data.category_id) {
                                categories.tasks.forEach((element, index)=>{
                                    if (element.id == response.data.data.id) {
                                        console.log(categories.tasks[index])
                                        delete categories.tasks[index];
                                    }
                                });
                            }
                        });
                    }catch (e) {

                    }

                }
            });
        }

    }
}
</script>

<style scoped>
.kanban-column {
    min-height: 300px;
}
.avatar {
    vertical-align: middle;
    width: 50px;
    height: 50px;
    border-radius: 50%;
}
.column-width {
    min-width: 320px;
    width: 320px;
}
/* Unfortunately @apply cannot be setup in codesandbox,
but you'd use "@apply border opacity-50 border-blue-500 bg-gray-200" here */
.ghost-card {
    opacity: 0.5;
    background: #F7FAFC;
    border: 1px solid #4299e1;

}
.cardClass{
    min-height: 300px;
}
</style>
