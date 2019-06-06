<template>
	<div>
		<div v-if="hideValidate">
			<div class="jumbotron">
		     	<h1>Add Server and User</h1>
		    </div>
		    <div v-if="noErrors">
		    	<font size="5" color="red">Bad Combination!</font>
		    	<br>
		    </div>
			<form v-on:submit.prevent="validateServerAndUser" method="post">
				<div class="form-group">
					<label>Server</label>
	            	<font v-if="server.server==''" size="3" color="red">*</font>
					<input type="text" v-model="server.server" class="form-control">
				</div>
				<div class="form-group">
					<label>Username</label>
	            	<font v-if="server.username==''" size="3" color="red">*</font>
					<input type="text" v-model="server.username" class="form-control">
				</div>
				<div class="form-group">
					<label>Password</label>
	            	<font v-if="server.password==''" size="3" color="red">*</font>
					<input type="password" v-model="server.password" class="form-control">
				</div>
				<div class="form-group">
					<input :disabled="validateCreate" type="submit" class="btn btn-primary" value="Validade Server and User">
				</div>
			</form>
		</div>


	<br>
	<div v-if="validated">
		<div class="jumbotron">
	     	<h1>Choose a Project</h1>
	    </div>
	    <br>

		<div class="form-group">
			<h4>Select a project</h4>
			<br>
			<div>
				<select v-model="project" class="form-control">
                  	<option disabled value="">Please select one</option>
                    <option v-for='p in projectList.projects'>{{p.name}}</option>
                </select>

			
			</div>
			<br><br>
            
       
			<button type="button" class="btn btn-primary" :disabled="project == ''" v-on:click.prevent="createUser">Choose Project</button>
			<button type="button" class="btn btn-outline-danger" v-on:click.prevent="changeHideValidate">Cancel</button>
		</div>


		
	</div>
	</div>

</template>

<script>
	import server from './Server.vue'
	export default {

		data(){
			return{
				info: [],
				projectList: [],
				servers: [],
				tokenInfo: [],
				validated: false,
				hideValidate: true,
				noErrors: false,
				serverTemp: '',
				server:{
					server:'',
					username: '',
					password: '',
					tempToken: '',
					project: '',
				},
				userId: '',
				project: '',
				projectId: '',
			}
		},
		
		components:{ server },
		created (){
			this.fetchServer();
		},

		methods:{
			fetchServer(){
				this.$http.get("server").then( response => { this.servers = response.data.servers});
			},
			createUser(){
				this.getProjectId();
				this.getToken();


				this.$http.post("/api/server/",this.server).then(response => {
						this.server = response.data.server;
						console.log(response.data);
				});

				
				this.goToCurrentUser();
				
			},
			
			changeHideValidate(){
				var vm = this;
				vm.hideValidate = true;
				vm.validated = false;
			},
			getProjectList: function(){
				this.projectList = [];
				var vm = this;
				axios.get('api/getProjectList/' + vm.server.token + '/' + vm.server.server + '/' + vm.userId)
                .then(function (response){
                    vm.projectList = response.data;
                })
                .catch(function (error){
                    vm.projectList = 'An error occurred.' + error;
                });
			},
			validateServerAndUser: function(){
				this.info = [];
                var vm = this;

                vm.serverTemp = vm.server.server.replace(/[/]/g,']');

                vm.validated = false;
                vm.hideValidate = true;
                vm.server.tempToken = '';
                vm.userId = '';

                
                axios.post('api/createTempToken/' + vm.serverTemp + '/' + vm.server.username + '/' + vm.server.password)
                .then(function (response){
                    vm.info = response.data;
                })
                .catch(function (error){
                    vm.info = 'An error occurred.' + error;
                });
                setTimeout(function(){
                    vm.server.tempToken = vm.info.token;
                    vm.userId = vm.info.userId;
                    if (vm.server.tempToken != '' && vm.userId != '' && vm.server.tempToken != null && vm.userId != null) {
                    	vm.validated = true;
                    	vm.hideValidate = false;
                    	vm.noErrors = false;
                    	
                    	this.projectList = [];
						axios.get('api/getProjectList/' + vm.server.tempToken + '/' + vm.serverTemp + '/' + vm.userId)
		                .then(function (response){
		                    vm.projectList = response.data;
		                })
		                .catch(function (error){
		                    vm.projectList = 'An error occurred.' + error;
		                });



                    } else {
                    	vm.validated = false;
                    	vm.hideValidate = true;
                    	vm.noErrors = true;
                    }
                }, 1700); 
			},
			getProjectId: function(){
				var vm = this;
				for (var i = vm.projectList.projects.length - 1; i >= 0; i--) {
		                        if(vm.projectList.projects[i].name == vm.project){
		                            vm.server.project = vm.projectList.projects[i].id;
		                        }
		                    }
			},
			getToken: function(){
				this.tokenInfo = [];
				var vm = this;
				axios.post('api/getToken/' + vm.serverTemp + '/' + vm.server.username + '/' + vm.server.password + '/' + vm.server.project)
                .then(function (response){
                    vm.tokenInfo = response.data;
                })
                .catch(function (error){
                    vm.tokenInfo = 'An error occurred.' + error;
                });
                setTimeout(function(){
                	vm.server.tempToken = vm.tokenInfo;
				}, 1700);





			},
			goToCurrentUser(){
				this.$router.push('/currentUser');
			}
		},
		computed: {
            validateCreate() {
              return this.server.server == '' || this.server.username == '' || this.server.password == '';
            }
        },
	}

</script>