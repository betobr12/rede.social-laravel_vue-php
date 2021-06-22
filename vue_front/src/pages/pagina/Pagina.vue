<template>
  <site-template>
    <span slot="menuesquerdo">
      <div class="row valign-wrapper">
        <grid-vue tamanho="4">
          <router-link :to="'/pagina/'+userPage.id+'/'+$slug(userPage.name,{lower: true})">
           <img :src="userPage.url" :alt="userPage.name" class="circle responsive-img"> <!-- notice the "circle" class -->
          </router-link>

        </grid-vue>
        <grid-vue tamanho="8">
          <span class="black-text">
            <router-link :to="'/pagina/'+userPage.id+'/'+$slug(userPage.name,{lower: true})">
              <h5>{{ userPage.name }}</h5>
            </router-link>
            <p>{{ userPage.description }}</p>
          </span>
          <span>
            <button type="button" v-if="showButton" @click="friend(userPage.id)" class="btn-floating btn-large waves-effect waves-light color red">
              <i class="medium material-icons">{{ textBtn }}</i>
              </button>
          </span>
        </grid-vue>
      </div>
    </span>

    <span slot="menuesquerdoamigos">
      <span><i class="medium material-icons">people</i></span>
      <router-link v-for="item in friends" :key="item.id" :to="'/pagina/'+item.id+'/'+$slug(item.name,{lower: true})">
        <li  >{{ item.name }}</li>
      </router-link>
      <li v-if="!friends.length">Nenhum amigo</li>
      <span><i class="medium material-icons">fast_forward</i></span>
      <router-link v-for="item in followers" :key="item.id" :to="'/pagina/'+item.id+'/'+$slug(item.name,{lower: true})">
        <li  >{{ item.name }}</li>
      </router-link>
      <li v-if="!followers.length">Nenhum amigo</li>

    </span>

    <span slot="principal">
      <publicar-conteudo-vue />
      <card-conteudo-vue v-for="item in listaConteudos" :key="item.id"
        :content_id="item.id"
        :liked_content="item.liked_content"
        :total_likes="item.total_likes"
        :comments="item.comments"
        :profile="item.user_url"
        :name="item.user_name"
        :user_id="item.user_id"
        :data="item.created_at">
          <card-detalhe-vue
            :url_image="item.url_image"
            :title="item.title"
            :description="item.description"
            :link="item.link" />
      </card-conteudo-vue>     
      <div v-scroll="handleScroll"></div>
    </span>
  </site-template>
</template>

<script>
import CardConteudoVue from '@/components/social/CardConteudoVue'
import CardDetalheVue from '@/components/social/CardDetalheVue'
import PublicarConteudoVue from '@/components/social/PublicarConteudoVue'
import SiteTemplate from '@/templates/SiteTemplate'
import GridVue from '@/components/layouts/GridVue'

export default {
  name: 'Pagina',
  data () {
    return {
      user: false,
      urlNextPage: null,
      stopScroll: false,
      showButton: false,
      userPage: { name: '', url: '', description: '' },
      friends:[],
      friendsLogged:[],
      textBtn:'person_add',
      followers:[]
    }
  },

  created() {
    this.updatePage();
  },
  
  components:{
    CardConteudoVue,
    CardDetalheVue,
    PublicarConteudoVue,
    SiteTemplate,
    GridVue
  },

  watch:{
    '$route':'updatePage',
  },

  methods: {
    updatePage() {
      let usuarioAux = this.$store.getters.getUsuario;
      if(usuarioAux){
        this.user = this.$store.getters.getUsuario;
        this.$http.get(this.$urlAPI+'content/page/'+this.$route.params.id,{"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
        .then(response => {
          if (response.data.content) {            
            this.$store.commit('setContentsTimeLine',response.data.content.data);
            this.urlNextPage = response.data.content.next_page_url;
            this.userPage = response.data.data_user_page;
            if (this.userPage.id != this.user.id) {
              this.showButton = true;
            } else {
              this.showButton = false;
            }            
          this.$http.get(this.$urlAPI+'user/list_friend_page/'+this.userPage.id,{"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
          .then(response => {
            if (response.data.success) {
              this.friends       = response.data.friends;
              this.friendsLogged = response.data.user_logged;
              this.followers     = response.data.followers;
              this.isFriend();
            } else {
              alert(response.data.error);
            }
          })
            .catch(e => {
              console.log(e)
              alert('Erro! Tente novamente mais tarde!')
            })
          }
        })
        .catch(e => {
          console.log(e)
          alert('Erro! Tente novamente mais tarde!')
        })
      }
    },

    isFriend() {
      for (let friend of this.friendsLogged) {
        if (friend.id == this.userPage.id) {
          this.textBtn = 'remove_circle_outline';
          return;
        }
      }
      return this.textBtn = 'person_add';
    },

    friend(id) {
      this.$http.post(this.$urlAPI+'user/friend',{id:id},
        {"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
        .then(response => {
          if (response.data.success) {
            this.friendsLogged = response.data.friends;
            this.followers     = response.data.followers;
            this.isFriend();
          } else {
            alert(response.data.error);
          }
        }).catch(e => {
        console.log(e)
        alert('Erro! Tente novamente mais tarde!')
        }
      );
    },

    handleScroll() {      
      if (this.stopScroll) {
        return;
      }      
      if (window.scrollY >= document.body.clientHeight - 949) {
        this.stopScroll = true;       
        this.pageLoad();
      }
    },

    pageLoad() {
      if (!this.urlNextPage) {
        return;
      }
      this.$http.get(this.urlNextPage,{"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
      .then(response => {        
        if (response.data.content && this.$route.name == "Pagina") {
          this.$store.commit('setPaginationContentsTimeLine',response.data.content.data);
          this.urlNextPage = response.data.content.next_page_url;
          this.stopScroll = false;
        }
      })
      .catch(e => {
        console.log(e)
        alert('Erro! Tente novamente mais tarde!')
      })
    },
  },

  computed: {
    listaConteudos() {
     return this.$store.getters.getContentsTimeLine;
    }
  }
}

</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
