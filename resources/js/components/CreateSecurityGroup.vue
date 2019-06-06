<template>
  <div>
    <div class="jumbotron">
      <h1>Create Security Group</h1>
    </div>
    <div>
        <h4>Security Group</h4>
        <br>

        <div>
            <label>Name</label>
            <font v-if="name==''" size="3" color="red">*</font>
            <div>
                <input v-model="name" placeholder="Key pair name">
            </div>
        </div>
        <br>
        <div>
            <label>Description</label>
            <div>
                <input v-model="description" placeholder="Description">
            </div>
        </div>
        <br>
        

      <div>
        <button type="button" :disabled="validateCreate" class="btn btn-outline-success" v-on:click.prevent='createSecurityGroup()'>Create</button>
        <button type="button" class="btn btn-outline-danger" v-on:click.prevent="goBack">Cancel</button>
      </div>
     
    </div>
  </div>
</template>
<script>
    module.exports = {
        data() {
            return {
                info: [],
                name: '',
                description: '',
                validation: false,
            };
        },
        methods: {  
            createSecurityGroup: function () {
                this.info = [];
                var vm = this;
                if (vm.description == '') {
                    vm.description = 'null';
                }
                
                axios.post('api/createSecurityGroup/' + vm.name + '/' + vm.description)
                .then(function (response){
                    vm.info = response.data;
                })
                .catch(function (error){
                    vm.info = 'An error occurred.' + error;
                });
                //this.$router.push('/keypairs');
            },           
            goBack() {
                this.$router.push('/security_groups');
            },
        },
        computed: {
            validateCreate() {
              return this.name == '';
            }
        },
        mounted() {
        }
    };
</script>