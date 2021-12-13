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
                <div class="row" v-model="categories" >
                    <div class="col-md-3" v-for="element in categories" :key="element.category_id">
                        <div class="p-2 alert alert-secondary">
                            <div class="text-center "><h5>{{element.category_name}}

                                <button class="btn btn-primary btn-sm ml-2" v-if="element.category_id == 1" @click="showAddTaskModal"><span class="fa fa-plus" ></span></button></h5>

                            </div>
                        <draggable class="list-Group kanban-column"  group="tasks"  @end="changeOrder" v-model="element.tasks">
                               <transition-group :id="element.category_id">

                            <div class="list-group-item mb-3" v-for="task in element.tasks" :id="task.task_id" :key="task.task_id+','+task.category_id+','+task.order" >


                                <div class="float-right">
                                    <button class="btn btn-xs" v-on:click="editTask(task)"><span class="fa fa-edit" ></span></button>
                                </div>

                                <div class="card border-grey mb-3" style="max-width: 18rem;">

                                    <div class="card-header bg-transparent border-grey column">
                                        <div class="float-left ">
                                            <label>{{ task.task_title}}</label>
                                        </div>
                                        <div class="float-right mt-0 align-top">
                                            <img src="/assets/uploads/product/1637598357.png" alt="" width="100" class="img-fluid avatar">
                                        </div>
                                    </div>

                                    <div class="card-body">
                                     <span :class="priority[task.priority].color">{{priority[task.priority].name}}</span>

<!--                                        <h5 class="card-title">Success card title</h5>-->
                                        <p class="card-text text-truncate">{{task.task_description}}</p>
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

                <b-modal ref="TaskModal" hide-footer title="Add Task">
                    <div class="d-block">
                        <form v-on:submit.prevent="AddTask">
                            <div class="mb-3">
                                <label for="title" class="form-label">Task Title</label>
                                <input type="text" class="form-control" id="title" placeholder="Enter Task Title" v-model="taskData.title">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ translate('Enter Description') }}</label>
                                <textarea
                                    class="form-control"
                                    name="description"
                                    id="description"
                                    v-model="taskData.description"
                                    :placeholder="[[translate('Enter Description')]]"
                                > </textarea>
                            </div>
                            <div class="mb-3">
                                <label for="priority" class="form-label">{{ translate('Enter Priority') }}</label>
                                <select class="form-control"  id="priority" name="priority" v-model="taskData.priority">
                                    <option value="">Choose Priority</option>
                                    <option v-for="(priority,index) in priority" :value="index" :key="index">{{ priority.name }}</option>
                                </select>
                            </div>
                                <!--                                <select class="form-control"  id="title" name="title" v-model="taskData.category_id">
                                    <option value="">Choose Category</option>
                                    <option v-for="(category,index) in categories" :value="category.id" :key="index">{{ category.name }}</option>
                                </select>
                                <div class="invalid-feedback" v-if="errors.category_id">{{errors.category_id[0]}}</div>-->

<!--                            <div class="mb-3">
                                <label for="name" class="form-label">Enter Product Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter Product Name" v-model="editProductData.product_name">
                                <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Choose an Image</label>
                                <div>
                                    <img ref="editProductImageDisplay" :src="`${$store.state.serverPath}assets/uploads/product/${editProductData.image}`" :alt="editProductData.name"  class="img-thumbnail">
                                </div>

                                <input type="file" class="form-control" id="image" v-on:change="editAttachImage" ref="editProductImage">
                                <div class="invalid-feedback" v-if="errors.image">{{errors.image[0]}}</div>
                            </div>
                            <hr>-->
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
                                <input type="text" class="form-control" id="title" placeholder="Enter Task Title" v-model="editTaskData.task_title">
                                <div class="invalid-feedback" v-if="errors.title">{{errors.title[0]}}</div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ translate('Enter Description') }}</label>
                                <textarea
                                        class="form-control"
                                        name="description"
                                        id="description"
                                        v-model="editTaskData.task_description"
                                        :placeholder="[[translate('Enter Description')]]"
                                > </textarea>
                            </div>
                            <div class="mb-3">
                                <label for="priority" class="form-label">{{ translate('Enter Priority') }}</label>
                                <select class="form-control"  id="priority" name="priority" v-model="editTaskData.priority">
                                    <option value="">Choose Priority</option>
                                    <option v-for="(priority,index) in priority" :value="index" :key="index">{{ priority.name }}</option>
                                </select>
                            </div>
                            <!--                                <select class="form-control"  id="title" name="title" v-model="taskData.category_id">
                                <option value="">Choose Category</option>
                                <option v-for="(category,index) in categories" :value="category.id" :key="index">{{ category.name }}</option>
                            </select>
                            <div class="invalid-feedback" v-if="errors.category_id">{{errors.category_id[0]}}</div>-->

                            <!--                            <div class="mb-3">
                                                            <label for="name" class="form-label">Enter Product Name</label>
                                                            <input type="text" class="form-control" id="name" placeholder="Enter Product Name" v-model="editProductData.product_name">
                                                            <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="image" class="form-label">Choose an Image</label>
                                                            <div>
                                                                <img ref="editProductImageDisplay" :src="`${$store.state.serverPath}assets/uploads/product/${editProductData.image}`" :alt="editProductData.name"  class="img-thumbnail">
                                                            </div>

                                                            <input type="file" class="form-control" id="image" v-on:change="editAttachImage" ref="editProductImage">
                                                            <div class="invalid-feedback" v-if="errors.image">{{errors.image[0]}}</div>
                                                        </div>
                                                        <hr>-->
                            <div class="text-right">
                                <button type="button" class="btn btn-light" v-on:click="hideEditTaskModal"> Cancel</button>
                                <button type="submit" class="btn btn-primary" ><span class="fa fa-check"></span> Update Task</button>
                            </div>

                        </form>
                    </div>

                </b-modal>
            </div>
        </div>

    </div>
