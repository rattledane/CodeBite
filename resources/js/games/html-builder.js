import GameEngine from '../GameEngine';

export default class HtmlBuilderGame extends GameEngine {
    constructor(config) {
        super(config);
    }
}
window.HtmlBuilderGame = HtmlBuilderGame;

window.checkAnswer = function(userInput, answerKey) {
    if (!userInput) return false;
    
    const normalize = (html) => {
        return html
            .replace(/\s+/g, ' ')
            .replace(/>\s+</g, '><')
            .trim();
    };

    try {
        const userNode = document.createElement('div');
        userNode.innerHTML = userInput.trim();
        const answerNode = document.createElement('div');
        answerNode.innerHTML = answerKey.trim();

        if (userNode.innerHTML === answerNode.innerHTML) return true;
        return normalize(userInput) === normalize(answerKey);
    } catch(e) {
        return false;
    }
};

window.updatePreview = function(userInput) {
    const frame = document.getElementById('preview-frame');
    if (frame) {
        frame.srcdoc = `
        <html>
            <head>
                <style>
                    body { font-family: sans-serif; padding: 1rem; margin: 0; }
                </style>
            </head>
            <body>
                ${userInput}
            </body>
        </html>
        `;
    }
};
