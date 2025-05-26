'use strict';

import Alpine from 'alpinejs';
import api from './api';
import { toast } from '../base/toast';

export function imagineView() {
    Alpine.data('imagine', (model, services = [], samples = [], image = null) => ({
        samples: samples,
        services: services,
        model: null,

        showSettings: false,

        history: [],
        historyLoaded: false,

        isProcessing: false,
        isDeleting: false,
        preview: null,

        prompt: null,
        negativePrompt: null,
        images: [],

        params: {},
        original: {},

        placeholder: null,
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

            if (image) {
                this.select(image);
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

            this.reset();
        },

        fetchHistory() {
            let params = {
                limit: 24
            };

            if (this.history && this.history.length > 0) {
                params.starting_after = this.history[this.history.length - 1].id;
            }

            api.get('/library/images', params)
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

            this.images = [];
        },

        typeWrite(field, value) {
            let i = 0;
            let speed = 10;

            let typeWriter = () => {
                if (i < value.length) {
                    this[field] += value.charAt(i);
                    i++;

                    clearTimeout(this.timer);
                    this.timer = setTimeout(typeWriter, speed);
                }
            };

            this[field] = '';
            typeWriter();
        },

        surprise() {
            let prompt = this.samples[Math.floor(Math.random() * this.samples.length)];
            this.$refs.prompt.focus();
            this.typeWrite('prompt', prompt);
        },

        placeholderSurprise() {
            clearTimeout(this.timer);

            if (this.prompt) {
                return;
            }

            this.timer = setTimeout(() => {
                let randomPrompt = this.samples[Math.floor(Math.random() * this.samples.length)];
                this.typeWrite('placeholder', randomPrompt);
            }, 2000);
        },

        tab(e) {
            if (this.prompt != this.placeholder && this.placeholder) {
                e.preventDefault();
                this.prompt = this.placeholder;
            }
        },

        blur() {
            this.placeholder = null;
            clearTimeout(this.timer);
        },

        submit($el) {
            if (this.isProcessing) {
                return;
            }

            this.isProcessing = true;
            this.preview = null;

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

            for (let image of this.images) {
                body.append('images[]', image);
            }

            api.post(`/ai/images`, body)
                .then(response => response.json())
                .then(image => {
                    this.history.unshift(image);
                    this.select(image);
                    this.prompt = null;
                })
                .catch(error => {
                    let url = new URL(window.location.href);
                    url.pathname = '/app/imagine/';
                    window.history.pushState({}, '', url);
                }).finally(() => {
                    this.isProcessing = false;
                });
        },

        select(image) {
            this.preview = image;
            this.form = false;

            let url = new URL(window.location.href);
            url.pathname = '/app/imagine/' + image.id;
            window.history.pushState({}, '', url);

            this.checkProgress();
        },

        remove(image) {
            this.isDeleting = true;

            api.delete(`/library/images/${image.id}`)
                .then(() => {
                    this.preview = null;
                    this.form = true;

                    window.modal.close();

                    toast.show("Image has been deleted successfully.", 'ti ti-trash');
                    this.isDeleting = false;

                    let url = new URL(window.location.href);
                    url.pathname = '/app/imagine/';
                    window.history.pushState({}, '', url);

                    this.history.splice(this.history.indexOf(image), 1);
                })
                .catch(error => this.isDeleting = false);
        },

        copyImgToClipboard(image) {
            fetch(image.output_file.url)
                .then(res => res.blob())
                .then(blob => {
                    let item = new ClipboardItem({
                        [blob.type]: blob,
                    });

                    return navigator.clipboard.write([item])
                })
                .then(() => {
                    toast.success('Image copied to clipboard!');
                });
        },

        checkProgress() {
            if (this.preview.state >= 3) {
                return;
            }

            api.get(`/library/images/${this.preview.id}`)
                .then(response => response.json())
                .then(image => {
                    this.preview = image;
                    setTimeout(() => this.checkProgress(), 5000);
                });
        },

        save(resource) {
            api.post(`/library/images/${resource.id}`, {
                title: resource.title,
            }).then((resp) => {
                // Update the item in the history list
                this.updateHistory(resp.data);
            });
        },

        addImage($event) {
            const files = Array.from($event.target.files);
            const limit = this.model.config.images.limit || 1;

            this.images = [
                ...this.images,
                ...files.slice(0, limit - this.images.length)
            ];

            $event.target.value = null;
            window.modal.open('options');
        },

        removeImage(image) {
            this.images = this.images.filter(f => f !== image);
        },

        updateHistory(image) {
            let index = this.history.findIndex(item => item.id === image.id);

            if (index >= 0) {
                this.history[index] = image;
            }
        },

        actionNew() {
            this.prompt = null;
            this.negativePrompt = null;
            this.params = {};
            this.images = [];

            this.form = true;

            let url = new URL(window.location.href);
            url.pathname = '/app/imagine/';
            window.history.pushState({}, '', url);

            this.$nextTick(() => {
                this.$refs.prompt.focus();
            });
        },

        actionEdit() {
            this.prompt = this.preview.params?.prompt || null;
            this.negativePrompt = this.preview.params?.negative_prompt || null;

            let params = { ...this.preview.params };
            delete params.prompt;
            delete params.negative_prompt;

            this.selectModel(this.preview.model);
            this.form = true;

            this.$nextTick(() => {
                this.params = params;
            });

            let url = new URL(window.location.href);
            url.pathname = '/app/imagine/';
            window.history.pushState({}, '', url);

            this.$nextTick(() => {
                this.$refs.prompt.focus();
            });
        }
    }));
}