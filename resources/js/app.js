window.Pusher = require('pusher-js');
import Echo from "laravel-echo";

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true
});

var ctxClass = window.audioContext || window.AudioContext
        || window.AudioContext || window.webkitAudioContext;
var myAudioContext = new ctxClass();
unlockAudioContext(myAudioContext);

$(document).ready(function() {
    if(Laravel.userId) {
        window.Echo.private(`order.${Laravel.userId}`)
            .notification((notification) => {
                showNotifications(notification, '#notifications');
                beep();
            });
    }
});

function showNotifications(notification, target) {
    let pending = parseInt($(".bell").find(".pending").html());
    if (Number.isNaN(pending)) {
        $(".bell")
            .find(".pending")
            .html(1);
    } else {
        $(".bell")
            .find(".pending")
            .html(pending + 1);
    }

    if(notification.data) {
        $(target).prepend(makeNotification(notification));
    }
}

// Make a single notification string
function makeNotification(notification) {
    return `<li class="notifications-item unread">
                <a class="text-decoration-none" href="${notification.data.link}?read=${notification.id}">
                    <i class="fa fa-dot-circle-o text-danger" aria-hidden="true"></i>
                    <div class="text">
                        <h6 class="m-0 p-0">${notification.data.title}</h6>
                        <p class="m-0 p-0">${notification.data.message}</p>
                    </div>
                </a>
            </li>`;
}

function beep(duration, frequency, volume){
    return new Promise((resolve, reject) => {
        // Set default duration if not provided
        duration = duration || 200;
        frequency = frequency || 2000;
        volume = volume || 10;

        try{
            let oscillatorNode = myAudioContext.createOscillator();
            let gainNode = myAudioContext.createGain();
            oscillatorNode.connect(gainNode);

            // Set the oscillator frequency in hertz
            oscillatorNode.frequency.value = frequency;

            // Set the type of oscillator
            oscillatorNode.type= "square";
            gainNode.connect(myAudioContext.destination);

            // Set the gain to the volume
            gainNode.gain.value = volume * 0.01;

            // Start audio with the desired duration
            oscillatorNode.start(myAudioContext.currentTime);
            oscillatorNode.stop(myAudioContext.currentTime + duration * 0.001);

            // Resolve the promise when the sound is finished
            oscillatorNode.onended = () => {
                resolve();
            };
        }catch(error){
            reject(error);
        }
    });
}

function unlockAudioContext(audioCtx) {
    if (audioCtx.state !== 'suspended') return;
    const b = document.body;
    const events = ['touchstart','touchend', 'mousedown','keydown'];
    events.forEach(e => b.addEventListener(e, unlock, false));
    function unlock() { audioCtx.resume().then(clean); }
    function clean() { events.forEach(e => b.removeEventListener(e, unlock)); }
}
