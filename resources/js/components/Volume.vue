<template> 
  <div>
    <div class="jumbotron">
      <h1>Volumes</h1>
    </div>
    <div> 
      <div>
        <button type="button" class="btn btn-info" v-on:click.prevent='createVolume()'>Create Volume</button>
        <button type="button" class="btn btn-outline-danger" v-on:click.prevent="goBack">Cancel</button>

      </div>
        <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Size</th>
                        <th>Status</th>
                        <th>Group</th>
                        <th>Type</th>
                        <th>Attached to</th>
                        <th>Availability Zone</th>
                        <th>Bootable</th>
                        <th>Encrypted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for='v in volumes.volumes'>
                        <td>{{v.name}}</td>
                        <td>{{v.size}}G</td>
                        <td>{{v.status}}</td>
                        <td v-if= 'consistencygroup_id == null'>-</td>
                        <td v-if= 'consistencygroup_id != null'>{{v.consistencygroup_id}}</td>
                        <td>{{v.volume_type}}</td>
                        <td v-if= 'v.attachments.length != 0' v-for= 'a in v.attachments'>{{a.device}}</td>
                        <td v-if= 'v.attachments.length == 0'>-</td>
                        <td>{{v.availability_zone}}</td>
                        <td v-if='v.bootable=="false"'>No</td>
                        <td v-if='v.bootable=="true"'>Yes</td>
                        <td v-if='v.encrypted==false'>No</td>
                        <td v-if='v.encrypted==true'>Yes</td>
                        <td>
                            <button class="btn btn-info" v-on:click.prevent="editVolume">Edit</button>
                            <button type="button" class="btn btn-danger" v-on:click.prevent="deleteVolume(v.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
        </table>
    </div>
  </div>
</template>
<script>
    module.exports = {
        data() {
            return {
                volumes: [],
                count: 0,
                name,
                consistencygroup_id: '',
            };
        },
        methods: {
            getVolumes: function () {
                this.volumes = [];
                var vm = this;
                    axios.get('api/volumes')
                    .then(function (response){
                      vm.volumes = response.data;
                    })
                    .catch(function (error){
                      vm.volumes = 'An error occurred.' + error;
                    });

                },  

            
            createVolume: function () {
                this.$router.push('/createVolume');
            },  

            deleteVolume: function (volumeID) {
                var vm = this;
                axios.delete('api/deleteVolume/' +volumeID);
            },                  
            goBack() {
                this.$router.push('/login');
            },
        },
        mounted() {
            this.getVolumes();
        }
    };
</script>