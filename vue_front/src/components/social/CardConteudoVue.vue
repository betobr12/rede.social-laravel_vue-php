<template>

<div class="row">
    <div class="card">
      <div class="card-content">
        <div class="row valign-wrapper">
          <grid-vue tamanho="1">
            <router-link :to="'/pagina/'+user_id+'/'+$slug(name,{lower: true})">
             <img :src="profile" :alt="name" class="circle responsive-img"> <!-- notice the "circle" class -->
            </router-link>
          </grid-vue>
          <grid-vue tamanho="11">
            <span class="black-text">
              <router-link :to="'/pagina/'+user_id+'/'+$slug(name,{lower: true})">
               <strong>{{name}}</strong> - <small>{{data}}</small>
              </router-link>
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

          <a style="cursor:pointer" @click="openComment()">
            <i class="material-icons">insert_comment</i> {{ listComments.length }}
          </a>
        </p>

        <p v-if="showComment" class="right-align">
          <input type="text" v-model="descriptionComment" placeholder="Comentar">
          <button v-if="descriptionComment" @click="comment(content_id)" class="btn waves-effect waves-light orange"><i class="material-icons">send</i></button>
        </p>
        <p v-if="showComment">
          <ul class="collection">
            <li class="collection-item avatar" v-for="item in comments" :key="item.comment_id">
              <img :src="item.user_comment_url" alt="" class="circle">
              <span class="titles">{{ item.user_comment_name }} <small>- {{ item.created_at }}</small></span>
              <p>
                {{ item.description }}
              </p>
            </li>
          </ul>
        </p>
      </div>
    </div>

</div>

</template>

<script>
import GridVue from '@/components/layouts/GridVue'

export default {
  name: 'CardConteudoVue',
  props:['content_id','profile','name','data','total_likes','liked_content','comments','user_id'],
  data () {
    return {
      like: this.liked_content ? 'favorite' : 'favorite_border',
      totalLikes: this.total_likes,
      showComment: false,
      descriptionComment: '',
      listComments: this.comments || []
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
            console.log(response.data.content);
            this.totalLikes = response.data.likes; //valor do like não atualiza corretamente
            this.$store.commit('setContentsTimeLine',response.data.content.data);

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
    },
    openComment() {
      this.showComment = !this.showComment;
    },

    comment(comment_id) {
      if (!this.descriptionComment) {
        return;
      }
      this.$http.put(this.$urlAPI+'comment/'+comment_id,{description: this.descriptionComment},

      {"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
      .then(response => {
        if (response.status) {
          console.log(response.data.content);
          alert('Comentario inserido com sucesso');
          this.descriptionComment = "";
          this.$store.commit('setContentsTimeLine',response.data.content.data);
        } else {
          alert(response.data.error);
        }
      }).catch(e => {
      console.log(e)
      alert('Erro! Tente novamente mais tarde!')
      });
    },
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
