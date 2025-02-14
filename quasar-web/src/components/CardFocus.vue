<template>

    <q-card class="q-ma-sm-xs q-my-xs" bordered>
        <div class="flex justify-center">
            <q-icon :name="icon" color="primary" size="220px" />
        </div>
        <q-card-section>
            <div class="column">
                <span class="text-h6 ellipsis">{{ title }}</span>
                <p class="text-caption">{{ description }}</p>
                <slot />
            </div>
        </q-card-section>
        <q-separator class="w-100"/>
        <q-card-section class="row align-top">
            <p class="q-mb-none">
                <slot name="note" />
            </p>
        </q-card-section>
    </q-card>
    
</template>

<script>
export default {
    name: 'CardFocus',
    props: {
        icon: String,
        title: String,
        description: String
    },
};
</script>
