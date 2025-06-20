<template>

    <q-card flat bordered class="q-my-xs q-mx-sm-xs">
        <q-card-section horizontal>
            
            <!-- Image -->
            <q-img
                width="100%"
                loading="eager"
                alt="image-upload"
                :fit="fit ?? 'fill'" 
                :src="src.img_src"
                :ratio="ratio || 1"
                
            >
                <!-- No image src-->
                <div class="absolute-full flex flex-center bg-primary" v-if="!src.img_src">
                    <q-icon name="contacts" size="xl"/>
                </div>

                <!-- Title -->
                <div class="absolute-bottom text-center text-white">
                    <slot name="caption">
                        <span class="w-100 text-h6">{{ title }}</span>
                    </slot>
                </div>
            </q-img>
            
            <input
                type="file"
                ref="fileInput"
                accept="image/*"
                @change="getImage"
                hidden
            />
        </q-card-section>

        <q-card-actions align="around">
            <q-btn @click="$refs.fileInput.click()" dense flat round color="primary" icon="restart_alt" />
            <q-btn @click="removeImage" dense flat round color="red" icon="delete" />
            <q-btn
                color="secondary" 
                icon="save" 
                flat round dense
                v-if="!noSubmit" 
                :disable="src.img_src && !src.file ? true : false"
                @click="updateAvatar"   
            />
        </q-card-actions>
    </q-card>
    
</template>

<script>
import { ref, getCurrentInstance } from 'vue';

export default {
    name: 'ImageUpload',

    props: {
        ratio: Number,
        title: String,
        width: String,
        fit: String,
        noSubmit: Boolean,
        url: String,
    },

    emits: [
        'upload', 
        'submit'
    ],

    setup(props, { emit }) {
        
        const imageSize = 20 * 1024 * 1024;
        const instance = getCurrentInstance();
        const $toast = instance?.appContext.config.globalProperties.$toast;
        const fileInput = ref(null);
        
        // File
        const src = ref({
            img_src: props.url
        });
        
        // Upload
        const getImage = (event) => {
            src.value.file = null;
            src.value.file = event.target.files[0];
            if (!src.value.file) return;
            if (src.value.file.size > imageSize) {
                $toast.error(`Ops, file is bigger than ${imageSize / 1024 / 1024} MB.`)
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                src.value.img_src = e.target.result;
            };

            reader.readAsDataURL(src.value.file);
            emit('upload', src.value.file, src.value.img_src)
            event.target.value = "";
        };

        const removeImage = () => {
            src.value.img_src = '';
            src.value.file = null;
            if (fileInput.value) {
                fileInput.value.value = null;
            }
        };

        const updateAvatar = () => {
            emit('submit', src.value);
        };

        return {
            src,
            getImage,
            removeImage,
            updateAvatar
        };
    }
};
</script>

