<template>

    <q-infinite-scroll 
        :disable="disable"
        :offset="offset ?? 500"
        @load="(index, done) => onLoadRef(index, done)" 
    >
        <!-- Content -->
        <slot />

        <!-- Loading -->
        <div v-if="loading" class="row justify-center">
            <LoadingData text="Loading data..." />
        </div>
    </q-infinite-scroll>
    
</template>

<script>
import { ref } from 'vue'

export default {
    name: 'InfiteScroll',
    props: {
        offset: Number,
        loading: Boolean,
        disable: Boolean,
    },

    emits: [
        'load'
    ],

    setup (props, context) {
        const scrollTargetRef = ref(null)
        const onLoadRef =  (index, done) => {
            context.emit('load')
            done();
        }

        return {
            scrollTargetRef,
            onLoadRef
        };
    }
};
</script>