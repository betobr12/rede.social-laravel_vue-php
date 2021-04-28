<template>
  <span>
    <header>
      <nav-bar-vue logo="Social" url="/" cor="blue-grey">
          <li><router-link to="/">Home</router-link></li>
          <li><router-link to="/login">Entrar</router-link></li>
          <li><router-link to="/cadastro">Cadastre-se</router-link></li>
      </nav-bar-vue>
    </header>

    <main>
      <div class="container">
        <div class="row">
          <grid-vue tamanho="6">
            <card-menu-vue>
            <slot name="menuesquerdo" />
            </card-menu-vue>
          </grid-vue>
          <grid-vue tamanho="6">
            <slot name="principal" />
          </grid-vue>
        </div>
      </div>
    </main>
    <footer-vue cor="blue-grey" logo="Social" descricao="Teste de descrição" ano="2021" >
            <li><a class="grey-text text-lighten-3" href="#!">Home</a></li>
            <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
            <li><a class="grey-text text-lighten-3" href="#!">Link 3</a></li>
            <li><a class="grey-text text-lighten-3" href="#!">Link 4</a></li>
    </footer-vue>
  </span>
</template>

<script>

import NavBarVue  from '@/components/layouts/NavBarVue'
import FooterVue from '@/components/layouts/FooterVue'
import GridVue from '@/components/layouts/GridVue'
import CardMenuVue from '@/components/layouts/CardMenuVue'

export default {
  name: 'SiteTemplate',
  components: {
    NavBarVue,
    FooterVue,
    GridVue,
    CardMenuVue
  }
}
</script>

<style>

</style>
