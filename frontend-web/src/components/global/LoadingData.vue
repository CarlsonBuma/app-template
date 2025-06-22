<template>

    <div class="w-100 flex justify-center q-pt-sm">
        <q-spinner-bars :size="size ? size : 'xl'" :color="colorIcon ? colorIcon : 'primary'" /><br>
        <div class="w-100 flex justify-center q-mt-sm">
            <span class="text-overline" :class="colorText ? colorText : 'text-grey'">{{ text ?? 'Loading' }}</span>
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
