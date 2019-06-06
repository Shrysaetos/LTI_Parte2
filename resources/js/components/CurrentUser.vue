<template>
	<div>
	<div class="jumbotron">
            <h1>Current User</h1>
     </div>
	<table class="table table-bordered table-sptried table-condensed">
		<thead>
			<tr>
				<th>Server</th>
				<th>Username</th>
				<th>Password</th>
				<th>Temporary Token</th>
				<th>Project</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{{  servers[servers.length-1].server  }}</td>
				<td>{{  servers[servers.length-1].username  }}</td>
				<td>{{  servers[servers.length-1].password  }}</td>
				<td>{{  servers[servers.length-1].tempToken  }}</td>
				<td>{{  servers[servers.length-1].project  }}</td>
			</tr>
		</tbody>
		
	</table>
	</div>
</template>

<script>
	export default {

		data(){
			return{
				servers: [],
			}
		},
		created (){
			this.fetchServer();
		},

		methods:{
			fetchServer(){
				this.$http.get("server").then( response => { this.servers = response.data.servers});
			},
		}
	}

</script>