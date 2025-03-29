<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Побуквенное чтение текста с паузой и автопрокруткой</title>
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            background-color: #f0f0f0;
            flex-direction: column;
            font-size: 16px;
        }

        .window {
            width: 90%;
            max-width: 700px;
            height: 200px;
            border: 2px solid #000;
            padding: 10px;
            background-color: #fff;
            font-family: Arial, sans-serif;
            font-size: 1rem;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            overflow-y: scroll;
            position: relative;
        }

        .text {
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1.5;
        }

        .word-count, .written-count {
            position: absolute;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .word-count {
            bottom: 1px;
            right: 20px;
        }

        .written-count {
            bottom: 15px;
            right: 20px;
        }

        .controls-container {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            flex-direction: row;
            gap: 5px;
            z-index: 10;
            justify-content: center;
            padding: 10px;
        }

        .button {
            padding: 8px 12px;
            font-size: 0.8rem;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .pause-btn {
            background-color: #007bff;
            color: white;
        }

        .pause-btn.active {
            background-color: #28a745;
        }

        .speed-btn {
            background-color: #808080;
            color: white;
        }

        #fullscreen-btn {
            background-color: #808080;
            color: white;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .fullscreen {
            background-color: #28a745;
        }

        /* Кнопка очистки localStorage */
        #clearStorage-btn {
            background-color: #dc3545;
            color: white;
        }

        /* Новые кнопки для изменения шрифта */
        #increaseFontSizeBtn,
        #decreaseFontSizeBtn {
            background-color: #ffc107;
            color: white;
        }

        /* Новый стиль для поля ввода */
        #wordInput {
            padding: 5px;
            font-size: 1rem;
            margin-right: 5px;
        }

        @media (max-width: 768px) {
            .window {
                height: 150px;
                font-size: 1rem;
            }

            .button {
                font-size: 0.7rem;
                padding: 6px 10px;
            }

            .word-count, .written-count {
                font-size: 0.8rem;
            }

            #fullscreen-btn {
                font-size: 0.9rem;
                padding: 8px 15px;
            }
        }

        @media (max-width: 480px) {
            body {
                font-size: 14px;
            }

            .window {
                height: 120px;
            }

            .button {
                font-size: 0.6rem;
                padding: 4px 8px;
            }

            #fullscreen-btn {
                font-size: 0.8rem;
                padding: 6px 12px;
            }
        }
    </style>