</template>
<script>
import draggable from 'vuedraggable';
import * as todoService from '../Services/todo_service';
import * as ProductService from "../Services/product_service";
import {getToDolist} from "../Services/todo_service";
export default {
    name: "ToDoList",
    components :{
        draggable
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

      }
    },
    data() {
        return {
            time : '',
            taskData : {
                title : '',
                description : '',
                priority : '',
                category_id : 1,
                user_id : 1
            },
            editTaskData : {
                title : '',
                description : '',
                priority : '',
                category_id : '',
                user_id : ''
            },
            errors : {},
            categoryList : [
                { id : 1, category : 'Backlog'},
                { id : 2, category : 'To Do'},
                { id : 3, category : 'In Progress'},
                { id : 4, category : 'Done'},
            ],
            tasks : [
                { id : 1, category_id : 1,order : 0, taskName : 'Task 1', title : 'Task 1', priority : 1},
                { id : 2, category_id : 1, order :1,taskName : 'Task 2', title : 'Task 2', priority : 2},
                { id : 3, category_id : 2, order: 2,taskName : 'Task 3', title : 'Task 3', priority : 1},
                { id : 4, category_id : 3, order :3, taskName : 'Task 4', title : 'Task 4', priority : 3},
            ],
            categories : [],
            priority : {
                1 : {color : 'badge badge-danger', name : 'High'},
                2 : {color : 'badge badge-warning', name: 'Medium'},
                3 : {color : 'badge badge-primary', name : 'Low'}
            }

        }
    },
    mounted() {
       // this.categories = this.categoryList;
        this.getCategories();
       // this.loadTasks();
    },
    methods : {
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
            formData.append('_method','POST');
            try
            {
                const response = await todoService.addList(formData);
                this.flashMessage.success({
                    message: response.data.message,
                    time: this.$getConst('TIME'),
                    blockClass: 'custom-block-class'
                });
                this.taskData = [];

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
                console.log(this.categories);
                //this.categories.tasks= [];
               // this.loadTasks();
            }
            catch (e) {
                this.flashMessage.error({
                    message: this.translate(e),
                    time: this.$getConst('TIME'),
                    blockClass: 'custom-block-class'
                });
            }

           //

        },
        async loadTasks() {
            try {
                //const response = await todoService.getToDoTasks();
               // console.log(response);
            }
            catch (e) {

            }
            this.categories.map(category => {
               // console.log(category)
                let tempTask = [];
                for(let index in this.tasks) {
                    if (this.tasks[index].category_id == category.id) {
                        tempTask.push(this.tasks[index]);
                        //category.tasks.push(this.tasks[index])
                    }
                }
                if (tempTask.length) {
                    category.tasks =tempTask;
                }
                /*category.tasks = [
                    {id : 1, category_id : 1, order :  1, taskName : 'Task 1'}
                ];*/
            })
           // console.log(this.categories);
        },
        changeOrder(data) {
            //console.log(data);
          //  console.log(data.to.id,data.from.id,data.item.id,data.newIndex,data.oldIndex )
            let toTask = data.to;
            let fromTask = data.from;
            let task_id = data.item.id;
            let category_id = fromTask.id == toTask.id ? null : toTask.id;
            let order = data.newIndex == data.oldIndex ? false : data.newIndex;
            if (order !== false && category_id !== null) {

            }
        },
        editTask(task) {
         this.editTaskData = {...task};
            this.showEditTaskModal();
        },
        async saveTaskData() {
            let formData = new FormData();
            formData.append('title', this.editTaskData.task_title);
            formData.append('categoryID', this.editTaskData.category_id);
            formData.append('description', this.editTaskData.task_description);
            formData.append('priority', this.editTaskData.priority);
            formData.append('id', this.editTaskData.task_id);
            formData.append('_method','PUT');
            try
            {
                const response = await todoService.UpdateList(this.editTaskData.task_id,formData);
                this.categories.map(categories => {

                   /* categories.map(task => {
                        console.log(task);
                    })
*/
                    /*if (product.id == response.data.id) {
                        for (let key in response.data) {
                            product[key] = response.data[key];
                        }
                    }*/
                });
                this.hideEditTaskModal();
                this.flashMessage.success({
                    message: response.data.message,
                    time: this.$getConst('TIME'),
                    blockClass: 'custom-block-class'
                });
                this.taskData = [];

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
</style>
