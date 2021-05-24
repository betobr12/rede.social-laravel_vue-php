<template>
  <span>
   <div v-if="url_image && url_image != '#'" class="card-image">
      <a v-if="url_image && url_image != '#'" :href="url_image" target="_blank">
        <img :src="url_image">
        <span class="card-title">{{title || ''}}</span>
      </a>

      <span v-if="!url_image || url_image == '#'">
        <img :src="url_image">
        <span class="card-title">{{title || ''}}</span>
      </span>

    </div>
    <div class="card-content">
       <span v-if="!url_image || url_image == '#'">
        <span class="card-title">{{title || ''}}</span>
      </span>
      <p>{{description}}</p>
      <p v-if="link && link != '#'"><a :href="link" target="_blank">Confira mais!</a></p>
    </div>
  </span>
</template>
<script>
export default {name:'CardDetalheVue',props:['url_image','title','description','link']
  }
</script>

<style>

</style>
