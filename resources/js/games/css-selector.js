import GameEngine from '../GameEngine';

export default class CssSelectorGame extends GameEngine {
    constructor(config) {
        super(config);
    }
}

window.CssSelectorGame = CssSelectorGame;

// Expose a custom checkAnswer for this game
window.checkAnswer = function(userInput, answerKey) {
    if (!userInput) return false;
    
    try {
        const container = document.getElementById('dom-playground');
        if (!container) return false;
        
        const expectedElements = Array.from(container.querySelectorAll(answerKey));
        const userElements = Array.from(container.querySelectorAll(userInput));
        
        if (expectedElements.length === 0 || userElements.length === 0) return false;
        if (expectedElements.length !== userElements.length) return false;
        
        for (let i = 0; i < expectedElements.length; i++) {
            if (expectedElements[i] !== userElements[i]) return false;
        }
        
        return true;
    } catch (e) {
        return false; // Invalid selector
    }
};

// Helper for live highlighting
window.updateHighlights = function(userInput) {
    const container = document.getElementById('dom-playground');
    if (!container) return;
    
    // Clear all previous highlights
    const allNodes = container.querySelectorAll('*');
    allNodes.forEach(node => {
        node.classList.remove('ring-4', 'ring-[#FFE500]', 'bg-yellow-100', 'bg-opacity-30', 'scale-[1.02]');
    });

    const errorMsg = document.getElementById('selector-error');
    if (errorMsg) errorMsg.style.display = 'none';

    if (!userInput) return;

    try {
        const userElements = container.querySelectorAll(userInput);
        userElements.forEach(node => {
            // Apply highlight styles
            node.classList.add('ring-4', 'ring-[#FFE500]', 'bg-yellow-100', 'bg-opacity-30', 'scale-[1.02]');
            // Add transition for smooth effect
            node.style.transition = "all 0.2s ease-in-out";
        });
    } catch (e) {
        // Invalid selector, show error
        if (errorMsg) errorMsg.style.display = 'block';
    }
};
