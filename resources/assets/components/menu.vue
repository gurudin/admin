<template>
<ul class="nav flex-column">

  <menu-child
    v-for="(tree,inx) in dataTree"
    :model="tree"
    :data-group="dataGroup"
    :current-uri="currentUri"
    :key="inx">
  </menu-child>

</ul>
</template>

<script>
export default {
  props: {
    dataTree: Array,
    dataGroup: null,
    currentUri: String,
  }
};
</script>

