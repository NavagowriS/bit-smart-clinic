<template>
	
	<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
		<div class="container-fluid">
			<router-link to="/" class="navbar-brand">
				<img :src="icons.appIcon" alt="Smart Clinic" class="icon-24">
			</router-link>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
							aria-expanded="false"
							aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<!-- left side -->
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					
					<li class="nav-item">
						<router-link to="/doctors" class="nav-link">Doctors</router-link>
					</li>
					<li class="nav-item">
						<router-link to="/patients" class="nav-link">Patients</router-link>
					</li>
					
					<!-- clinics -->
					<li class="nav-item dropdown">
						<router-link to="/clinics" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
												 aria-expanded="false">
							Clinics
						</router-link>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
							<li>
								<router-link class="dropdown-item" to="/clinics">All clinics</router-link>
							</li>
							<li>
								<hr class="dropdown-divider">
							</li>
							<li v-for="item in clinicsList">
								<router-link class="dropdown-item" :to="{name:'pageClinic', params:{id: item.id}}">
									{{ item.title }}
								</router-link>
							</li>
						
						</ul>
					</li>
				</ul>
				
				<!-- right side -->
				<div class="d-flex align-items-center">
          <span v-if="loginState" class="me-2">
            <span class="navbar-text fw-bold">Welcome {{ loggedInUser.full_name }}</span>
          </span>
					<router-link to="/login" class="btn btn-success btn-sm me-2" v-if="!loginState">Login</router-link>
					
					<router-link to="/users" class="btn btn-outline-success btn-sm me-2" v-if="loginState && userType === 'ADMIN'"><i class="bi bi-people-fill"></i>Manage
						users
					</router-link>
					
					<button class="btn btn-danger btn-sm" @click="logout" v-if="loginState">Logout</button>
				</div>
			</div>
		</div>
	</nav>

</template>

<script>
export default {
	name: 'TopNavigationBar',
	
	data() {
		return {
			icons: {
				appIcon: require( '@/assets/images/logo.svg' ).default,
			},
		};
	},
	
	computed: {
		
		loginState: function () {
			return this.$store.getters[ 'auth/getLoginStatus' ];
		},
		
		loggedInUser: function () {
			return this.$store.getters[ 'auth/getLoggedInUser' ];
		},
		
		userType: function () {
			return this.$store.getters[ 'auth/getUserType' ];
		},
		
		clinicsList() {
			return this.$store.getters[ 'clinics/getClinicsList' ];
		},
		
	},
	
	async mounted() {
		try {
			
			await this.$store.dispatch( 'clinics/fetchAll' );
			
		} catch ( e ) {
		
		}
	},
	
	methods: {
		
		async logout() {
			
			try {
				await this.$store.dispatch( 'auth/auth_logout' );
				await this.$router.push( '/login' );
				
			} catch ( e ) {
				console.log( e );
				alert( 'Failed to logout' );
			}
			
		}, /* logout */
		
		
	},
	
};
</script>

<style scoped>

</style>
