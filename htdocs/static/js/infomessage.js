class InfoMessage {
    TYPE_INFO = 0;
    TYPE_WARNING = 1;
    TYPE_ERROR = 2;
    TYPE_SUCCESS = 3;

    constructor(message, type) {
        let messageElement = document.createElement("div");
        messageElement.classList.add("infomessage");
        switch(type) {
            case this.TYPE_INFO: {
                messageElement.setAttribute("message-type", "info");
                break;
            }
            case this.TYPE_WARNING: {
                messageElement.setAttribute("message-type", "warning");
                break;
            }
            case this.TYPE_ERROR: {
                messageElement.setAttribute("message-type", "error");
                break;
            }
            case this.TYPE_SUCCESS: {
                messageElement.setAttribute("message-type", "success");
                break;
            }
        }
        messageElement.innerText = message;

        let messageTypes = [
            {
                messages: document.querySelectorAll(".infomessages-list .infomessage[message-type=\"info\"]").length,
                firstMessage: document.querySelector(".infomessages-list .infomessage[message-type=\"info\"]")
            },
            {
                messages: document.querySelectorAll(".infomessages-list .infomessage[message-type=\"warning\"]").length,
                firstMessage: document.querySelector(".infomessages-list .infomessage[message-type=\"warning\"]")
            },
            {
                messages: document.querySelectorAll(".infomessages-list .infomessage[message-type=\"error\"]").length,
                firstMessage: document.querySelector(".infomessages-list .infomessage[message-type=\"error\"]")
            },
            {
                messages: document.querySelectorAll(".infomessages-list .infomessage[message-type=\"success\"]").length,
                firstMessage: document.querySelector(".infomessages-list .infomessage[message-type=\"success\"]")
            }
        ];

        let inserted = false;
        let messageList = document.querySelector(".infomessages-list");
        for(let i = type; i >= 0; i--) {
            if(messageTypes[i].messages > 0) {
                messageList.insertBefore(messageElement, messageTypes[i].firstMessage)
                inserted = true;
                break;
            }
        }

        if(!(inserted)) {
            messageList.appendChild(messageElement);
        }
    }

    static clearMessages() {
        let messagesList = document.querySelector(".infomessages-list");
        messagesList.innerHTML = "";
    }
}