'use strict';

import Alpine from 'alpinejs';
import api from './api';
import { toast } from '../base/toast';

export function videoView() {
    Alpine.data('video', (model, services = [], video = null) => ({
        services: services,
        model: null,

        history: [],
        historyLoaded: false,

        isProcessing: false,
        isDeleting: false,
        preview: null,

        prompt: null,
        negativePrompt: null,
        frames: [],

        params: {},
        original: {},

        timer: 0,
        form: true,

        init() {
            this.$watch('preview', (value) => {
                // Update the item in the history list
                if (this.history && value) {
                    let index = this.history.findIndex(item => item.id === value.id);
                    if (index >= 0) {
                        this.history[index] = value;
                    }
                }
            });

            this.selectModel(model);
            this.$watch('model', () => this.reset());

            if (video) {
                this.select(video);
            }

            this.fetchHistory();
        },

        selectModel(key) {
            let found = false;
            this.services.forEach(service => {
                service.models.forEach(model => {
                    if (model.key == key) {
                        this.model = { ...model, service: service };
                        found = true;
                    }
                });
            });

            if (!found && this.services.length > 0 && this.services[0].models.length > 0) {
                this.model = {
                    ...this.services[0].models[0],
                    service: this.services[0]
                };

                found = true;
            }

            if (!found) {
                this.model = null;
            }
        },

        fetchHistory() {
            let params = {
                limit: 25
            };

            if (this.history && this.history.length > 0) {
                params.starting_after = this.history[this.history.length - 1].id;
            }

            api.get('/library/videos', params)
                .then(response => response.json())
                .then(list => {
                    let data = list.data;
                    this.history.push(...data);

                    if (data.length < params.limit) {
                        this.historyLoaded = true;
                    }
                })
        },

        reset() {
            for (let key in this.params) {
                if (this.original[key] === undefined) {
                    delete this.params[key];
                    continue;
                }

                this.params[key] = this.original[key];
            }

            this.frames = [];
        },

        submit($el) {
            if (this.isProcessing) {
                return;
            }

            this.isProcessing = true;
            this.$nextTick(() => this.preview = null);

            let data = {
                ...this.params,
                prompt: this.prompt,
                negative_prompt: this.negativePrompt,
                model: this.model.key || null,
            };

            let body = new FormData();
            for (let key in data) {
                body.append(key, data[key]);
            }

            for (let frame of this.frames) {
                body.append('frames[]', frame);
            }

            api.post(`/ai/videos`, body)
                .then(response => response.json())
                .then(video => {
                    this.history.unshift(video);
                    this.select(video);

                    setTimeout(() => {
                        this.prompt = null;
                        this.isProcessing = false;
                    }, 1000);
                })
                .catch(error => {
                    this.isProcessing = false;
                    this.preview = null;

                    let url = new URL(window.location.href);
                    url.pathname = '/app/video/';
                    window.history.pushState({}, '', url);
                });
        },

        select(video) {
            this.preview = video;
            this.form = false;

            let url = new URL(window.location.href);
            url.pathname = '/app/video/' + video.id;
            window.history.pushState({}, '', url);

            this.checkProgress();
        },

        remove(video) {
            this.isDeleting = true;

            api.delete(`/library/videos/${video.id}`)
                .then(() => {
                    this.preview = null;
                    this.form = true;

                    window.modal.close();

                    toast.show("Video has been deleted successfully.", 'ti ti-trash');
                    this.isDeleting = false;

                    let url = new URL(window.location.href);
                    url.pathname = '/app/video/';
                    window.history.pushState({}, '', url);

                    this.history.splice(this.history.indexOf(video), 1);
                })
                .catch(error => this.isDeleting = false);
        },

        checkProgress() {
            if (this.preview.state >= 3) {
                return;
            }

            api.get(`/library/videos/${this.preview.id}`)
                .then(response => response.json())
                .then(video => {
                    this.preview = video;
                    setTimeout(() => this.checkProgress(), 5000);
                });
        },

        save(video) {
            api.post(`/library/videos/${video.id}`, {
                title: video.title,
            }).then((resp) => {
                // Update the item in the history list
                if (this.history) {
                    let index = this.history.findIndex(item => item.id === resp.data.id);

                    if (index >= 0) {
                        this.history[index] = resp.data;
                    }
                }
            });
        },

        addFrame($event) {
            const files = Array.from($event.target.files);
            const limit = this.model.config.frames.limit || 1;

            this.frames = [
                ...this.frames,
                ...files.slice(0, limit - this.frames.length)
            ];

            $event.target.value = null;
            window.modal.open('options');
        },

        removeFrame(frame) {
            this.frames = this.frames.filter(f => f !== frame);
        },

        actionNew() {
            this.prompt = null;
            this.negativePrompt = null;
            this.params = {};
            this.frames = [];

            this.form = true;

            let url = new URL(window.location.href);
            url.pathname = '/app/video/';
            window.history.pushState({}, '', url);

            this.$nextTick(() => {
                this.$refs.prompt.focus();
            });
        },

        actionEdit() {
            this.prompt = this.preview.params?.prompt || null;
            this.negativePrompt = this.preview.params?.negative_prompt || null;
            this.frames = [];

            let framePromises = (this.preview.params.frames || []).map((frame, index) => {
                return new Promise((resolve) => {
                    this.fileFromUrl(frame, frame, (file) => {
                        resolve({ file, index });
                    });
                });
            });

            Promise.all(framePromises).then(results => {
                // Sort by original index and extract just the files
                this.frames = results
                    .sort((a, b) => a.index - b.index)
                    .map(result => result.file);
            });

            let params = { ...this.preview.params };
            delete params.prompt;
            delete params.negative_prompt;
            delete params.frames;

            this.selectModel(this.preview.model);
            this.form = true;

            this.$nextTick(() => {
                this.params = params;
            });

            let url = new URL(window.location.href);
            url.pathname = '/app/video/';
            window.history.pushState({}, '', url);

            this.$nextTick(() => {
                this.$refs.prompt.focus();
            });
        },

        fileFromUrl(url, filename, callback) {
            fetch(url)
                .then(response => response.blob())
                .then(blob => new File([blob], filename, { type: blob.type }))
                .then(callback);
        }
    }));
}