<template>
    <div>
        <div class="jumbotron">
            <h1>Flavors</h1>
        </div>
        <table class="table">
        	<thead>
        	    <tr>
        	        <th>Name</th>
        	        <th>VCPUS</th>
        	        <th>RAM</th>
                    <th>Total Disk</th>
                    <th>Public</th>
        	    </tr>
       		</thead>
        	<tbody>
                <tr v-for='f in flavors.flavors' v-if='f.id == flavorID'>
                    <td>{{f.name}}</td>
                    <td>{{f.vcpus}}</td>
                    <td>{{f.ram}}MB</td>
                    <td>{{f.disk}}G</td>
                    <td v-if='f["os-flavor-access:is_public"] == true'>Yes</td>
                    <td v-if='f["os-flavor-access:is_public"] != true'>No</td>
                </tr>
        	</tbody>
    	</table>
        <button type="button" class="btn btn-outline-danger" v-on:click.prevent="goBack">Back</button>
    	</div>
</template>
<script>
    module.exports = {
        data() {
            return {
                flavors: [],
                flavorID: 0,
            };
        },
        methods: {
            getFlavors: function () {
                this.flavors = [];
                var vm = this;
                axios.get('api/flavors')
                    .then(function (response){
                        vm.flavors = response.data;
                    })
                    .catch(function (error){
                        vm.flavors = 'An error occurred.' + error;
                    });
            },

            getFlavorID: function () {
                this.flavorID = this.$route.params.id;
            },

            goBack() {
                this.$router.push('/instances');
            },
        },
        mounted() {
            this.getFlavors();
            this.getFlavorID();
        }
    };
</script>