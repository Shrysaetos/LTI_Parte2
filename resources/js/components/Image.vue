<template>
    <div>
        <div class="jumbotron">
            <h1>Images</h1>
        </div>
        <button class="btn btn-info col-lg-2 control-label" v-on:click.prevent="createImage">Create Image</button>
        <button type="button" class="btn btn-outline-danger col-lg-2 " v-on:click.prevent="goBack">Cancel</button>
        <br><br>
    	<table class="table">
        	<thead>
        	    <tr>
        	        <th>Name</th>
                    <th>Status</th>
                    <th>Protected</th>
                    <th>Public</th>
                    <th>Actions</th>
        	    </tr>
       		</thead>
        	<tbody>
                <tr v-for='i in images.images'>
                    <td>{{i.name}}</td>
                    <td>{{i.status | capitalize}}</td>
                    <td v-if='i.protected == false'>No</td>
                    <td v-if='i.protected == true'>Yes</td>
                    <td v-if='i.visibility == "public"'>Yes</td>
                    <td v-if='i.visibility != "public"'>No</td>
                    <td>
                        <button type="button" class="btn btn-danger" v-on:click.prevent="deleteImage(i.id)">Delete</button>
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
                images: [],
            };
        },

        filters: {
            capitalize: function (value) {
                if (!value) return ''
                    value = value.toString()
                    return value.charAt(0).toUpperCase() + value.slice(1)
            }
        },

        methods: {
            getImages: function () {
                this.images = [];
                var vm = this;
                axios.get('api/images')
                    .then(function (response){
                        vm.images = response.data;
                    })
                    .catch(function (error){
                        vm.images = 'An error occurred.' + error;
                    });
            },

            goBack() {
                this.$router.push('/currentUser');
            },

            deleteImage: function(imageID) {
                axios.delete('api/deleteImage/' +imageID);            },

            createImage() {
                this.$router.push('/createImage');
            }
        },
        mounted() {
            this.getImages();
        }
    };
</script>