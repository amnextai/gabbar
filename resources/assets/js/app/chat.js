'use strict';

import Alpine from 'alpinejs';
import api from './api';
import { EventSourceParserStream } from 'eventsource-parser/stream';

export function chat() {
    Alpine.data('chat', (
        model,
        adapters = [],
        assistant = null,
        conversation = null
    ) => ({
        adapters: [],
        adapter: null,

        conversation: null,
        assistant: assistant,

        history: [],
        historyLoaded: false,

        assistants: null,

        tree: [],
        map: null,

        file: null,
        prompt: null,
        isProcessing: false,
        parent: null,
        quote: null,
        autoScroll: false,
        contentElement: null,
        isDeleting: false,
        query: '',

        options: {
            assistant: null,
            model: null,
            message: null,
        },

        init() {
            this.contentElement = document.getElementById('content');

            adapters.forEach(adapter => {
                if (adapter.is_available) {
                    adapter.models.forEach(model => {
                        if (model.is_available && model.is_enabled) {
                            this.adapters.push(model);
                        }
                    });
                }
            });

            let m = this.assistant?.model || model;
            this.adapter = this.adapters.find(adapter => adapter.model == m);

            if (!this.adapter && this.adapters.length > 0) {
                this.adapter = this.adapters[0];
            }

            if (conversation) {
                this.select(conversation);
                setTimeout(() => this.scrollToBottom(), 500);
            }

            this.fetchHistory();
            this.getAssistants();

            let autoScrollTimer = null;
            ['wheel', 'touchstart', 'mousedown', 'keydown'].forEach(event => {
                window.addEventListener(event, () => {
                    this.autoScroll = false;
                    // Reset after some time to avoid false positives
                    clearTimeout(autoScrollTimer);
                    if (this.contentElement.scrollHeight - this.contentElement.scrollTop <= this.contentElement.clientHeight) {
                        autoScrollTimer = setTimeout(() => this.autoScroll = true, 1000);
                    }
                }, { passive: true });
            });

            window.addEventListener('mouseup', (e) => {
                this.$refs.quote.classList.add('hidden');
                this.$refs.quote.classList.remove('flex');
            });

            // Parse query parameters and find the parameter 'q'
            let url = new URL(window.location.href);
            let query = url.searchParams.get('q');

            if (query) {
                this.prompt = query;
                this.submit();
            }
        },

        select(conversation) {
            this.conversation = conversation;

            this.map = new Map();
            this.conversation.messages.forEach(message => {
                this.map.set(message.id, message);
            });

            this.generateTree();

            let url = new URL(window.location.href);
            url.pathname = '/app/chat/' + conversation.id;
            url.search = '';
            window.history.pushState({}, '', url);

            // Find the first message in the last tree node
            if (this.tree.length === 0) {
                return;
            }

            let lastNode = this.tree[this.tree.length - 1];
            let lastMessage = lastNode.children[lastNode.index];

            // Set the adapter for the conversation based on the last message
            let adapter = this.adapters.find(adapter => adapter.model === lastMessage.model);
            if (adapter) {
                this.adapter = adapter;
            }

            // Set the assistant for the conversation based on the last message
            this.selectAssistant(lastMessage.assistant);
        },

        generateTree(msgId = null) {
            if (msgId && !this.map.has(msgId)) {
                return;
            }

            this.tree.splice(0);
            let parentId = null;

            while (true) {
                let node = {
                    index: 0,
                    children: []
                }

                this.map.forEach(message => {
                    if (parentId === message.parent_id) {
                        node.children.push(message);
                    }
                });

                let ids = node.children.map(msg => msg.id);

                if (node.children.length > 0) {
                    if (msgId) {
                        let msg = this.map.get(msgId);

                        // Update indices to ensure the selected message is visible
                        while (msg) {
                            if (ids.indexOf(msg.id) >= 0) {
                                node.index = ids.indexOf(msg.id);
                                break;
                            }

                            if (msg.parent_id) {
                                msg = this.map.get(msg.parent_id);

                                continue;
                            }

                            break;
                        }
                    }

                    this.tree.push(node);
                    parentId = node.children[node.index].id;
                    continue;
                }

                break;
            }
        },

        findMessage(id) {
            if (this.map.has(id)) {
                return this.map.get(id);
            }

            if (!this.history) {
                return null;
            }

            for (const conversation of this.history) {
                let message = conversation.messages.find(m => m.id === id);

                if (message) {
                    return message;
                }
            }

            return null;
        },

        addMessage(msg) {
            let conversation = this.findConversation(msg.conversation.id || msg.conversation);

            if (!conversation) {
                return;
            }

            let regen = this.isMessageVisible(msg.id);

            if (msg.conversation.id) {
                for (const [key, value] of Object.entries(msg.conversation)) {
                    if (key === 'messages') {
                        continue;
                    }
                }

                conversation.title = msg.conversation.title;
                conversation.cost = msg.conversation.cost;
            }

            if (!conversation.messages.find(m => m.id === msg.id)) {
                conversation.messages.push(msg);
                if (conversation.id == this.conversation.id) {
                    regen = true;
                }


            } else {
                let index = conversation.messages.findIndex(m => m.id === msg.id);
                msg.reasoning = conversation.messages[index].reasoning;
                conversation.messages[index] = msg;
            }

            if (!this.conversation || conversation.id != this.conversation.id) {
                return;
            }

            this.map.set(msg.id, msg);

            if (regen) {
                this.generateTree(msg.id);
            }
        },

        findConversation(id) {
            if (this.conversation && this.conversation.id === id) {
                return this.conversation;
            }

            if (this.history) {
                return this.history.find(conversation => conversation.id === id);
            }

            return null;
        },

        markAsSilent() {
            // Mark processing messages as silent in both current conversation and history
            if (this.history) {
                this.history.forEach(conversation => {
                    conversation.messages.forEach(message => {
                        if (message.isProcessing) message.silent = true;
                    });
                });
            }

            if (this.conversation?.messages) {
                this.conversation.messages.forEach(message => {
                    if (message.isProcessing) message.silent = true;
                });
            }
        },

        isMessageVisible(messageId) {
            return this.tree.some(node => {
                // Check if the message at current node's index matches the messageId
                return node.children[node.index]?.id === messageId;
            });
        },

        fetchHistory() {
            let params = {
                limit: 25
            };

            if (this.history && this.history.length > 0) {
                params.starting_after = this.history[this.history.length - 1].id;
            }

            api.get('/library/conversations', params)
                .then(response => response.json())
                .then(list => {
                    let data = list.data;
                    this.history.push(...data);

                    if (data.length < params.limit) {
                        this.historyLoaded = true;
                    }
                });
        },

        getAssistants(cursor = null) {
            let params = {
                limit: 250
            };

            if (cursor) {
                params.starting_after = cursor;
            }

            api.get('/assistants', params)
                .then(response => response.json())
                .then(list => {
                    if (!this.assistants) {
                        this.assistants = [];
                    }

                    this.assistants.push(...list.data);

                    if (list.data.length > 0 && list.data.length == params.limit) {
                        this.getAssistants(this.assistants[this.assistants.length - 1].id);
                    }
                });
        },

        stopProcessing() {
            this.isProcessing = false;
        },

        async submit() {
            if (this.isProcessing) {
                return;
            }

            this.isProcessing = true;

            if (!this.conversation) {
                try {
                    await this.createConversation();
                } catch (error) {
                    this.stopProcessing();
                    return;
                }
            }

            let data = new FormData();
            data.append('content', this.prompt);
            data.append('model', this.adapter.model);

            if (this.assistant?.id) {
                data.append('assistant_id', this.assistant.id);
            }

            if (this.quote) {
                data.append('quote', this.quote);
            }

            let msgs = document.getElementsByClassName('message');
            if (msgs.length > 0) {
                let pid = msgs[msgs.length - 1].dataset.id;

                if (pid) {
                    data.append('parent_id', pid);
                }
            }

            if (this.file) {
                data.append('file', this.file);
            }

            this.ask(data);
        },

        async ask(data) {
            try {
                let response = await api.post('/ai/conversations/' + this.conversation.id + '/messages', data);

                // Get the readable stream from the response body
                const stream = response.body
                    .pipeThrough(new TextDecoderStream())
                    .pipeThrough(new EventSourceParserStream());

                // Get the reader from the stream
                const reader = stream.getReader();

                this.scrollToBottom();
                this.autoScroll = true;

                this.file = null;

                let msg;
                while (true) {
                    if (this.isProcessing) {
                        this.quote = null;
                        this.prompt = null;
                        this.isProcessing = false;
                        this.markAsSilent();
                    }

                    const { value, done } = await reader.read();
                    if (done) {
                        this.stopProcessing();

                        if (msg) {
                            msg.call = null;
                            msg.isProcessing = false;

                            if (this.isMessageVisible(msg.id)) {
                                this.generateTree(msg.id);
                            }
                        }

                        break;
                    }

                    if (value.event == 'token' || value.event == 'reasoning-token') {
                        let chunk = JSON.parse(value.data);
                        msg = this.findMessage(chunk.attributes.message_id);

                        if (msg) {
                            const now = Date.now();
                            if (value.event == 'reasoning-token') {
                                if (msg.silent) {
                                    msg.pendingReasoning = (msg.pendingReasoning || '') + chunk.data;
                                } else {
                                    msg.reasoning += (msg.pendingReasoning || '') + chunk.data;
                                }

                                msg.call = {
                                    name: 'reasoning'
                                };
                            } else {
                                if (msg.silent) {
                                    msg.pendingContent = (msg.pendingContent || '') + chunk.data;
                                } else {
                                    msg.content += (msg.pendingContent || '') + chunk.data;
                                }

                                msg.call = null;
                            }

                            msg.isProcessing = true;
                            if (this.autoScroll) {
                                this.scrollToBottom();
                            }
                        }

                        continue;
                    }

                    if (msg && value.event == 'call') {
                        let chunk = JSON.parse(value.data);
                        msg = this.findMessage(chunk.attributes.message_id);

                        if (msg) {
                            msg.call = chunk.data;

                            if (this.isMessageVisible(msg.id)) {
                                this.generateTree(msg.id);
                            }
                        }


                        continue;
                    }

                    if (value.event == 'message') {
                        msg = JSON.parse(value.data);

                        if (!msg.hasOwnProperty('reasoning')) {
                            msg.reasoning = '';
                        }

                        this.addMessage(msg);
                        continue;
                    }

                    if (value.event == 'error') {
                        this.error(value.data);
                        break;
                    }
                }
            } catch (error) {
                this.error(error);
            }
        },

        scrollToBottom() {
            requestAnimationFrame(() => {
                this.contentElement.scrollTop = this.contentElement.scrollHeight;  // Scroll the element to the end
            });
        },

        error(msg) {
            this.stopProcessing();
            toast.error(msg);
            console.error(msg);
            this.generateTree();
        },

        async createConversation() {
            let resp = await api.post('/ai/conversations');
            let conversation = resp.data;

            if (this.history === null) {
                this.history = [];
            }

            this.history.unshift(conversation);
            this.select(conversation);
        },

        save(conversation) {
            api.post(`/library/conversations/${conversation.id}`, {
                title: conversation.title,
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

        enter(e) {
            if (e.key === 'Enter' && !e.shiftKey && !this.isProcessing && this.prompt && this.prompt.trim() !== '') {
                e.preventDefault();
                this.submit();
            }
        },

        paste(e) {
            if (!this.adapter || this.adapter.file_types.length === 0) {
                return; // Allow default paste behavior if no file types are supported
            }

            const items = e.clipboardData.items;
            for (let i = 0; i < items.length; i++) {
                if (items[i].kind === 'file') {
                    const file = items[i].getAsFile();
                    if (file && this.adapter.file_types.includes(file.type)) {
                        this.file = file;
                        e.preventDefault(); // Prevent default paste only if we've found a supported file
                        break;
                    }
                }
            }
            // If no supported file is found, allow default paste behavior
        },

        copy(message) {
            navigator.clipboard.writeText(message.content)
                .then(() => {
                    toast.success('Copied to clipboard!');
                });
        },

        textSelect(e) {
            this.$refs.quote.classList.add('hidden');
            this.$refs.quote.classList.remove('flex');

            let selection = window.getSelection();

            if (selection.rangeCount <= 0) {
                return;
            }

            let range = selection.getRangeAt(0);
            let text = range.toString();

            if (text.trim() == '') {
                return;
            }

            e.stopPropagation();

            let startNode = range.startContainer;
            let startOffset = range.startOffset;

            let rect;
            if (startNode.nodeType === Node.TEXT_NODE) {
                // Create a temporary range to get the exact position of the start
                let tempRange = document.createRange();
                tempRange.setStart(startNode, startOffset);
                tempRange.setEnd(startNode, startOffset + 1); // Add one character to make the range visible
                rect = tempRange.getBoundingClientRect();
            } else if (startNode.nodeType === Node.ELEMENT_NODE) {
                // For element nodes, get the bounding rect directly
                rect = startNode.getBoundingClientRect();
            }

            // Adjust coordinates relative to the container (parent)
            let container = this.$refs.quote.parentElement;
            let containerRect = container.getBoundingClientRect();
            let x = rect.left - containerRect.left + container.scrollLeft;
            let y = rect.top - containerRect.top + container.scrollTop;

            this.$refs.quote.style.top = y + 'px';
            this.$refs.quote.style.left = x + 'px';

            this.$refs.quote.classList.add('flex');
            this.$refs.quote.classList.remove('hidden');

            this.$refs.quote.dataset.value = range.toString();

            return;

        },

        selectQuote() {
            this.quote = this.$refs.quote.dataset.value;
            this.$refs.quote.dataset.value = null;

            this.$refs.quote.classList.add('hidden');
            this.$refs.quote.classList.remove('flex');

            // Clear selection
            window.getSelection().removeAllRanges();
        },

        regenerate(message, model = null, assistant = null) {
            if (!message.parent_id) {
                return;
            }

            let parentMessage = this.conversation.messages.find(
                msg => msg.id === message.parent_id
            );

            if (!parentMessage) {
                return;
            }

            let data = new FormData();
            data.append('parent_id', parentMessage.id);
            data.append('model', model || message.model);

            if (assistant) {
                data.append('assistant_id', assistant.id);
            } else if (message.assistant) {
                data.append('assistant_id', message.assistant.id);
            }

            this.isProcessing = true;
            this.ask(data);
        },

        edit(message, content) {
            let data = new FormData();

            data.append('model', message.model);
            data.append('content', content);

            if (message.parent_id) {
                data.append('parent_id', message.parent_id);
            }

            if (message.assistant?.id) {
                data.append('assistant_id', message.assistant.id);
            }

            if (message.quote) {
                data.append('quote', message.quote);
            }

            this.isProcessing = true;
            this.ask(data);
        },

        remove(conversation) {
            this.isDeleting = true;

            api.delete(`/library/conversations/${conversation.id}`)
                .then(() => {
                    this.conversation = false;
                    window.modal.close();

                    toast.show("Conversation has been deleted successfully.", 'ti ti-trash');
                    this.isDeleting = false;

                    let url = new URL(window.location.href);
                    url.pathname = '/app/chat/';
                    window.history.pushState({}, '', url);

                    this.history.splice(this.history.indexOf(conversation), 1);

                    setTimeout(() => this.conversation = null, 100);
                })
                .catch(error => this.isDeleting = false);
        },

        doesAssistantMatch(assistant, query) {
            if (
                this.$store.workspace.subscription?.plan.config.assistants != null
                && !this.$store.workspace.subscription?.plan.config.assistants.includes(assistant.id)
            ) {
                return false;
            }

            query = query.trim().toLowerCase();

            if (!query) {
                return true;
            }

            if (assistant.name.toLowerCase().includes(query)) {
                return true;
            }

            if (assistant.expertise && assistant.expertise.toLowerCase().includes(query)) {
                return true;
            }

            if (assistant.description && assistant.description.toLowerCase().includes(query)) {
                return true;
            }

            return false;
        },

        selectAssistant(assistant) {
            this.assistant = assistant;
            window.modal.close();

            if (!this.conversation) {
                let url = new URL(window.location.href);
                url.pathname = '/app/chat/' + (assistant?.id || '');
                window.history.pushState({}, '', url);
            }
        },

        toolKey(item) {
            switch (item.object) {
                case 'image':
                    return 'imagine';

                default:
                    return null;
            }
        },

        showOptions(message = null, modal = 'options') {
            this.options.assistant = message ? message.assistant : this.assistant;
            this.options.message = message;
            this.options.model = message ? message.model : this.adapter.model;

            window.modal.open(modal);
        },

        applyOptions() {
            if (this.options.message) {
                this.regenerate(
                    this.options.message,
                    this.options.model,
                    this.options.assistant
                );
            } else {
                this.selectAssistant(this.options.assistant);
                this.adapter = this.adapters.find(
                    adapter => adapter.model === this.options.model
                );
            }

            this.options = {
                assistant: null,
                model: null,
                message: null,
            };

            window.modal.close();
        },
    }));
}