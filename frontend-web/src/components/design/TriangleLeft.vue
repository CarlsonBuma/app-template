<style scoped>
.design-triangle {
    top: 0;
    position: fixed;
    width: 100%;
    height: 100vh;
    background-image: linear-gradient(135deg, #243b3fbd 30%, #243f3700 30%); /* Gradual blending for a softer transition */
}
</style>

<template> 
    <div id="app-design" class="design-triangle"></div>
</template>

<script>
export default {
    name: 'TriangleLeft',
}
</script>