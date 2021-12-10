<template>
	
	<div class="container my-5">
		<div class="row justify-content-center">
			<div class="col-12 col-md-6">
				
				<div class="card">
					
					<div class="card-header">
						<div class="fw-bold text-center">Login to Smart Clinic</div>
					</div>
					
					<div class="img-fluid text-center my-2">
						<img :src="icons.hospital" alt="Clinic" class="login-logo">
					</div>
					
					<div class="card-body">
						
						<div class="row justify-content-center">
							<div class="col-8">
								
								<form @submit.prevent="onLogin">
									<div class="mb-3">
										<label for="txt-username" class="form-label fw-bold">Username</label>
										<input id="txt-username" type="text" class="form-control" v-model="username">
									</div>
									
									<div class="mb-3">
										<label for="txt-password" class="form-label fw-bold">Password</label>
										<input id="txt-password" type="password" class="form-control" v-model="password">
									</div>
									
									<div class="text-center">
										<button class="btn btn-success" type="submit">Login</button>
									</div>
								</form>
							
							</div>
						</div>
						
						<div v-if="error" class="mt-3">
							<div class="alert alert-danger">
								{{ error }}
							</div>
						</div>
					
					</div>
				
				</div><!-- card -->
			
			</div>
		</div>
	</div>

</template>

<script>
export default {
	name: 'Login',
	
	data() {
		return {
			
			icons: {
				hospital: require( '@/assets/images/icons/hospital.svg' ).default,
			},
			
			username: '',
			password: '',
			
			error: '',
			
		};
	},
	
	methods: {
		
		onLogin: async function () {
			
			const userParams = {
				username: this.username,
				password: this.password,
			};
			
			try {
				
				await this.$store.dispatch( 'auth/auth_login', userParams );
				
				const userType = this.$store.getters[ 'auth/getUserType' ];
				
				if ( userType === 'DOCTOR' ) {
					await this.$router.push( { name: 'pageDoctorHome' } );
				} else if ( userType === 'PHARMACIST' ) {
					await this.$router.push( { name: 'PagePharmaHome' } );
				} else {
					await this.$router.push( '/' );
				}
				
				
			} catch ( e ) {
				console.log( e );
				this.error = 'Login failed. Please check your username and password';
			}
			
			
		},
		
	},
	
};
</script>

<style scoped>

.login-logo {
	width: 150px;
}

</style>
