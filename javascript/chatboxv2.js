const chatbox_btn = document.querySelector('.questionmark');
const mobile_chatbox = document.querySelector('.chatbox')
    chatbox_btn.addEventListener('click', function() {
        chatbox_btn.classList.toggle('is-active');
        mobile_chatbox.classList.toggle('is-active');
    });