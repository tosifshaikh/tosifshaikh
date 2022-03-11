<template>
  <div>
    <Modal v-model="getdeleteModalObj.showDeleteModal" width="360"
    :mask-closable="false"
    :closable='false'
    >
      <p slot="header" style="color: #f60; text-align: center">
        <Icon type="ios-information-circle"></Icon>
        <span>Delete confirmation</span>
      </p>
      <div style="text-align: center">
        <p>Are you sure you want to delete this tag?.</p>
      </div>
      <div slot="footer">
          <Button
          type="default"
          size="large"

          @click="closeModal"
          >Close</Button
        >
        <Button
          type="error"
          size="large"

          :loading="modalLoading"
          @click="remove"
          >Delete</Button
        >
      </div>
    </Modal>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    data() {
        return {
            modalLoading : false,
        }
    },
    methods: {
         async remove() {

              this.modalLoading = true;
                const res= await this.callApi('post',this.getdeleteModalObj.deleteURL, this.getdeleteModalObj.data);
                if (res.status == 200) {
                     //this.success('Category has been deleted successfully!');
                     this.success(this.getdeleteModalObj.msg);
                     this.$store.commit('setDeleteModal',true);
                } else {
                     this.error();
                        this.$store.commit('setDeleteModal',false);
                }
                this.modalLoading = false;
            //     this.showDeleteModal = false;

        },
        closeModal() {
             this.$store.commit('setDeleteModal',false);
        }
    },
    computed: {
        ...mapGetters([
            'getdeleteModalObj',
        ])
    },
};

</script>

<style>
</style>
