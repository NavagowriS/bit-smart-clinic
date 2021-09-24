<template>

  <div>

    <TopNavigationBar/>

    <div class="container">

      <div class="row">
        <div class="col">

          <div class="card">
            <div class="card-body">
              <div class="card-title">Manage Users</div>

              <p>The table contains available users in the system.
                <router-link to="/users/create">Create new user</router-link>
              </p>
              <table class="table table-bordered table-sm">
                <thead>
                <tr>
                  <th>Full name</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Role</th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="user in users" :key="user.id">
                  <td>
                    <router-link :to="'/users/edit/' + user.id">{{ user.full_name }}</router-link>
                  </td>
                  <td>{{ user.username }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.role }}</td>
                </tr>
                </tbody>
              </table>

            </div>
          </div>

        </div>
      </div>

    </div>

  </div>

</template>

<script>

import TopNavigationBar from '@/components/TopNavigationBar.vue';

export default {
  name: 'ManageUsers',
  components: { TopNavigationBar },

  data() {
    return {
      users: [],
    };
  },

  async mounted() {

    try {

      await this.$store.dispatch( 'users/fetchAll' );

      this.users = this.$store.getters[ 'users/getUsers' ];

    } catch ( e ) {
      console.log( e );
    }

  },
};
</script>

<style scoped>

</style>
