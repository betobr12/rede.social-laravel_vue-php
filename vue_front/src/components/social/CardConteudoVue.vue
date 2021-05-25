<template>

<div class="row">
    <div class="card">
      <div class="card-content">
        <div class="row valign-wrapper">
          <grid-vue tamanho="1">
            <img :src="profile" :alt="name" class="circle responsive-img"> <!-- notice the "circle" class -->
          </grid-vue>
          <grid-vue tamanho="11">
            <span class="black-text">
              <strong>{{name}}</strong> - <small>{{data}}</small>

            </span>
          </grid-vue>
        </div>

        <slot />

      </div>
      <div class="card-action">
        <p>
          <a style="cursor:pointer"  @click="likeContent(content_id)">
            <i class="material-icons">{{ like }}</i> {{ totalLikes }}
          </a>

          <a style="cursor:pointer" @click="openComment(comment_id)">
            <i class="material-icons">insert_comment</i> 22
          </a>

        </p>
      </div>
    </div>

</div>

</template>

<script>
import GridVue from '@/components/layouts/GridVue'

export default {
  name: 'CardConteudoVue',
  props:['content_id','profile','name','data','total_likes','liked_content'],
  data () {
    return {
      like: this.liked_content ? 'favorite' : 'favorite_border',
      totalLikes: this.total_likes,

    }
  },
  components:{
    GridVue
  },
  methods: {
    likeContent(content_id) {
        this.$http.put(this.$urlAPI+'like/'+content_id,
        {},
        {"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
        .then(response => {
          if (response.status) {
            this.totalLikes = response.data.likes;
            this.$store.commit('setContentsTimeLine',response.data.content);

          if (this.like == 'favorite_border') {
            this.like = 'favorite';
          } else {
            this.like = 'favorite_border';
          }
          } else {
            alert(response.data.error);
          }
        }).catch(e => {
        console.log(e)
        alert('Erro! Tente novamente mais tarde!')
        }
      );
    }

  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
