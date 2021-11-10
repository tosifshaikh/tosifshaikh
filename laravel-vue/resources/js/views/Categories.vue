<template>
    <div class="container-fluid px-4">
        <ol class="breadcrumb mt-4">
            <li class="breadcrumb-item active">
                <router-link to="/">
                    Dashboard
                </router-link>
            </li>

            <li class="breadcrumb-item">
                Categories
            </li>
        </ol>
      <div class="card mb-3">
          <div class="card-header">
              <i class="fas fa-chart-area"></i>
              Categories Management
          </div>
            <div class="card-body">
               <table class="table">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Name</td>
                        <td>Image</td>
                        <td>Action</td>
                    </tr>
                </thead>
                   <tbody>
                    <tr>
                        <td>1</td>
                        <td>ABC</td>
                        <td>Image</td>
                        <td>
                            <button class="btn btn-primary btn-sm"><span class="fa fa-edit"></span></button>
                            <button class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></button>
                        </td>
                    </tr>
                   </tbody>
               </table>
            </div>
      </div>

    </div>
</template>

<script>
export default {
    name: "Categories"
}
</script>

<style scoped>

</style>
