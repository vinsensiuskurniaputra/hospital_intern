@extends('layouts.auth')

@section('title', 'Notifications')

@section('content')
    <div class="p-6 space-y-6" x-data="notifications">
        <!-- Header -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Notifications</h1>
                    <p class="mt-1 text-sm text-gray-500">Stay updated with your latest activities</p>
                </div>
                <div class="flex gap-3">
                    <button @click="markAllAsRead"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Mark all as read
                    </button>
                    <button @click="clearAll"
                        class="px-4 py-2 text-sm text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                        Clear all
                    </button>
                </div>
            </div>
        </div>

        <!-- Notifications Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4" x-init="loadNotifications">
            <template x-for="notification in notifications" :key="notification.id">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
                    x-show="notification.visible" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    :class="{ 'bg-[#F5F7F0]': !notification.read }">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <!-- Icon -->
                            <div :class="`p-3 rounded-lg ${notification.iconBg}`">
                                <i :class="`bi ${notification.icon} text-lg ${notification.iconColor}`"></i>
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <h3 class="font-medium text-gray-900" x-text="notification.title"></h3>
                                    <span class="text-xs text-gray-500" x-text="notification.time"></span>
                                </div>
                                <p class="mt-1 text-sm text-gray-600" x-text="notification.message"></p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 flex justify-end gap-3">
                            <button @click="toggleRead(notification)" class="text-sm text-gray-600 hover:text-[#637F26]"
                                x-text="notification.read ? 'Mark as unread' : 'Mark as read'">
                            </button>
                            <button @click="removeNotification(notification.id)"
                                class="text-sm text-red-600 hover:text-red-700">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('notifications', () => ({
                notifications: [],

                loadNotifications() {
                    // Sample notifications - replace with your actual data
                    this.notifications = [{
                            id: 1,
                            title: 'Schedule Updated',
                            message: 'Your internship schedule for next week has been updated.',
                            time: '5 minutes ago',
                            icon: 'bi-calendar-check',
                            iconBg: 'bg-green-100',
                            iconColor: 'text-green-600',
                            read: false,
                            visible: true
                        },
                        {
                            id: 2,
                            title: 'New Assignment',
                            message: 'You have a new assignment in General Surgery.',
                            time: '1 hour ago',
                            icon: 'bi-file-text',
                            iconBg: 'bg-blue-100',
                            iconColor: 'text-blue-600',
                            read: false,
                            visible: true
                        },
                        // Add more notifications as needed
                    ]
                },

                toggleRead(notification) {
                    notification.read = !notification.read;
                },

                removeNotification(id) {
                    const index = this.notifications.findIndex(n => n.id === id);
                    if (index > -1) {
                        this.notifications[index].visible = false;
                        setTimeout(() => {
                            this.notifications = this.notifications.filter(n => n.id !== id);
                        }, 300);
                    }
                },

                markAllAsRead() {
                    this.notifications.forEach(n => n.read = true);
                },

                clearAll() {
                    this.notifications.forEach(n => n.visible = false);
                    setTimeout(() => {
                        this.notifications = [];
                    }, 300);
                }
            }))
        })
    </script>
@endsection
