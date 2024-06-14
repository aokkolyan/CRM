<template>
  <div class="form-group"></div>
</template>

<script>
export default {
  created() {
    this.$store.dispatch("destroyToken").then(response => {
      this.$router.push({ path: "/user/login" }).catch(error => {
        console.info(error)
      });
    });
  }
};
</script>