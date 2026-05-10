/**
 * Normalize CSS string for consistent comparison
 */
export function normalizeCSS(cssString) {
    if (!cssString) return '';
    
    // Lowercase
    let normalized = cssString.toLowerCase();
    
    // Split into individual properties by semicolon
    let properties = normalized.split(';')
        .map(prop => prop.trim())
        .filter(prop => prop !== '');
    
    // Format each property: remove spaces around colon, collapse multiple spaces
    properties = properties.map(prop => {
        let parts = prop.split(':');
        if (parts.length >= 2) {
            let key = parts[0].trim();
            // Join the rest in case value has a colon, and replace multiple spaces with single space
            let val = parts.slice(1).join(':').trim().replace(/\s+/g, ' '); 
            return `${key}:${val}`;
        }
        return prop.replace(/\s+/g, '');
    });
    
    // Sort alphabetically so order doesn't matter
    properties.sort();
    
    // Ensure trailing semicolon
    return properties.join(';') + (properties.length > 0 ? ';' : '');
}

/**
 * Check if the user's input matches the required answer key
 */
export function checkAnswer(userInput, answerKey) {
    const userNormalized = normalizeCSS(userInput);
    const answerNormalized = normalizeCSS(answerKey);
    
    // If the required answer is empty, it's correct
    if (answerNormalized === '') return true;
    
    const answerProps = answerNormalized.split(';').filter(p => p !== '');
    const userProps = userNormalized.split(';').filter(p => p !== '');
    
    if (answerProps.length === 0) return false;
    
    // Partial match: all required properties must exist in the user's input
    return answerProps.every(prop => userProps.includes(prop));
}

/**
 * Calculate the score based on time remaining and number of attempts
 */
export function calculateScore(timeRemaining, maxScore = 100, attempts = 1) {
    if (timeRemaining === 0 && attempts > 0 && !window.forcedSubmit) {
        // Just in case it's an auto-submit from timeout
        return 0;
    }
    
    let score = 0;
    if (timeRemaining > 45) {
        score = maxScore;
    } else if (timeRemaining >= 30) {
        score = Math.floor(maxScore * 0.8); // 80
    } else if (timeRemaining >= 15) {
        score = Math.floor(maxScore * 0.6); // 60
    } else {
        score = Math.floor(maxScore * 0.4); // 40
    }
    
    // Bonus for first attempt (attempts count starts at 1 for the current submission)
    if (attempts === 1) {
        score += 10;
    }
    
    return score;
}

// Make them available globally so Alpine.js can call them from inline scripts
window.normalizeCSS = normalizeCSS;
window.checkAnswer = checkAnswer;
window.calculateScore = calculateScore;
