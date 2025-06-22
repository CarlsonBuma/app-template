<template>

    <div class="w-100 flex justify-center q-pa-md">
        <div class="w-title-lg">
            <span class="text-caption" v-if="header"><em>{{ header }}</em></span>
            <h1 class="text-h4 q-mt-xs q-mb-xs" v-if="title">{{ title }}</h1>
            <div class="q-py-sm">
                <slot />
            </div>
        </div>
    </div>
    
</template>

<script>
export default {
    name: 'SectionTitle',
    props: {
        header: String,
        title: String,
        subtitle: String,
    },
};
</script>
