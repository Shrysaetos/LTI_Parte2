<template>
    <div>
        <div class="jumbotron">
            <h1>Key Pairs</h1>
        </div>
        <button class="btn btn-info col-lg-2 control-label" v-on:click.prevent="createKeypair">Create Key Pair</button>
        <button type="button" class="btn btn-outline-danger col-lg-2 " v-on:click.prevent="goBack">Cancel</button>
        <br><br>
    	<table class="table">
        	<thead>
        	    <tr>
        	        <th>Name</th>
                    <th>Fingerprint</th>
                    <th>Public key</th>
                    <th>Actions</th>
        	    </tr>
       		</thead>
        	<tbody>
                <tr v-for='k in keypairs.keypairs'>
                    <td>{{k.keypair.name}}</td>
                    <td>{{k.keypair.fingerprint}}</td>
                    <td v-line-clamp="lines">{{k.keypair.public_key}}</td>
                    <td>
                        <button type="button" class="btn btn-danger" v-on:click.prevent="deleteKeypair(k.keypair.name)">Delete</button>
                    </td>
                </tr>
        	</tbody>
    	</table>
    	</div>
</template>
<script>
    module.exports = {
        data() {
            return {
                keypairs: [],
                lines: '',
            };
        },
        methods: {
            getKeypairs: function () {
                this.keypairs = [];
                var vm = this;
                axios.get('api/keypairs')
                    .then(function (response){
                        vm.keypairs = response.data;
                    })
                    .catch(function (error){
                        vm.keypairs = 'An error occurred.' + error;
                    });
            },

            goBack() {
                this.$router.push('/currentUser');
            },

            deleteKeypair: function(image) {
                axios.delete('api/keypairs' + keypair.id)
                    .then(response => {
                        this.getKeypairs();
                    })
            },

            createKeypair() {
                this.$router.push('/createKeypair');
            },

            deleteKeypair: function (keypairName) {
                var vm = this;
                axios.delete('api/deleteKeypair/' +keypairName);
                    vm.$forceUpdate();;
                
            }, 
        },
        mounted() {
            this.getKeypairs();
        }
    };
</script>