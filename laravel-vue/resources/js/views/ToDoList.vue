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
                    <div class="col-md-3" v-for="element in categories" :key="element.id">
                        <div class="p-2 alert alert-secondary">
                            <div class="text-center"><h5>{{element.category}}  <button class="btn btn-primary btn-sm ml-2"><span class="fa fa-plus" ></span></button></h5></div>
                          <draggable class="list-Group kanban-column"  group="tasks"  @end="changeOrder" v-model="element.tasks">
                            <div class="list-group-item" v-for="task in element.tasks" :id="task.id" :key="task.category_id+','+task.order">
                            {{ task.taskName}}
                            </div>
                            </draggable>
                        </div>
                    </div>
                    <!--                   <div class="col-md-3">
                                           <div class="p-2 alert alert-primary">
                                               <div class="text-center"><h5>In Progress</h5></div>
                                               <draggable class="list-Group kanban-column" :list="arrInProgress" group="tasks" @change="updateTodo" @add="onAdd($event, false)">
                                                   <div class="list-group-item" v-for="element in arrInProgress" :key="element.id">
                                                       {{ element.name}}
                                               </div>
                                               </draggable>
                                           </div>
                                       </div>
                                       <div class="col-md-3">
                                           <div class="p-2 alert alert-warning">
                                               <div class="text-center"><h5>Tested</h5></div>
                                               <draggable class="list-Group kanban-column" :list="arrTested" group="tasks" @change="updateTodo" @add="onAdd($event, false)">
                                                   <div class="list-group-item" v-for="element in arrTested" :key="element.id">
                                                       {{ element.name}}
                                                   </div>
                                               </draggable>
                                           </div>
                                       </div>
                                       <div class="col-md-3">
                                           <div class="p-2 alert alert-success">
                                               <div class="text-center"><h5>Done</h5></div>
                                               <draggable class="list-Group kanban-column" :list="arrDone" group="tasks" @change="updateTodo">
                                                   <div class="list-group-item" v-for="element in arrDone" :key="element.id">
                                                       {{ element.name}}
                                                   </div>
                                               </draggable>
                                           </div>
                                       </div>-->
                </div>


            </div>
        </div>

    </div>
</template>
<script>
import draggable from 'vuedraggable';
export default {
    name: "ToDoList",
    components :{
        draggable
    },
    data() {
        return {
            newTask : '',
            categoryList : [
                { id : 1, category : 'Backlog'},
                { id : 2, category : 'To Do'},
                { id : 3, category : 'In Progress'},
                { id : 4, category : 'Done'},
            ],
            tasks : [
                { id : 1, category_id : 1,order : 0, taskName : 'Task 1'},
                { id : 2, category_id : 1, order :1,taskName : 'Task 2'},
                { id : 3, category_id : 2, order: 2,taskName : 'Task 3'},
                { id : 4, category_id : 3, order :3, taskName : 'Task 4'},
            ],
            categories : []

        }
    },
    mounted() {
        this.categories = this.categoryList;
        this.loadTasks();
    },
    methods : {
        loadTasks() {
            this.categories.map(category => {
                console.log(category)
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
            console.log(this.categories);
        },
        changeOrder(data) {
            /*let toTask = data.to
            let fromTask = data.from
            let task_id = data.item.id
            let category_id = fromTask.id == toTask.id ? null : toTask.id
            let order = data.newIndex == data.oldIndex ? false : data.newIndex*/
console.log(data);
        },
        onAdd(evt,status) {
            console.log(evt,status,'add')
            /*if (this.newTask) {
                this.arrBackLog.push({name : this.newTask});
                this.newTask = '';
            }*/
        },
        async updateTodo(evt) {
            let todo = evt.removed && evt.removed.element;
            if (todo) {
                console.log(evt)
            }
        },

    }
}
</script>

<style scoped>
.kanban-column {
    min-height: 300px;
}
</style>
