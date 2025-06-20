<template>

    <q-input dense filled :model-value="value" :label="label" >
        <template #append >
            <q-icon name="content_copy" class="_hover-mouse _hover" @click="copyToClipboard" />
        </template>
    </q-input>
    
</template>

<script>
export default {
    name: 'CopyClipboard',
    props: {
        label: String,
        value: String,
    },

    components: {
        
    },

    setup() {
        return {
            //
        };
    },

    methods: {
        copyToClipboard() {
            navigator.clipboard.writeText(this.value)
                .then(() => {
                    this.$toast.success('Copied to clipboard!')
                })
                .catch(err => {
                    this.$toast.error('Failed to copy:', err)
                });
        }
    }
};
</script>
