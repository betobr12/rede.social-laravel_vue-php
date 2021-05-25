<template>
  <site-template>
    <span slot="menuesquerdo">
      <div class="row valign-wrapper">
        <grid-vue tamanho="4">
          <img :src="user.url" :alt="user.name" class="circle responsive-img"> <!-- notice the "circle" class -->
        </grid-vue>
        <grid-vue tamanho="8">
          <span class="black-text">
            <h5>{{ user.name }}</h5>
            {{ user.description }}
          </span>
        </grid-vue>
      </div>
    </span>

    <span slot="principal">
      <publicar-conteudo-vue />
      <card-conteudo-vue v-for="item in listaConteudos" :key="item.id"
        :content_id="item.id"
        :liked_content="item.liked_content"
        :total_likes="item.total_likes"
        :profile="item.user.url"
        :name="item.user.name"
        :data="item.created_at">
          <card-detalhe-vue
            :url_image="item.url_image"
            :title="item.title"
            :description="item.description"
            :link="item.link" />
      </card-conteudo-vue>


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
  name: 'Home',
  data () {
    return {
      user: false,
    }
  },
  created() {
    let usuarioAux = this.$store.getters.getUsuario; // para resgatar os valores da sessao criados no login.vue
    if(usuarioAux){
      this.user = this.$store.getters.getUsuario;
      this.$http.get(this.$urlAPI+'content',{"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
      .then(response => {
        console.log(response);
        if (response.data.content)
        {
          this.$store.commit('setContentsTimeLine',response.data.content.data);
        }
      })
      .catch(e => {
        console.log(e)
        alert('Erro! Tente novamente mais tarde!')
      })
    }
  },
  components:{
    CardConteudoVue,
    CardDetalheVue,
    PublicarConteudoVue,
    SiteTemplate,
    GridVue
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
