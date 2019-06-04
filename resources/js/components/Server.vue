<template>
	<tr>
		<td>{{  server.server  }}</td>
		<td>{{  server.username  }}</td>
		<td>{{  server.password  }}</td>
		<td>{{  server.tempToken  }}</td>
		<td>{{  server.project  }}</td>
	</tr>
</template>

<script>
	export default{
		props:['server']
	}
</script>