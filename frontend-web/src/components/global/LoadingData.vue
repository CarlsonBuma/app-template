<template>

    <div class="w-100 flex justify-center q-py-md">
        <q-spinner-bars :size="size ? size : '64px'" :color="colorIcon ? colorIcon : 'primary'" /><br>
        <div v-if="text" class="w-100 flex justify-center q-mt-sm">
            <span class="text-overline" :class="colorText ? colorText : 'text-grey'">{{ text }}</span>
        </div>
    </div>
    
</template>

<script>
export default {
    name: 'LoadingData',
    props: {
        text: String,
        colorIcon: String,
        colorText: String,
        size: String,
    },
};
</script>