</head>
<body>
    <div class="window" id="window">
        <div class="text" id="text">
            <!-- Текст будет добавляться сюда -->
        </div>
    </div>

    <div class="written-count" id="writtenCount">Прочитано слов: 0</div>
    <div class="word-count" id="wordCount">Осталось слов: 0</div>

    <div class="controls-container">
        <button class="button" id="fullscreen-btn">🖵</button>
        <button class="button speed-btn" id="decreaseSpeedBtn">↓</button>
        <button class="button pause-btn" id="pauseBtn">▶</button>
        <button class="button speed-btn" id="increaseSpeedBtn">↑</button>
        <!-- Кнопки изменения размера шрифта -->
        <button class="button" id="decreaseFontSizeBtn">-</button>
        <button class="button" id="increaseFontSizeBtn">+</button>
        <!-- Кнопка выбора файла -->
        <button class="button" id="fileSelectBtn">📂</button>
        <button class="button" id="clearStorage-btn">🗑</button>

        <!-- Новая кнопка и поле для ввода номера слова -->
        <input type="number" id="wordInput" placeholder="№" min="1" style="width: 50px; height: 30px; font-size: 14px;"/>
        <button class="button" id="goToWordBtn">></button>
    </div>

    <input type="file" id="fileInput" accept=".txt,.fb2" style="display: none;" />

    <script>
        let textContent = localStorage.getItem("textContent") || ""; // Изначально пустой текст
        const textElement = document.getElementById("text");
        const windowElement = document.getElementById("window");
        const wordCountElement = document.getElementById("wordCount");
        const writtenCountElement = document.getElementById("writtenCount");
        const pauseBtn = document.getElementById("pauseBtn");
        const decreaseSpeedBtn = document.getElementById("decreaseSpeedBtn");
        const increaseSpeedBtn = document.getElementById("increaseSpeedBtn");
        const fullscreenBtn = document.getElementById('fullscreen-btn');
        const clearStorageBtn = document.getElementById('clearStorage-btn');
        const fileInput = document.getElementById("fileInput");
        const fileSelectBtn = document.getElementById("fileSelectBtn");

        // Новые кнопки для изменения шрифта
        const decreaseFontSizeBtn = document.getElementById("decreaseFontSizeBtn");
        const increaseFontSizeBtn = document.getElementById("increaseFontSizeBtn");

        // Поле и кнопка для перехода по слову
        const wordInput = document.getElementById("wordInput");
        const goToWordBtn = document.getElementById("goToWordBtn");

        let index = localStorage.getItem("textIndex") ? parseInt(localStorage.getItem("textIndex")) : 0;
        let textSoFar = localStorage.getItem("textSoFar") || '';
        let writtenWords = countWords(textSoFar);
        let totalWords = countWords(textContent);
        let typingInterval = null;
        let isPaused = false;
        let speed = 100;
        let fontSize = 16; // Изначальный размер шрифта

        function countWords(text) {
            return text.trim().split(/\s+/).filter(Boolean).length;
        }

        function typeText() {
            if (index < textContent.length) {
                textSoFar += textContent.charAt(index);
                textElement.innerHTML = textSoFar;
                index++;

                localStorage.setItem("textIndex", index);
                localStorage.setItem("textSoFar", textSoFar);

                windowElement.scrollTop = windowElement.scrollHeight;

                writtenWords = countWords(textSoFar);

                writtenCountElement.textContent = `Прочитано слов: ${writtenWords}`;
                wordCountElement.textContent = `Осталось слов: ${totalWords - writtenWords}`;
            }
        }

        function startTyping() {
            if (isPaused) {
                isPaused = false;
                typingInterval = setInterval(typeText, speed);
            } else {
                typingInterval = setInterval(typeText, speed);
            }
        }

        function togglePause() {
            if (isPaused) {
                isPaused = false;
                pauseBtn.classList.remove('active');
                typingInterval = setInterval(typeText, speed);
            } else {
                isPaused = true;
                pauseBtn.classList.add('active');
                clearInterval(typingInterval);
            }
        }

        function increaseSpeed() {
            speed = Math.max(10, speed - 10);
            if (!isPaused) {
                clearInterval(typingInterval);
                typingInterval = setInterval(typeText, speed);
            }
        }

        function decreaseSpeed() {
            speed = Math.min(1000, speed + 10);
            if (!isPaused) {
                clearInterval(typingInterval);
                typingInterval = setInterval(typeText, speed);
            }
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                }
                fullscreenBtn.classList.add('fullscreen');
                fullscreenBtn.textContent = '❌';
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
                fullscreenBtn.classList.remove('fullscreen');
                fullscreenBtn.textContent = '🖵';
            }
        }

        function clearLocalStorage() {
            localStorage.removeItem("textIndex");
            localStorage.removeItem("textSoFar");
            localStorage.removeItem("textContent");
            index = 0;
            textSoFar = '';
            writtenWords = 0;
            wordCountElement.textContent = `Осталось слов: ${totalWords}`;
            writtenCountElement.textContent = `Прочитано слов: ${writtenWords}`;
            textElement.innerHTML = '';
        }

        function loadTextFromFile(file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const arrayBuffer = event.target.result;
                const fileType = file.name.split('.').pop().toLowerCase();

                if (fileType === 'txt') {
                    // Декодируем содержимое файла с кодировкой windows-1251
                    const decoder = new TextDecoder('windows-1251');
                    textContent = decoder.decode(arrayBuffer);
                    processText();
                } else if (fileType === 'fb2') {
                    // Парсинг FB2 с использованием XML
                    const decoder = new TextDecoder('windows-1251');
                    const decodedContent = decoder.decode(arrayBuffer);

                    const parser = new DOMParser();
                    const xmlDoc = parser.parseFromString(decodedContent, "application/xml");

                    // Получаем текст из элементов <body> внутри FB2
                    const body = xmlDoc.getElementsByTagName('body')[0];
                    const paragraphs = body.getElementsByTagName('p');

                    // Обрабатываем каждый абзац, чтобы создать теги <p> для правильного отображения
                    textContent = Array.from(paragraphs).map(p => `<p>${p.textContent}</p>`).join('');
                    processText();
                }
            };
            reader.readAsArrayBuffer(file);  // Читаем файл как ArrayBuffer
        }

        function processText() {
            localStorage.setItem("textContent", textContent);
            totalWords = countWords(textContent);
            localStorage.removeItem("textIndex");
            localStorage.removeItem("textSoFar");
            index = 0;
            textSoFar = '';
            writtenWords = 0;
            textElement.innerHTML = textSoFar;
            wordCountElement.textContent = `Осталось слов: ${totalWords}`;
            writtenCountElement.textContent = `Прочитано слов: 0`;
            startTyping(); // Начинаем печатать текст
        }

        // При клике на кнопку "Выбрать файл", открываем диалоговое окно
        fileSelectBtn.addEventListener("click", function() {
            fileInput.click();
        });

        // Слушаем выбор файла
        fileInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                loadTextFromFile(file);
            }
        });

        // Изменение размера шрифта
        function increaseFontSize() {
            fontSize += 2;
            textElement.style.fontSize = fontSize + 'px';
        }

        function decreaseFontSize() {
            fontSize = Math.max(10, fontSize - 2);
            textElement.style.fontSize = fontSize + 'px';
        }

        // Переход к указанному слову
        function goToWord() {
            const wordNumber = parseInt(wordInput.value);
            if (wordNumber >= 1 && wordNumber <= totalWords) {
                let wordIndex = 0;
                let wordCount = 0;
                let currentText = textContent;

                while (wordCount < wordNumber && wordIndex < currentText.length) {
                    if (/\s/.test(currentText.charAt(wordIndex))) {
                        wordCount++;
                    }
                    wordIndex++;
                }

                index = wordIndex;
                textSoFar = currentText.slice(0, index);
                textElement.innerHTML = textSoFar;
                writtenWords = countWords(textSoFar);
                writtenCountElement.textContent = `Прочитано слов: ${writtenWords}`;
                wordCountElement.textContent = `Осталось слов: ${totalWords - writtenWords}`;
                windowElement.scrollTop = windowElement.scrollHeight;
            } else {
                alert("Номер слова вне диапазона.");
            }
        }

        // При запуске страницы
        textElement.innerHTML = textSoFar;
        if (textContent) {
            startTyping();
        }

        pauseBtn.addEventListener('click', togglePause);
        decreaseSpeedBtn.addEventListener('click', decreaseSpeed);
        increaseSpeedBtn.addEventListener('click', increaseSpeed);
        fullscreenBtn.addEventListener('click', toggleFullscreen);
        clearStorageBtn.addEventListener('click', clearLocalStorage);

        // Добавляем события для кнопок изменения шрифта
        decreaseFontSizeBtn.addEventListener('click', decreaseFontSize);
        increaseFontSizeBtn.addEventListener('click', increaseFontSize);

        // Добавляем событие для перехода по номеру слова
        goToWordBtn.addEventListener('click', goToWord);

        document.addEventListener('keydown', (event) => {
            if (event.code === 'Space') {
                togglePause();
            } else if (event.code === 'ArrowUp') {
                increaseSpeed();
            } else if (event.code === 'ArrowDown') {
                decreaseSpeed();
            }
        });
    </script>
</body>
</html>
