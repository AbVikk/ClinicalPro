// resources/js/bootstrap.js

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import SocketIoClient from 'socket.io-client';
window.io = SocketIoClient;

// We use the hardcoded 'localhost' host to fix the 127.0.0.1 error
const echoHost = 'http://localhost:3000'; 
console.log('[Echo] Using host:', echoHost);

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: echoHost,
    transports: ['websocket', 'polling'],
    path: '/socket.io',
    secure: window.location.protocol === 'https:',
    
    // This authorizer is now 100% correct
    authorizer: (channel, options) => {
        return {
            authorize: (socket, callback) => {
                
                const channelName = channel.name; 
                console.log('[Echo] authorizer -> trying to auth channel:', channelName);

                axios.post('/broadcasting/auth', {
                    socket_id: socket.id,
                    channel_name: channelName 
                })
                .then(response => {
                    socket.emit('subscribe', channelName); 
                    callback(false, response.data);
                })
                .catch(error => {
                    console.error('Broadcasting Auth Failed:', error);
                    callback(true, error);
                });
            }
        };
    },
});

// --- Extra debug: socket.io lower-level events ---
try {
    const socketObj = window.Echo && window.Echo.connector && (window.Echo.connector.socket || window.Echo.connector.io);
    if (socketObj) {
        console.log('[Echo] socket object found. connected=', !!socketObj.connected);
        const s = socketObj;
        s.on && s.on('connect', () => console.log('[Socket.IO] connect'));
        s.on && s.on('connect_error', (err) => console.error('[Socket.IO] connect_error', err));
        s.on && s.on('error', (err) => console.error('[Socket.IO] error', err));
        s.on && s.on('reconnect_attempt', (n) => console.log('[Socket.IO] reconnect_attempt', n));
    } else {
        console.log('[Echo] socket object NOT found yet.');
    }
} catch (err) {
    console.error('Error attaching socket debug listeners', err);
}

// === THIS SCRIPT BLOCK IS NOW CORRECT ===

const doctorIdElement = document.querySelector('meta[name="doctor-id"]');

if (doctorIdElement && window.Echo) {
    const doctorId = doctorIdElement.getAttribute('content');

    if (doctorId) {
        
        // 4. We now subscribe to the SIMPLE, SHORT channel name
        const simpleChannelName = `doctor-alerts.${doctorId}`;

        console.log(`[Echo] Subscribing to channel: ${simpleChannelName}`);

        // --- lightweight toast helper (avoids blocking alert) ---
        function showInlineToast(message) {
            // (Your toast function code is perfect, leaving it as-is)
            try {
                let container = document.getElementById('live-toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'live-toast-container';
                    container.style.position = 'fixed';
                    container.style.top = '1rem';
                    container.style.right = '1rem';
                    container.style.zIndex = 1060;
                    container.style.display = 'flex';
                    container.style.flexDirection = 'column';
                    container.style.gap = '0.5rem';
                    document.body.appendChild(container);
                }
                const toast = document.createElement('div');
                toast.className = 'live-toast shadow-sm';
                toast.style.background = '#ffffff';
                toast.style.border = '1px solid rgba(0,0,0,0.08)';
                toast.style.padding = '0.75rem 1rem';
                toast.style.borderRadius = '0.5rem';
                toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.08)';
                toast.style.minWidth = '240px';
                toast.style.color = '#111827';
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 200ms ease, transform 200ms ease';
                toast.style.transform = 'translateY(-6px)';
                toast.innerText = message;
                container.appendChild(toast);
                requestAnimationFrame(() => {
                    toast.style.opacity = '1';
                    toast.style.transform = 'translateY(0)';
                });
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-6px)';
                    setTimeout(() => {
                        toast.remove();
                    }, 220);
                }, 6000);
            } catch (err) {
                console.error('showInlineToast error', err);
            }
        }

        // 5. We use .private() which automatically adds "private-"
        // This is the correct way Echo is designed to work.
        // It will "knock on the door" for "private-doctor-alerts.3"
        window.Echo.private(simpleChannelName) // <-- THE FIX IS HERE
            .subscribed(() => {
                console.log('[Echo] SUCCESSFULLY SUBSCRIBED to private channel!');
            })
            .listen('DoctorAlertEvent', (e) => {
                console.log('NEW ALERT RECEIVED:', e.message);
                showInlineToast(e.message || 'You have a new notification');
                const badge = document.getElementById('live-request-count-badge');
                if (badge) {
                    let count = parseInt(badge.textContent) || 0;
                    badge.textContent = count + 1;
                }
            })
            .error((error) => {
                console.error(`[Echo] Subscription Error to ${simpleChannelName}:`, error);
            });
    }
} else {
    console.log('[Echo] Not a doctor page, or Echo not found. Alerter not started.');
}
// === END OF NEW CODE BLOCK ===