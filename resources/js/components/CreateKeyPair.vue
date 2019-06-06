<template>
  <div>
    <div class="jumbotron">
      <h1>Create Key Pair</h1>
    </div>
    <div>
        <h4>Key pair</h4>
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
            <label>Type</label>
            <font v-if="type==''" size="3" color="red">*</font>
            <div>
                <select v-model="type">
                    <option disabled value="">Please select one</option>
                    <option>SSH key</option>
                    <option>X509 Certificate</option>
                </select>
            </div>
        </div>
        <br>
        <div>
            <label>Public key (Leave blank to generate a new key): </label>
            <div>
                <input v-model="publicKey" placeholder="Public key">
            </div>
        </div>
        

        <br><br>
      <div>
        <button type="button" :disabled="validateCreate" class="btn btn-outline-success" v-on:click.prevent='createKeypair(name, type, publicKey)'>Create</button>
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
                type: '',
                publicKey: '',
                validation: false,
            };
        },
        methods: {  
            createKeypair: function (name, type, publicKey) {
                this.info = [];
                var vm = this;
                if (publicKey == '') {
                    publicKey = 'null';
                }
                
                axios.post('api/createKeypair/' + name + '/' + type + '/' + publicKey)
                .then(function (response){
                    vm.info = response.data;
                })
                .catch(function (error){
                    vm.info = 'An error occurred.' + error;
                });
                             
                    this.goBack();
                
            },           
            goBack() {
                var vm = this
                setTimeout(function(){   
                vm.$router.push('/keypairs');
                }, 500);
            },
        },
        computed: {
            validateCreate() {
              return this.name == '' || this.type == '';
            }
        },
        mounted() {
        }
    };
</script>