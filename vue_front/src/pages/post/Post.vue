<template>
  <post-template>
    <span slot="principal">
      <!--
      <div class="row">
        <div class="card">
          <div class="card-content">
            <div class="row valign-wrapper">
              <grid-vue tamanho="1">
                 <img class="circle responsive-img">
              </grid-vue>
              <grid-vue tamanho="11">
                <span class="black-text">
                   <strong>Nome</strong> - <small>Data</small>
                </span>
              </grid-vue>
            </div>
            <slot />
          </div>
          <div class="card-action">
            <p>
              <a style="cursor:pointer">
                <i class="material-icons"></i> 10
              </a>
              <a style="cursor:pointer" >
                <i class="material-icons">insert_comment</i>
              </a>
            </p>
            <p  class="right-align">
              <input type="text"  placeholder="Comentar">
              <button  class="btn waves-effect waves-light orange"><i class="material-icons">send</i></button>
            </p>
            <p >
              <ul class="collection">
                <li class="collection-item avatar" >
                  <img  class="circle">
                  <span class="titles">Username <small>- Criado em</small></span>
                  <p>
                   Descrição
                  </p>
                </li>
              </ul>
            </p>
          </div>
        </div>
      </div>
-->

    </span>
  </post-template>
</template>

<script>
import CardConteudoVue from '@/components/social/CardConteudoVue'
import CardDetalheVue from '@/components/social/CardDetalheVue'

import PostTemplate from '@/templates/PostTemplate'
import GridVue from '@/components/layouts/GridVue'

export default {
  name: 'Post',
  data () {
    return {}
  },

  components:{
    CardConteudoVue,
    CardDetalheVue,
    PostTemplate,
    GridVue
  },
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
