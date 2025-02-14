<template>

    <div class="w-100 q-ma-sm text-center">
        <span v-if="text" class="text-caption text-grey">{{ text }}</span>
        <span v-else class="text-caption text-grey">
            <slot />
        </span>
    </div>
    
</template>

<script>
export default {
    name: 'SectionNote',
    props: {
        text: String,
    },
};
</script>
