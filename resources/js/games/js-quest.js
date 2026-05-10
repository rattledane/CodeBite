import GameEngine from '../GameEngine';

export default class JsQuestGame extends GameEngine {
    constructor(config) {
        super(config);
    }
}
window.JsQuestGame = JsQuestGame;

window.checkAnswer = function(userInput, answerKey) {
    if (!userInput) return false;
    
    const normalize = (code) => {
        return code
            .replace(/\s+/g, ' ')
            .replace(/;\s*$/, '')
            .replace(/'/g, '"')
            .trim();
    };

    return normalize(userInput) === normalize(answerKey);
};

window.runJsCode = function(userInput) {
    const frame = document.getElementById('sandbox-frame');
    const output = document.getElementById('console-output');
    
    if (frame && output) {
        output.innerHTML = ''; 
        
        frame.srcdoc = `
        <html>
            <body>
                <script>
                    const _log = console.log;
                    console.log = function(...args) {
                        window.parent.postMessage({ type: 'log', message: args.join(' ') }, '*');
                        _log.apply(console, args);
                    };
                    
                    try {
                        let result = eval(${JSON.stringify(userInput)});
                        window.parent.postMessage({ type: 'result', message: result }, '*');
                    } catch (e) {
                        window.parent.postMessage({ type: 'error', message: e.toString() }, '*');
                    }
                </script>
            </body>
        </html>
        `;
    }
};

window.addEventListener('message', function(e) {
    const output = document.getElementById('console-output');
    if (!output) return;
    
    const data = e.data;
    if (data && data.type) {
        const line = document.createElement('div');
        line.classList.add('mb-1', 'font-mono');
        
        if (data.type === 'error') {
            line.classList.add('text-red-500');
            line.textContent = '> Error: ' + data.message;
        } else if (data.type === 'log') {
            line.classList.add('text-gray-300');
            line.textContent = '> ' + data.message;
        } else if (data.type === 'result') {
            if (data.message !== undefined && data.message !== null) {
                line.classList.add('text-[#00ff88]');
                line.textContent = '< ' + data.message;
            } else if (data.message === undefined) {
                line.classList.add('text-gray-500');
                line.textContent = '< undefined';
            }
        }
        
        output.appendChild(line);
    }
});
