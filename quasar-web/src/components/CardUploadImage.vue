<template>

    <q-card flat bordered class="q-ma-sm-xs q-my-xs">
        <q-card-section horizontal>
            <q-img
                v-if="avatar"
                :src="avatar"
                :ratio="1"
                alt="image-avatar"
                fit="fill" 
                loading="eager"
            >
                <div v-if="name" class="absolute-bottom text-center">
                    <span class="w-100 text-h6">{{ name }}</span>
                </div>
            </q-img>
            
            <q-icon
                v-else
                name="contacts"
                class="col q-ma-md" 
                :size="width ? width : '320px'" 
            />
            
            <input
                type="file"
                name="image"
                ref="file"
                accept="image/*"
                @change="(event) => getImage(event)"
                hidden
            />
        </q-card-section>

        <q-card-actions align="around">
            <q-btn
                @click="$refs.file.click()" 
                flat 
                round 
                color="primary"
                icon="restart_alt" 
            />
            <q-btn 
                @click="removeImage()"
                flat 
                round 
                color="red" 
                icon="delete" 
            />
            <q-btn
                v-if="allowUpdate"
                @click="updateAvatar(upload_image_src, removeAvatar)" 
                flat 
                round 
                color="secondary" 
                icon="save" 
            />
        </q-card-actions>
    </q-card>

</template>

<script>
import { ref } from 'vue'

export default {
    name: 'CardUploadImage',

    props: {
        avatarWidth: String,
        userAvatar: String,
        allowUpdate: Boolean,
        name: String,
        slogan: String,
        width: String
    },

    emits: [
        'upload',
        'update'
    ],

    setup(props) {
        return {
            avatar: ref(props.userAvatar),
            imageSize: 2000000,
            upload_image_src: null,
            removeAvatar: ref(false),
        };
    },

    methods: {

        updateAvatar(upload_image_src, removeAvatar) {
            this.$emit('update', upload_image_src, this.avatar, removeAvatar);
            this.removeAvatar = false;
            this.upload_image_src = null;
        },

        getImage(event) {
            try {
                const imageSize = this.imageSize;
                this.upload_image_src = event.target.files[0];
                if ( this.upload_image_src.size > imageSize) throw 'Ups, the size is bigger than ' + imageSize / 1000000 + ' MB'
                let reader = new FileReader();
                reader.readAsDataURL( this.upload_image_src);
                reader.onload = (e) => {
                    this.avatar = e.target.result;
                };
                this.removeAvatar = false;
                this.$emit('upload', this.upload_image_src, this.avatar)
            } catch (error) {
                this.$toast.error(error)
            }
        },

        removeImage() {
            this.removeAvatar = true;
            this.$refs.file.value = '';
            this.upload_image_src = null;
            this.avatar = '';
            this.$emit('upload', this.upload_image_src, this.avatar)
        },
    },
};
</script>


