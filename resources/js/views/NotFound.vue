<template>
  <div class="not-found">
    <h1>404 - Page Not Found</h1>
    <p>Oops! The sneakers you're looking for seem to have run away.</p>
    <div class="image-container">
      <img src="/images/404-notfound.jpg" alt="404 Sneakers" class="not-found-image">
    </div>
    <router-link to="/" class="home-link">Go back to homepage</router-link>
  </div>
</template>

<script>
export default {
  name: 'NotFound'
}
</script>

<style scoped>
.not-found {
  text-align: center;
  padding: 50px 20px;
  font-family: 'Kumbh Sans', sans-serif;
}

h1 {
  color: hsl(26, 100%, 55%);
  font-size: 2.5rem;
  margin-bottom: 20px;
}

p {
  color: hsl(219, 9%, 45%);
  font-size: 1.2rem;
  margin-bottom: 30px;
}

.image-container {
  width: 300px;
  height: 300px;
  margin: 0 auto 30px;
  border-radius: 50%;
  overflow: hidden;
  border: 4px solid hsl(26, 100%, 55%);
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #eeece8;
}

.not-found-image {
  width: 80%;
  height: 80%;
  object-fit: contain;
}

.home-link {
  display: inline-block;
  background-color: hsl(26, 100%, 55%);
  color: white;
  text-decoration: none;
  padding: 15px 30px;
  border-radius: 10px;
  font-weight: 700;
  transition: background-color 0.3s;
}

.home-link:hover {
  background-color: hsl(26, 100%, 65%);
}
</style>