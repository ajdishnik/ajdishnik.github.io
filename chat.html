<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Чат-заметки</title>

<style>
* { box-sizing: border-box; }

body {
    font-family: Arial, sans-serif;
    background: #f2f2f2;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding-top: 30px;
    margin: 0;
}

.chat-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.chat-title {
    font-size: 28px;
    font-weight: bold;
    color: #4CAF50;
    margin-bottom: 15px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
}

.chat-container {
    width: 400px;
    height: 600px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.chat-box {
    flex: 1;
    padding: 10px;
    overflow-y: auto;
    border-bottom: 1px solid #ddd;
}

.message {
    background: #e3e3ff;
    padding: 8px 12px;
    border-radius: 15px;
    margin-bottom: 8px;
    max-width: 75%;
    position: relative;

    white-space: pre-wrap;
    overflow-wrap: break-word;
    word-break: break-word;
}

.message-time {
    font-size: 11px;
    color: #666;
    text-align: right;
    margin-top: 4px;
}

.edit-btn {
    font-size: 12px;
    background: none;
    border: none;
    cursor: pointer;
    color: #555;
    margin-top: 4px;
}

.edit-btn:hover { color: black; }

.input-area-wrapper {
    display: flex;
    flex-direction: column; /* превью сверху, input + кнопка снизу */
    padding: 10px;
}

.input-area {
    display: flex;
    flex-direction: row; /* input слева, кнопка справа */
    gap: 10px; /* отступ между полем и кнопкой */
}

input {
    flex: 1;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

button {
    margin-left: 5px;
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    background: #4CAF50;
    color: white;
    cursor: pointer;
}

button:hover { background: #45a049; }

.reply-text {
    font-size: 12px;
    color: #555;
    border-left: 2px solid #aaa;
    padding-left: 6px;
    margin-bottom: 4px;
    cursor: pointer;
}

.reply-preview {
    display: none;
    background:#f0f0f0;
    padding:5px 8px;
    border-left:3px solid #4CAF50;
    margin-bottom:5px;
    border-radius:5px;
    font-size:13px;
    position:relative;
}
.reply-preview #cancelReply {
    position:absolute;
    right:5px;
    cursor:pointer;
}
</style>
</head>

<body>

<div class="chat-wrapper">
    <div class="chat-title">Чат-заметки</div>

    <div class="chat-container">
        <div class="chat-box" id="chatBox"></div>
	<div class="input-area-wrapper">
		<!-- блок превью ответа сверху -->
		<div class="reply-preview" id="replyPreview">
			<span id="replyText"></span>
			<span id="cancelReply">❌</span>
		</div>

		<!-- поле ввода и кнопка отправки в ряд -->
		<div class="input-area">
			<input type="text" id="messageInput" placeholder="Введите сообщение...">
			<button onclick="sendMessage()">Отправить</button>
			<button onclick="copyChat()">📋</button>
		</div>

<script>
function getCurrentTime() {
    const now = new Date();
    return now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

function sendMessage() {
    const input = document.getElementById("messageInput");
    const chatBox = document.getElementById("chatBox");
    const text = input.value.trim();
    if (!text) return;

    // прячем блок превью ответа
    const replyPreview = document.getElementById("replyPreview");
    replyPreview.style.display = "none";
    let replyText = "";
    if (input.dataset.replyTo) {
        replyText = input.dataset.replyTo;
        delete input.dataset.replyTo;
    }

    const message = document.createElement("div");
    message.className = "message";

    const textDiv = document.createElement("div");
    textDiv.className = "message-text";

    // если есть ответ на сообщение
	if (replyText) {
		const replyDiv = document.createElement("div");
		replyDiv.className = "reply-text";

		// обрезаем текст до 50 символов
		const shortReply = replyText.length > 50 ? replyText.slice(0, 50) + "..." : replyText;
		replyDiv.textContent = "Ответ на: " + shortReply;

		// скролл к сообщению при клике
		replyDiv.onclick = function () {
			const messages = Array.from(document.querySelectorAll(".message"));
			for (const msg of messages) {
				const mainTextDiv = msg.querySelector(".message-text div:last-child");
				if (mainTextDiv && mainTextDiv.textContent === replyText) {
					msg.scrollIntoView({ behavior: "smooth", block: "center" });
					msg.style.background = "#d0f0d0";
					setTimeout(() => msg.style.background = "#e3e3ff", 1000);
					break;
				}
			}
		};

		textDiv.appendChild(replyDiv);
	}


    const mainText = document.createElement("div");
    mainText.textContent = text;
    textDiv.appendChild(mainText);

    const timeDiv = document.createElement("div");
    timeDiv.className = "message-time";
    timeDiv.textContent = getCurrentTime();

    // Кнопка редактирования
    const editBtn = document.createElement("button");
    editBtn.className = "edit-btn";
    editBtn.textContent = "✏️ Редактировать";
    editBtn.onclick = function () { editMessage(message); };

    // Кнопка ответа
    const replyBtn = document.createElement("button");
    replyBtn.className = "edit-btn";
    replyBtn.textContent = "💬 Ответить";
    replyBtn.onclick = function () {
        const inputField = document.getElementById("messageInput");
        const preview = document.getElementById("replyPreview");
        const previewText = document.getElementById("replyText");

        inputField.focus();
        inputField.dataset.replyTo = mainText.textContent;

		previewText.textContent = mainText.textContent.length > 50 
			? mainText.textContent.slice(0, 20) + "..." 
			: mainText.textContent;
        preview.style.display = "block";
    };

    message.appendChild(textDiv);
    message.appendChild(timeDiv);
    message.appendChild(editBtn);
    message.appendChild(replyBtn);

    chatBox.appendChild(message);

    input.value = "";
    chatBox.scrollTop = chatBox.scrollHeight;
}

function editMessage(messageContainer) {
    const textDiv = messageContainer.querySelector(".message-text");
    const mainText = textDiv.querySelector("div:last-child").textContent;

    const textarea = document.createElement("textarea");
    textarea.value = mainText;
    textarea.style.width = "100%";

    const saveBtn = document.createElement("button");
    saveBtn.textContent = "Сохранить";
    saveBtn.style.marginTop = "5px";

    const replyDiv = textDiv.querySelector(".reply-text");
    if (replyDiv) replyDiv.remove();
    textDiv.replaceWith(textarea);
    messageContainer.appendChild(saveBtn);

    saveBtn.onclick = function () {
        const newTextDiv = document.createElement("div");
        newTextDiv.className = "message-text";

        if (replyDiv) newTextDiv.appendChild(replyDiv);

        const mainTextDiv = document.createElement("div");
        mainTextDiv.textContent = textarea.value;
        newTextDiv.appendChild(mainTextDiv);

        textarea.replaceWith(newTextDiv);
        saveBtn.remove();
    };
}

document.getElementById("cancelReply").onclick = function () {
    const inputField = document.getElementById("messageInput");
    const replyPreview = document.getElementById("replyPreview");
    delete inputField.dataset.replyTo;
    replyPreview.style.display = "none";
};

document.getElementById("messageInput").addEventListener("keydown", function(e) {
    if (e.key === "Enter") sendMessage();
});



function copyChat() {
    const chatBox = document.getElementById("chatBox");
    let chatText = "";

    chatBox.querySelectorAll(".message").forEach(msg => {
        const mainText = msg.querySelector(".message-text div:last-child").textContent;
        const replyDiv = msg.querySelector(".reply-text");
        const replyText = replyDiv ? "Ответ на: " + replyDiv.textContent.replace("Ответ на: ","") + "\n" : "";
        const time = msg.querySelector(".message-time").textContent;
        chatText += replyText + mainText + " (" + time + ")\n";
    });

    navigator.clipboard.writeText(chatText)
        .then(() => alert("Чат скопирован в буфер обмена!"))
        .catch(err => alert("Ошибка при копировании: " + err));
}


</script>

</body>
</html>
