<template>
  <div>
    <div class="jumbotron">
      <h1>Create Image</h1>
    </div>
    <div>
        <h4>Details</h4>

        <div>
            <label>Name</label>
            <font v-if="name==''" size="3" color="red">*</font>
            <div>
                <input v-model="name" placeholder="Image name">
            </div>
        </div>
        <br>
        <div>
            <label>Disk format</label>
            <font v-if="format==''" size="3" color="red">*</font>
            <div>
                <select v-model="format">
                    <option>ami</option>
                    <option>ari</option>
                    <option>aki</option>
                    <option>vhd</option>
                    <option>vhdx</option>
                    <option>vmdk</option>
                    <option>raw</option>
                    <option>qcow2</option>
                    <option>vdi</option>
                    <option>ploop</option>
                    <option>iso</option>
                </select>
            </div>
        </div>
        <br>
        <div>
            <label>Location</label>
            <font v-if="location==''" size="3" color="red">*</font>
            <div>
                <input v-model="location" placeholder="Image location">
            </div>
        </div>
        <br><br>
        <h4>Requirements</h4>
        <div>
            <label>Minimum Disk  (GB)</label>
            <div>
                <input type="number" v-model="disk" :min="0">
            </div>
        </div>
        <br>
        <div>
            <label>Minimum RAM (MB)</label>
            <div>
                <input type="number" v-model="ram" :min="0">
            </div>
        </div>
        <br><br>
        <div>
            <h4>Sharing</h4>
            <div>
                <label>Visibility</label>
                <div>
                    <select v-model="visibility">
                        <option>shared</option>
                        <option>public</option>
                        <option>private</option>
                        <option>community</option>
                    </select>
                </div>
                <br>
                <label>Protected</label>
                <div>
                    <select v-model="protected">
                        <option>yes</option>
                        <option>no</option>
                    </select>
                </div>
            </div>
        </div>

        <br><br>
      <div>
        <button type="button" :disabled="validateCreate" class="btn btn-outline-success" v-on:click.prevent="createImage">Create</button>
        <button type="button" class="btn btn-outline-danger" v-on:click.prevent="goBack">Cancel</button>
      </div>
      <br><br>

    </div>
  </div>
</template>
<script>
    module.exports = {
        data() {
            return {
                info: [],
                name: '',
                format: 'qcow2',
                disk: '',
                ram: '',
                id: '',
                visibility: 'shared',
                protected: 'no',
                protectedBool: false,
                locationFinal: '',
                location: '',
                nameFinal: '',
                formatFinal: '',
                locationFina: '',
            };
        },
        methods: {

            createImage: function () {
                this.info = [];
                var vm = this;

                //Verificações
                //name, format, location, visibility, protected nunca são vazios
                vm.nameFinal = '"' + vm.name + '"';
                vm.formatFinal = '"' + vm.format + '"';
                vm.locationFinal = '"' + vm.location + '"';
                vm.visibilityFinal = '"' + vm.visibility + '"';
                if (vm.disk == '') {
                    vm.disk = 'null';
                } 
                if (vm.ram == '') {
                    vm.ram = 'null';
                }
                if (vm.protected == 'no') {
                    vm.protectedBool = '"false"';
                }
                if (vm.protected == 'yes') {
                    vm.protectedBool = '"true"'
                }
                vm.locationFinal = vm.locationFinal.replace(/[/]/g,']');
                

                axios.post('api/createImage/' + vm.nameFinal + '/' + vm.formatFinal + '/' + vm.locationFinal + '/' + vm.disk + '/' + vm.ram + '/' + vm.visibilityFinal  + '/' + vm.protectedBool)
                .then(function (response){
                    vm.info = response.data;
                })
                .catch(function (error){
                    vm.info = 'An error occurred.' + error;
                });
                
            },  
                 
            goBack() {
                this.$router.push('/images');
            },
        },
        computed: {
            validateCreate() {
              return this.name == '' || this.format == '' || this.location == '';
            }
        },
        mounted() {
        }
    };
</script>