<template>

    <div 
        class="w-100 flex justify-center q-py-xl"
        :class="$tp.client_preferences.value.darkmode ? 'bg-dark text-white' : 'bg-grey-1 text-dark'"
    >
        <div class="w-content row justify-center q-py-md-md">
            <slot />
        </div>
    </div>
    
</template>

<script>
export default {
    name: 'SectionDesignClear',
    props: {
        // 
        
    },
};
</script>
