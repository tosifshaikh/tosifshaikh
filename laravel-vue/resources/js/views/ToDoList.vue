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
                <div class="row">
                    <div class="col-md-3">
                        <div class="p-2 alert alert-secondary">
                            <div class="text-center"><h5>Backlog  <button class="btn btn-primary btn-sm ml-2"><span class="fa fa-plus" ></span></button></h5></div>
                            <draggable class="list-Group kanban-column" :list="arrBackLog" group="tasks" @change="updateTodo">
                            <div class="list-group-item" v-for="element in arrBackLog" :key="element.id">
                            {{ element.name}}
                            </div>
                            </draggable>
                        </div>
                    </div>
                    <div class="col-md-3">
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
                    </div>
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
    },  props: ['arrBackLog', 'arrInProgress','arrTested','arrDone'],
    data() {
        return {
            newTask : '',
            arrBackLog : [
                {name : 'Product 1', id : 1},
                {name : 'Product 2', id : 2},
                {name : 'Product 3', id : 3},
                {name : 'Product 4', id : 4}
            ],
            arrInProgress : [],
            arrTested : [],
            arrDone : []
        }
    },
    methods : {
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