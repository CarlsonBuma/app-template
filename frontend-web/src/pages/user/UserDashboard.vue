<template>

    <PageWrapper >
        <!-- Content -->
        <CardSimple title="Voice Recorder" class="q-my-lg">
            <q-card-section>
                <ul>
                    <li v-for="(url, index) in recordings" :key="index">
                        <audio :src="url" controls></audio>
                    </li>
                </ul>
            </q-card-section>
            <q-separator />
            <q-card-section>
                <button @click="startRecording" :disabled="isRecording">Start Recording</button>
                <button @click="stopRecording" :disabled="!isRecording">Stop Recording</button>
            </q-card-section>
        </CardSimple>    
    </PageWrapper>

</template>

<script>
import { ref } from 'vue';

export default {
    name: 'UserDashboard',
    components: {
        // Components
    },

    setup() {
        const isRecording = ref(false);
        const recordings = ref([]);
        const mediaRecorder = ref(null);
        const audioChunks = ref([]);
        return {
            isRecording,
            recordings,
            mediaRecorder,
            audioChunks 
        }
    },

    data() {
        return {
            // Code
        }
    },

    mounted() {
        // Code
    },

    methods: {
        async startRecording() {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            this.mediaRecorder = new MediaRecorder(stream);
            this.audioChunks = [];
            this.isRecording = true;

            this.mediaRecorder.ondataavailable = event => {
                this.audioChunks.push(event.data);
            };

            this.mediaRecorder.onstop = () => {
                const blob = new Blob(this.audioChunks, { type: 'audio/wav' });
                const url = URL.createObjectURL(blob);
                this.recordings.push(url);
                this.isRecording = false;
            };

            this.mediaRecorder.start();
        },

        async stopRecording() {
            if (this.mediaRecorder && this.isRecording) {
                this.mediaRecorder.stop();
            }
        }
    }
};
</script>
