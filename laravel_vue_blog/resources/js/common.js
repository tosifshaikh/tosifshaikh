export default {
    data() {
        return {

        }
    },
    methods: {
       async callApi(method, url, dataObj) {
            console.log(method, url, dataObj,'call api');
            try {
                 return  await axios({
                    method: method,
                    url: url,
                    data: dataObj
                  });
            } catch (e) {
                console.log(e,'res')
                return e.response;
            }
        },
        info (description ,title='Hey') {
            this.$Notice.info({
                title: title,
                desc: description ? description : ''
            });
        },
        success (description ,title='Great') {
            this.$Notice.success({
                title: title,
                desc: description ? description : ''
            });
        },
        warning (description, title = 'Oops') {
            this.$Notice.warning({
                title: title,
                desc: description ? description : ''
            });
        },
        error (description='Some error occured!', title = 'Oops') {
            this.$Notice.error({
                title: title,
                desc: description ? description : ''
            });
        },
        checkUserPermission(key) {
            if (!this.userPermission) {
                return true;
            }
            let isPermitted = false;
            for (let d in this.userPermission) {
                if (this.$route.name == d.name) {
                    if (d[key]) {
                        isPermitted == true;
                        break;
                    } else {
                        break;
                    }
                }

            }
            return isPermitted;
        }
    },
    computed: {
        ...mapGetters({
            'userPermission': 'getUserPermission',
        }),
        isReadPermitted() {
            return this.checkUserPermission('read');
        },
        isWritePermitted() {
            return this.checkUserPermission('write');
        },
        isUpdatePermitted() {
            return this.checkUserPermission('update');
        },
        isDeletePermitted() {
            return this.checkUserPermission('delete');
        },
    }
}
